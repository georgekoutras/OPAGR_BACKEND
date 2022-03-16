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
                    <h3>Details for cultivation: {{$type->type}}</h3>

                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <form method="POST" action="{{ route('cul_type.update') }}" class="form-row">
                                @csrf

                                <input type="text" name="type" value="{{$type->type}}" hidden>

                                <!--                               SOIL TEMPERATURE                                  -->
                                <div class="form-group col-md-6">
                                    <label for="soil_temp_min">Minimum soil temperature (&#8451;)</label>
                                    <input type="text" class="form-control" id="soil_temp_min" name="soil_temp_min"
                                           placeholder="-40.0" value="{{$type->soil_temp_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="soil_temp_max">Maximum soil temperature (&#8451;)</label>
                                    <input type="text" class="form-control" id="soil_temp_max" name="soil_temp_max"
                                           placeholder="80.0" value="{{$type->soil_temp_max}}">
                                </div>

                                <!--                                  SOIL HUMIDITY                                  -->
                                <div class="form-group col-md-6">
                                    <label for="soil_moist_min">Minimum soil moisture (%)</label>
                                    <input type="text" class="form-control" id="soil_moist_min" name="soil_moist_min"
                                           placeholder="0" value="{{$type->soil_moist_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="soil_moist_max">Maximum soil moisture (%)</label>
                                    <input type="text" class="form-control" id="soil_moist_max" name="soil_moist_max"
                                           placeholder="100" value="{{$type->soil_moist_max}}">
                                </div>

                                <!--                                    SOIL EC                                      -->
                                <div class="form-group col-md-6">
                                    <label for="soil_ec_min">Minimum soil electrical conductivity (us/cm)</label>
                                    <input type="text" class="form-control" id="soil_ec_min" name="soil_ec_min"
                                           placeholder="0" value="{{$type->soil_ec_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="soil_ec_max">Maximum soil electrical conductivity (us/cm)</label>
                                    <input type="text" class="form-control" id="soil_ec_max" name="soil_ec_max"
                                           placeholder="20.000" value="{{$type->soil_ec_max}}">
                                </div>

                                <!--                                    SOIL PH                                      -->
                                <div class="form-group col-md-6">
                                    <label for="soil_ph_min">Minimum soil PH (us/cm)</label>
                                    <input type="text" class="form-control" id="soil_ph_min" name="soil_ph_min"
                                           placeholder="3" value="{{$type->soil_ph_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="soil_ph_max">Maximum soil PH (pH)</label>
                                    <input type="text" class="form-control" id="soil_ph_max" name="soil_ph_max"
                                           placeholder="9" value="{{$type->soil_ph_max}}">
                                </div>

                                <!--                            AIR TEMPERATURE                              -->
                                <div class="form-group col-md-6">
                                    <label for="air_temp_min">Minimum atmospheric temperature (&#8451;)</label>
                                    <input type="text" class="form-control" id="air_temp_min" name="air_temp_min"
                                           placeholder="-40.0" value="{{$type->air_temp_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="air_temp_max">Maximum atmospheric temperature (&#8451;)</label>
                                    <input type="text" class="form-control" id="air_temp_max" name="air_temp_max"
                                           placeholder="120.0" value="{{$type->air_temp_max}}">
                                </div>

                                <!--                                 AIR HUMIDITY                                    -->
                                <div class="form-group col-md-6">
                                    <label for="air_hum_min">Minimum atmospheric humidity (%)</label>
                                    <input type="text" class="form-control" id="air_hum_min" name="air_hum_min"
                                           placeholder="0" value="{{$type->air_hum_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="air_hum_max">Maximum atmospheric humidity (%)</label>
                                    <input type="text" class="form-control" id="air_hum_max" name="air_hum_max"
                                           placeholder="100" value="{{$type->air_hum_max}}">
                                </div>

                                <!--                               ATMOSPHERIC PRESSURE                              -->
                                <div class="form-group col-md-6">
                                    <label for="atmospheric_pressure_min">Minimum atmospheric pressure (Kpa)</label>
                                    <input type="text" class="form-control" id="atmospheric_pressure_min"
                                           name="atmospheric_pressure_min" placeholder="0"
                                           value="{{$type->atmospheric_pressure_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="atmospheric_pressure_max">Maximum atmospheric pressure (Kpa)</label>
                                    <input type="text" class="form-control" id="atmospheric_pressure_max"
                                           name="atmospheric_pressure_max" placeholder="120"
                                           value="{{$type->atmospheric_pressure_max}}">
                                </div>

                                <!--                                  LIGHT INTENSITY                                -->
                                <div class="form-group col-md-6">
                                    <label for="light_intensity_min">Minimum light intensity (Lux)</label>
                                    <input type="text" class="form-control" id="light_intensity_min"
                                           name="light_intensity_min" placeholder="0"
                                           value="{{$type->light_intensity_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="light_intensity_max">Maximum light intensity (Lux)</label>
                                    <input type="text" class="form-control" id="light_intensity_max"
                                           name="light_intensity_max" placeholder="200000"
                                           value="{{$type->light_intensity_max}}">
                                </div>

                                <!--                                       NOISE                                     -->
                                <div class="form-group col-md-6">
                                    <label for="noise_min">Minimum Noise (dB)</label>
                                    <input type="text" class="form-control" id="noise_min" name="noise_min"
                                           placeholder="30" value="{{$type->noise_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="noise_max">Maximum Noise (dB)</label>
                                    <input type="text" class="form-control" id="noise_max" name="noise_max"
                                           placeholder="120" value="{{$type->noise_max}}">
                                </div>

                                <!--                                 pm2.5 PARTICLES                                 -->
                                <div class="form-group col-md-6">
                                    <label for="pm25_min">Minimum pm2.5 (ug/m3)</label>
                                    <input type="text" class="form-control" id="pm25_min" name="pm25_min"
                                           placeholder="0" value="{{$type->pm25_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pm25_max">Maximum pm2.5 (ug/m3)</label>
                                    <input type="text" class="form-control" id="pm25_max" name="pm25_max"
                                           placeholder="1000" value="{{$type->pm25_max}}">
                                </div>

                                <!--                                    WIND SPEED                                   -->
                                <div class="form-group col-md-6">
                                    <label for="wind_speed_min">Minimum wind speed (m/s)</label>
                                    <input type="text" class="form-control" id="wind_speed_min" name="wind_speed_min"
                                           placeholder="0" value="{{$type->wind_speed_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="wind_speed_max">Maximum wind speed (m/s)</label>
                                    <input type="text" class="form-control" id="wind_speed_max" name="wind_speed_max"
                                           placeholder="70" value="{{$type->wind_speed_max}}">
                                </div>

                                <!--                                 RAIN ACCUMULATION                               -->
                                <div class="form-group col-md-6">
                                    <label for="rain_accumulation_min">Minimum rain accumulation (mm)</label>
                                    <input type="text" class="form-control" id="rain_accumulation_min"
                                           name="rain_accumulation_min" placeholder=""
                                           value="{{$type->rain_accumulation_min}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="rain_accumulation_max">Maximum rain accumulation (mm)</label>
                                    <input type="text" class="form-control" id="rain_accumulation_max"
                                           name="rain_accumulation_max" placeholder=""
                                           value="{{$type->rain_accumulation_max}}">
                                </div>

                                <button type="submit" class="btn btn-sm btn-outline-info">Apply changes</button>

                            </form>
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


