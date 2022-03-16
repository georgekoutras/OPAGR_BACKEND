<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['get_user'])->except('oauth_validate');
    }


    public function oauth_validate(Request $request)
    {

        $attributes = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if (!Auth::once($attributes)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Wrong credentials'
                ]
            ], 401);
        }

        // Get the client (type: Password Grant)
        $client = Passport::client()->where('name', 'OpenAgro_Password_Grant')->first();
        if (is_null($client)) return response()->json([
            'success' => false,
            'error' => 'Client does not exist'
        ], 500);


        $scope = \auth()->user()->role === 'admin' ? '*' : 'see-dashboard see-cultivations see-devices see-notifications';
        $urlOauth = Config::get('app.url') . '/oauth/token'; // valto ksana $urlOauth
        $response = Http::withHeaders(['Accept' => 'application/json'])->post($urlOauth, [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $attributes['email'],
            'password' => $attributes['password'],
            'scope' => $scope,
        ]);

        $response = $response->json();

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $response,
                'user' => \auth()->user()
            ]
        ]);
    }

    /* ----------------------------------------------------------------------------------------------- */

    public function refresh(Request $request)
    {
        $user = User::findOrFail($request['uid']);

        // Check if token has expired
        $token = Passport::token()->firstWhere('id', $this->getTokenIDFromJWT());

        if ($token->expires_at >= now() && $token->revoked === false) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'The Token has not expired yet!',
                    'expires_in' => abs(strtotime($token->expires_at) - strtotime(now()))
                ]
            ], 425);
        }

        $refresh_token = $request['refresh_token'];
        $client = Passport::client()->firstWhere('name', 'OpenAgro_Password_Grant');
        $scope = $user->role === 'admin' ? '*' : 'see-dashboard see-cultivations see-devices see-notifications';

        $urlOauth = Config::get('app.url') . '/oauth/token'; // valto ksana $urlOauth
        $response = Http::withHeaders(['Accept' => 'application/json'])->post($urlOauth, [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'redirect_uri' => $client->redirect,
            'scope' => $scope,
        ]);

        $response = $response->json();

        if (array_key_exists('error', $response)) {
            return response()->json([
                'success' => false,
                'error' => $response
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $response
            ]
        ]);
    }

    /* ----------------------------------------------------------------------------------------------- */

    public function logOut(Request $request)
    {
        $user = User::findOrFail($request['uid']);

        $token = Passport::token()
            ->where('revoked', false)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $refresh_token = Passport::refreshToken()
            ->where('revoked', false)
            ->where('access_token_id', $token->id)
            ->firstOrFail();

        $token->revoked = true;
        $refresh_token->revoked = true;
        $token->save();
        $refresh_token->save();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Successfully logout!'
            ]
        ]);
    }

    /* ----------------------------------------------------------------------------------------------- */

    private function getTokenIDFromJWT(): string
    {
        // Take the bearer token from the header, decode the payload, and take user's id
        $headers = getallheaders();
        throw_if(!array_key_exists('Authorization', $headers), new HttpException(401));
        $token = $headers['Authorization'];
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        return $jwtPayload->jti;
    }
}
