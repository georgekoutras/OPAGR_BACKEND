<!-- THROW HTTP EXCEPTION ERROR IF THIS IS NOT AN ADMIN -->
<?php
abort_if(session('role') === 'user', 404);
?>

<!-- USERS' PAGE -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@include('layouts.partials.head')

<body class="app">

@include('admin.partials.spinner')

<div>
    <!-- #Left Sidebar ==================== -->
    <div class="sidebar">
        <div class="sidebar-inner">
            <!-- ### $Sidebar Header ### -->
            <div class="sidebar-logo">
                <div class="peers ai-c fxw-nw">
                    <div class="peer peer-greed">
                        <a class='sidebar-link td-n' href="/home">
                            <div class="peers ai-c fxw-nw">
                                <div class="peer">
                                    <div class="logo">
                                        <img src="/images/logo.png" alt="">
                                    </div>
                                </div>
                                <div class="peer peer-greed">
                                    <h5 class="lh-1 mB-0 logo-text">OpenAgros</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <br>
                    <div class="peer">
                        <div class="mobile-toggle sidebar-toggle">
                            <a href="" class="td-n">
                                <i class="ti-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ### $Sidebar Menu ### -->
            <ul class="sidebar-menu scrollable pos-r">
                @include('_mine.menu')
            </ul>
        </div>
    </div>

    <!-- #Main ============================ -->
    <div class="page-container">
        <!-- ### $Topbar ### -->
    @include('admin.partials.topbar')

    <!-- ### $App Screen Content ### -->
        <main class='main-content bgc-grey-100'>
            <div id='mainContent'>
                <div class="container-fluid">

                    <h4 class="c-grey-900 mT-10 mB-30">@yield('page-header')</h4>

                    @include('admin.partials.messages')
                    @yield('content')

                    {{-- MAIN CONTENT --}}

                    <div class="row mt-4">
                        <h3>Users</h3>
                        <table class="table table-bordered"
                               style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                            <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>State</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $users = json_decode($users);
                            @endphp
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->firstName.' '.$user->lastName }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->state }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <div class="mr-1">
                                                <form method="post" action="{{route('edit_user')}}">
                                                    @csrf
                                                    <input name="email" value="{{$user->email}}" hidden>
                                                    @if($user->state === 'active')
                                                        <button type="submit" class="btn btn-sm btn-outline-info">Edit
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                            <div class="mr-1">
                                                <form method="post" action="{{route('delete_user')}}">
                                                    @csrf
                                                    <input name="email" value="{{$user->email}}" hidden>
                                                    <button
                                                        class="btn btn-sm btn-outline-dark">{{ ($user->state === 'active') ? 'Deactivate' : 'Activate'}}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-2">
                            <button class="btn btn-dark" onclick="location.href = '/users/create' ">Create User</button>
                        </div>
                    </div>


                </div>
            </div>
        </main>

        <!-- ### $App Screen Footer ### -->
        <footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600">
            <span>&copy; {{ date('Y') }}</span>
        </footer>
    </div>
</div>

<script src="{{ mix('/js/app.js') }}"></script>

<!-- Global js content -->

<!-- End of global js content-->

<!-- Specific js content placeholder -->
@stack('js')
<!-- End of specific js content placeholder -->

</body>

</html>
