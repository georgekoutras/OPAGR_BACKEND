<!-- DEVICES' PAGE -->

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
                        <h3>Devices</h3>

                        @php($devices = json_decode($devices))
                        @php($user = json_decode($user))

                        <table class="table table-bordered"
                               style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                            <thead>
                            <tr>
                                <th>Device</th>
                                <th>State</th>
                                <th>RockBlock</th>
                                <th>IMEI</th>
                                <th>GPRS</th>
                                <th>GPRS ID</th>
                                <th>ICCID</th>
                                <th>MSISDN</th>
                                <th>IMSI</th>
                                @if($user->role === 'admin')
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($devices as $device)
                                <tr>
                                    <td>{{ $device->name }}</td>
                                    <td>{{ $device->state }}</td>
                                    <td>{{ $device->rockblock_module ? 'true' : 'false' }}</td>
                                    <td>{{ is_null($device->imei) ? '-' : $device->imei }}</td>
                                    <td>{{ $device->gprs_module ? 'true' : 'false' }}</td>
                                    <td>{{ is_null($device->gprs_id) ? '-' : $device->gprs_id }}</td>
                                    <td>{{ is_null($device->iccid) ? '-' : $device->iccid }}</td>
                                    <td>{{ is_null($device->msisdn) ? '-' : $device->msisdn }}</td>
                                    <td>{{ is_null($device->imsi) ? '-' : $device->imsi }}</td>
                                    @if($user->role === 'admin')
                                        <td>
                                            <div class="d-flex">
                                                <div class="mr-1">
                                                    <form method="post" action="">
                                                        @csrf
                                                        <input name="email" value="{{$device->name}}" hidden>
                                                        <button type="submit" class="btn btn-sm btn-outline-info">Edit
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="mr-1">
                                                    <form method="post" action="{{route('device.delete')}}">
                                                        @csrf
                                                        <input name="device" value="{{$device->name}}" hidden>
                                                        <button
                                                            class="btn btn-sm btn-outline-dark">{{ ($device->state === 'active') ? 'Deactivate' : 'Activate'}}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <button class="btn btn-dark" onclick="location.href = '/devices/insert' ">Insert a device
                        </button>
                    </div>


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
