<?php 

ini_set("display_errors", 0); 
include_once $_SERVER['DOCUMENT_ROOT'].'/php/conexao.php';

// Instancia a classe
include_once 'gmaps_class.php';
$gmaps = new gMaps();

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Qual e sua Causa?</title>
    <link rel="stylesheet" type="text/css" href="/tool/gmaps/gmaps_style.css" media="screen" />
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <script type="text/javascript">   
            
        var dadosUSER = <?=$gmaps->gmaps_marker_lista();?>;  
        var map, infoWindow;

        function initMap() {

            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: -12.2122691, lng: -53.5635637},
              zoom: 5,
              mapTypeId: 'satellite'
            });

            infoWindow = new google.maps.InfoWindow;         

            // Add some markers to the map.
            // Note: The code uses the JavaScript Array.prototype.map() method to
            // create an array of markers based on a given "locations" array.
            // The map() method here has nothing to do with the Google Maps API.
            var markers = dadosUSER[0].map(function(location, i) {
                var mark = new google.maps.Marker({
                    position: dadosUSER[0][i],
                    icon: dadosUSER[1][i]['icon'], 
                    title: dadosUSER[1][i]['name']
                });
                mark.addListener('click', function() {
                    /** infoWindow.setContent('<div class="map_popup">'+
                                     '<a href="#" class="map_close" onclick="$(\'.map_popup\').hide();">Fechar</a>'+
                                     '<div class="map_home">'+
                                            '<div class="map_header">'+
                                                 '<div class="map_photo">'+
                                                     '<img title="title nome" src="'+dadosUSER[1][i]['photo']+'">'+
                                                 '</div>'+
                                                 '<div class="map_info">'+
                                                     '<strong>' + dadosUSER[1][i]['name']+'</strong>'+
                                                     '<span>' + dadosUSER[1][i]['age']+ ', ' + dadosUSER[1][i]['local'] + '</span>'+
                                                 '</div>'+
                                            '</div>'+
                                            '<div class="map_main">'+
                                                 '<p class="map_texto">'+dadosUSER[1][i]['msg']+'</p>'+
                                                 '<a class="map_smore" href="'+dadosUSER[1][i]['link']+'" target="black">Meu Perfil</a>'+
                                           '</div>'+
                                    '</div>'+
                                    '<div class="map_seta"></div>'+
                                '</div>');   **/             
                    if (infoWindow.getContent() == dadosUSER[1][i]['name']) {
                        map.setZoom(map.getZoom()+1); 
                        top.GeraConversa(dadosUSER[1][i]['idus'],dadosUSER[1][i]['idur'],dadosUSER[1][i]['photo'],dadosUSER[1][i]['name'],0);                       
                    }else{
                        infoWindow.setContent(dadosUSER[1][i]['name']);
                        infoWindow.open(map, mark);
                    }
                    map.setCenter(mark.getPosition());
                });          
       
                return mark;
            });

            // Add a marker clusterer to manage the markers.
            var markerCluster = new MarkerClusterer(map, markers,{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                                  'Seu Local Aproximado!' :
                                  'Error na Localizacao!');
            infoWindow.open(map);
        }            
        </script>
</head>
<body>
    <div id="map"></div>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZUqiIU6-x9RlmI_sSYbwseG7NSQCHc68&callback=initMap"></script>    
</body>
</html>