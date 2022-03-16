<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{

    public function index()
    {
       return response()->json([
           
       ]);
    }


    public function create()
    {

    }


    public function store(Request $request)
    {
        return response()->json($request->toArray());
    }

    public function show(History $history)
    {

    }

    public function edit(History $history)
    {

    }

    public function update(Request $request, History $history)
    {

    }

    public function destroy(History $history)
    {

    }
}
