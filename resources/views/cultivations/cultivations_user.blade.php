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
                @include('_mine.user_menu')
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
                        <div class="col-8 mx-auto">
                            <h3>My Cultivations</h3>
                            <table class="table table-bordered"
                                   style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                                <thead>
                                <tr>
                                    <th>Cultivation</th>
                                    <th>Comments</th>
                                    <th>Affected from</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cultivations as $cultivation)
                                    <tr>
                                        @if($cultivation->user()->get()->isNotEmpty())
                                            <td>
                                                <a href="/cultivations/details/{{$cultivation->name}}">{{$cultivation->name}}</a>
                                            </td>
                                            <td>{{$cultivation->comments}}</td>
                                            <td>{{$cultivation->devices()->pluck('name') }}</td>
                                            <td>
                                                <form method="post" action="{{route('cultivation.delete')}}">
                                                    @csrf
                                                    <input type="text" name="cultivation" value="{{$cultivation->name}}"
                                                           hidden>
                                                    <button class="btn btn-danger btn-block">Delete</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                            <a class="btn btn-outline-dark" href="/cultivations/create">Create cultivation</a>
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

