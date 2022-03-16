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
                <div class="container">

                    <h4 class="c-grey-900 mT-10 mB-30">@yield('page-header')</h4>

                    @include('admin.partials.messages')
                    @yield('content')

                    {{-- MAIN CONTENT --}}
                    <div class="row mt-4">
                        <div class="col-8 mx-auto">
                            <h3>Cultivation Types</h3>
                            <table class="table table-bordered"
                                   style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                                <thead>
                                <tr>
                                    <th>Cultivation Type</th>
                                    <th>State</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $cul_types = json_decode($cul_types);
                                @endphp
                                @foreach($cul_types as $type)
                                    <tr>
                                        <td><a href="/cultivation/types/{{$type->type}}">{{ $type->type }}</a></td>
                                        <td>{{$type->deleted_at ? 'inactive' : 'active'}}</td>
                                        <td>
                                            <div class="d-flex">
                                                <div class="mr-1">
                                                    @if(!$type->deleted_at)
                                                        <a class="btn btn-sm btn-outline-info"
                                                           href="/cultivation/types/{{$type->type}}">Edit
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="mr-1">
                                                    <form method="post" action="{{route('cul_type.delete')}}">
                                                        @csrf
                                                        <input name="type" value="{{$type->type}}" hidden>
                                                        <input name="delete" value="{{$type->deleted_at}}" hidden>
                                                        <button
                                                            class="btn btn-sm btn-outline-danger">{{$type->deleted_at ? 'Activate' : 'Deactivate'}}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-8 mx-auto">
                            <button class="btn btn-dark" onclick="location.href = '/cultivation/types/insert' ">Insert
                                cultivation type
                            </button>
                        </div>
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
