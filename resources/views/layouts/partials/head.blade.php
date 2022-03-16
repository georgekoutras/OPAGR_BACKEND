<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OpenAgros') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
{{-- <link href="{{ mix('/css/rtl.css') }}" rel="stylesheet"> --}}

<!-- Global css content -->

    <!-- End of global css content-->

    <!-- Specific css content placeholder -->
@stack('css')
<!-- End of specific css content placeholder -->

    <script type="text/javascript">

        // initialization of GMaps
        function initMap() {
            // The location of Uluru
            const uluru = {lat: 41.117416, lng: 25.396385};
            // The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 9,
                center: uluru,
                mapTypeId: 'satellite'
            });


            /* ----------------------- FIELD ----------------------- */

            const image = "http://maps.google.com/mapfiles/kml/pal2/icon13.png";
            const myMarker = new google.maps.Marker({
                position: {lat: 41.078774, lng: 25.384002},
                map,
                icon: image,

            })

            //Construct the field polygon
            const field = new google.maps.Polygon({
                paths: [
                    {lat:41.077537, lng:25.384893},
                    {lat:41.077481, lng:25.384242},
                    {lat:41.077920, lng:25.384119},
                    {lat:41.078219, lng:25.383360},
                    {lat:41.079825, lng:25.383495},
                    {lat:41.079957, lng:25.384140},
                ],
                strokeColor: "#C8EED4",
                strokeOpacity: 0.8,
                strokeWeight: 3,
                fillColor: "#32A852",
                fillOpacity: 0.35,
            });

            field.setMap(map);  // Set the field polygon to the map

            /* ----------------------------------------------------- */

            /* ---------------------- DEVICE ----------------------- */
            const devices = {
                DE1258A: {
                    center: {lat:41.077286, lng:25.384193}
                },

                DE1257A: {
                    center: {lat:41.074642, lng:25.367886}
                }
            }

            for(const device in devices){
                const deviceCircle = new google.maps.Circle({
                    strokeColor: "rgba(245,139,139,0.76)",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: "#ff5858",
                    fillOpacity: 0.35,
                    map,
                    center: devices[device].center,
                    radius: 500

                })

                const deviceMarker = new google.maps.Marker({
                    position: devices[device].center,
                    icon: "http://maps.google.com/mapfiles/kml/pal4/icon49.png",
                    map
                })
            }
            /* ----------------------------------------------------- */



        }
    </script>
</head>
