<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <script>
        L_NO_TOUCH = false;
            L_DISABLE_3D = false;
    </script>

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <style>
        #map {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.js">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.0/css/all.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/python-visualization/folium/folium/templates/leaflet.awesome.rotate.min.css" />

    <meta name="viewport" content="width=device-width,
                initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <style>
        #map_a547671471a4f81a5828924aa034da6f {
            position: relative;
            width: 100.0%;
            height: 100.0%;
            left: 0.0%;
            top: 0.0%;
        }

        .leaflet-container {
            font-size: 1rem;
        }
    </style>

</head>

<body>

@if ($latitude && $longitude)
    <div class="folium-map" id="map_a547671471a4f81a5828924aa034da6f"></div>
@else
 <div style="margin: 30px; font-size: 20px; ">location not available for <span style="">{{$spoofdomain}}</span></div>
@endif
</body>
<script>
    var map_a547671471a4f81a5828924aa034da6f = L.map(
                "map_a547671471a4f81a5828924aa034da6f",
                {
                    center: [{{$latitude}}, {{$longitude}}],
                    crs: L.CRS.EPSG3857,
                    zoom: 10,
                    zoomControl: true,
                    preferCanvas: false,
                }
            );

            

        
    
            var tile_layer_3440b7dc53817d1b4b36208909d88435 = L.tileLayer(
                "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
                {"attribution": "Data by \u0026copy; \u003ca target=\"_blank\" href=\"http://openstreetmap.org\"\u003eOpenStreetMap\u003c/a\u003e, under \u003ca target=\"_blank\" href=\"http://www.openstreetmap.org/copyright\"\u003eODbL\u003c/a\u003e.", "detectRetina": false, "maxNativeZoom": 18, "maxZoom": 18, "minZoom": 0, "noWrap": false, "opacity": 1, "subdomains": "abc", "tms": false}
            ).addTo(map_a547671471a4f81a5828924aa034da6f);
        
    
            var marker_9fbdd0b3158906aaa671ccf3117bfa3a = L.marker(
                [{{$latitude}},{{$longitude}}],
                {}
            ).addTo(map_a547671471a4f81a5828924aa034da6f);
        
    
            var icon_fea0e438cba6c31fb6f2c2047fb7f765 = L.AwesomeMarkers.icon(
                {"extraClasses": "fa-rotate-0", "icon": "info-sign", "iconColor": "white", "markerColor": "red", "prefix": "glyphicon"}
            );
            marker_9fbdd0b3158906aaa671ccf3117bfa3a.setIcon(icon_fea0e438cba6c31fb6f2c2047fb7f765);
        
    
        var popup_a088dece8e829e7c1106a4b54a0f9bee = L.popup({"maxWidth": "100%"});

        
            
                var html_d13e99ff6ec3646b7cd3d9e1645fa599 = $(`<div id="html_d13e99ff6ec3646b7cd3d9e1645fa599" style="width: 100.0%; height: 100.0%;">${{$spoofdomain}}</div>`)[0];
                popup_a088dece8e829e7c1106a4b54a0f9bee.setContent(html_d13e99ff6ec3646b7cd3d9e1645fa599);
            
        

        marker_9fbdd0b3158906aaa671ccf3117bfa3a.bindPopup(popup_a088dece8e829e7c1106a4b54a0f9bee)
        ;

        
    
</script>

</html>

