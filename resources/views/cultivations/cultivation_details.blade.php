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
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <h4>General Info:</h4>
                            <form method="post" action="{{route('cultivation.update')}}">
                                @csrf
                                <div class="form-group">
                                    <label for="user">User</label>
                                    <input type="text" class="form-control" id="user" name="user"
                                           value="{{$cultivation->user()->pluck('email')->first()}}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Cultivation name" value="{{$cultivation->name}}">
                                </div>

                                <div class="form-group">
                                    <label for="comments">Write the comments</label>
                                    <textarea class="form-control" id="comments" name="comments" rows="3"
                                    >{{$cultivation->comments}}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="cultivation_type">Example select</label>
                                    <select class="form-control" id="cultivation_type" name="cultivation_type">
                                        @foreach($cultivation_types as $type)
                                            <option {{ strcmp($cultivation->cultivationType()->pluck('type')->first(), $type->type) ? '' : 'selected'}}>{{$type->type}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-sm btn-dark">Update info</button>
                            </form>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <h4>Devices that affect this field:</h4>
                            <div class="form-group">
                                <label for="device">Select device:</label>
                                <select class="form-control" id="device" name="device">
                                    @foreach($cultivation->devices()->pluck('name') as $device)
                                        <option>{{ $device }}</option>
                                    @endforeach
                                </select>
                            </div>

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


