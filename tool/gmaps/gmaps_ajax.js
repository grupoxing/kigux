// Referência para a instância de GMap2
var gmaps = {
    obj: null,
    geocoder: null,
    markers: [],
    inicializa: function(id, myOptions){
        var options = {
          zoom: 3,
          center: new google.maps.LatLng(0,0),
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          streetViewControl: false,
          disableDefaultUI:true,
          scrollwheel:false,
          disableDoubleClickZoom: true
        };

        if (myOptions) options = $.extend(options,myOptions)

        this.obj = new google.maps.Map(document.getElementById(id), options);

        // Cria o objeto que resolverá as consultas de endereço
        this.geocoder = new google.maps.Geocoder();
    },
    addListener: function(listener, func){
        google.maps.event.addListener(this.obj, listener, func);
    },
    strToLatLng: function(strLatLng){
        points = strLatLng.replace(/[\(\)]/g,'').split(',');
        latLng = new google.maps.LatLng(points[0],points[1]);

        return latLng;
    },
    strToBounds: function(strBounds){
        points = strBounds.replace(/[\(\)]/g,'').split(',');

        latLng1 = new google.maps.LatLng(points[0],points[1]);
        latLng2 = new google.maps.LatLng(points[2],points[3]);

        bound = new google.maps.LatLngBounds(latLng1, latLng2)

        return bound;
    },
    removeAllMarker: function(){
        if (this.markers) {
            for (i in this.markers) {
                 this.markers[i].setMap(null);
            }
            this.markers = [];
        }
    },
    addMarker: function(myOptions) {
        
      var options = (myOptions) ? myOptions : { map: this.obj };
        
      marker = new google.maps.Marker(options);
      
      this.markers.push(marker);

      return marker
    },
    getLocationPoints: function(endereco, callback){
        this.geocoder.geocode({address:endereco}, function(point, status){
            if (point && status == google.maps.GeocoderStatus.OK) {
                callback(point);
            }
            else
            {
                callback([]);
            }
        });
    },
    centerByPoint:function (point)
    {
        var location = point.geometry.location;            
        var bounds = point.geometry.viewport;
        this.setPosition(location, bounds);
    },
    setPosition: function(latlng, bounds){
        if(bounds) this.obj.fitBounds(bounds);
        this.obj.setCenter(latlng);
    },
    ZoomIn: function(zoom)
    {
        this.obj.setZoom(this.obj.getZoom()+1)
    },
    ZoomOut: function(zoom)
    {
        this.obj.setZoom(this.obj.getZoom()-1)
    }
    
};
/*
// Referência para a instância de GClientGeocoder
var geocoder;

// Array para mapear níveis de Zoom com a precisão do resultado
// Sinta-se livre para realizar o mapeamento achar mais conveniente.
// Note que quanto maior o número, maior o nível de zoom.
var nivelZoom = [];
    nivelZoom[0] = 2;
    nivelZoom[1] = 8;
    nivelZoom[2] = 9;
    nivelZoom[3] = 10;
    nivelZoom[4] = 12;
    nivelZoom[5] = 13;
    nivelZoom[6] = 14;
    nivelZoom[7] = 15;
    nivelZoom[8] = 16;

var markers = [];
var infowindows = [];
var infowindowopen = null;


// Função chamada ao carregar a página HTML
function inicializa(id, myOptions) {
    //var latlng = new google.maps.LatLng(-14, -54);
    
    var options = {
      zoom: 10,
      center: new google.maps.LatLng(google.loader.ClientLocation.latitude,google.loader.ClientLocation.longitude),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: false,
      disableDefaultUI:true,
      scrollwheel:false,
      disableDoubleClickZoom: true
    };
    
    
    if (myOptions)
        options = $.extend(options,myOptions)
    
    map = new google.maps.Map(document.getElementById(id), options);
    
    // Cria o objeto que resolverá as consultas de endereço
    geocoder = new google.maps.Geocoder();
}*/

/*
***********************
***********************
***********************
***********************
***********************

function getLocationPoints(endereco, callback){
    geocoder.geocode({address:endereco}, function(point, status){
        
        if (point && status == google.maps.GeocoderStatus.OK) {
            callback(point);
        }
        else
        {
            callback([]);
        }
        
    });
}

function centerLocationPoint(point)
{
    var location = point.geometry.location;            
    var bounds = point.geometry.viewport;
    setMapPosition(location, bounds);
}


function addMarker(latlng, icon) {
  marker = new google.maps.Marker({
    position: latlng,
    map: map,
    icon: icon
  });
  return marker
}
***********************
***********************
***********************
********************** */
/*function centerLocationPoint(endereco)
{
    getLocationPoint(endereco, function(location, bounds){
        setMapPosition(location, bounds);
    });
}

// Função chamada quando o usuário envia a consulta
function getLocationPoint(endereco, callback){
    // Realiza a consulta. resolverEnderecos é a função callback
    // Javascript que será chamada quando o método getLocations do
    // objeto geocoder retornar uma resposta.
    
    geocoder.geocode({address:endereco}, function(point, status){
        
        if (point && status == google.maps.GeocoderStatus.OK && point.length > 0) {
            
            var local = point[0];            
            var location = local.geometry.location;            
            var bounds = local.geometry.viewport;
            callback(location, bounds);
        }
        
    });
}



function addMarker(latlng, icon) {
  marker = new google.maps.Marker({
    position: latlng,
    map: mapaobj,
    icon: icon
  });
  markers.push(marker);
  return marker
}

function addDraggableMarker(latlng) {
  marker = new google.maps.Marker({
    position: latlng,
    map: mapaobj,
    draggable: true
  });
  markers.push(marker);
  return marker
}

function removeAllMarker(){
    if (markers) {
        for (i in markers) {
            markers[i].setMap(null);
        }
        markers = [];
    }
}




*/