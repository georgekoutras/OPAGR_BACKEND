{{--@include('layouts.partials.head')--}}

{{--<div class="sidebar">--}}
{{--    <div class="sidebar-inner">--}}
{{--        <!-- ### $Sidebar Header ### -->--}}
{{--        <div class="sidebar-logo">--}}
{{--            <div class="peers ai-c fxw-nw">--}}
{{--                <div class="peer peer-greed">--}}
{{--                    <a class='sidebar-link td-n' href="/admin">--}}
{{--                        <div class="peers ai-c fxw-nw">--}}
{{--                            <div class="peer">--}}
{{--                                <div class="logo">--}}
{{--                                    <img src="/images/logo.png" alt="">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="peer peer-greed">--}}
{{--                                <h5 class="lh-1 mB-0 logo-text">OpenAgros</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <br>--}}
{{--                <div class="peer">--}}
{{--                    <div class="mobile-toggle sidebar-toggle">--}}
{{--                        <a href="" class="td-n">--}}
{{--                            <i class="ti-arrow-circle-left"></i>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        --}}
{{--        <!-- ### $Sidebar Menu ### -->--}}
{{--        <ul class="sidebar-menu scrollable pos-r">--}}
{{--            @include('_mine.menu')--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--</div>--}}





{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ auth()->user()->email }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    @if (session('success'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ session('success') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                    <ul class="list-group">--}}
{{--                        <li class="list-group-item">--}}
{{--                            Full Name: {{ $user->firstName." ".$user->lastName }}--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item">--}}
{{--                            Email: {{ $user->email }}--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item">--}}
{{--                            Phone: {{ $user->phone }}--}}
{{--                        </li>--}}
{{--                        <li class="list-group-item">--}}
{{--                            role: {{ $user->role }}--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--                <form method="GET" action="{{route('logout')}}">--}}
{{--                    @csrf--}}
{{--                    <button class="btn btn-sm btn-primary m-2 btn-block">Log out</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

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
                        <a class='sidebar-link td-n' href="/">
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

                    <div id="map" style="height: 600px; width: 100%; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);"></div>
                    <script
                        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApyXIRL4Jj8R7C-QzEfkZxiaooropApNc&callback=initMap&libraries=&v=weekly"
                        async
                    ></script>

                    <table class="table table-bordered mt-2" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                        <thead>
                        <tr>
                            <th>Devices</th>
                            <th>Battery</th>
                            <th>Connection</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>DE125874</td>
                            <td>80%</td>
                            <td>ok</td>
                        </tr>
                        <tr>
                            <td>DE458796</td>
                            <td>98%</td>
                            <td>ok</td>
                        </tr>
                        <tr>
                            <td>DE258963</td>
                            <td>46%</td>
                            <td>delay 5min</td>
                        </tr>
                        </tbody>
                    </table>

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
