/**
 * Google maps block for MenaPro
 */
$(document).ready(function () {
    var map;
	if(typeof google!="undefined")
		google.maps.event.addDomListener(window, 'load', initmapsimple);

});
function initmapsimple(){
    var myLatLng = {lat: -25.363, lng: 131.044};




    $.each($('.googlemap_map'),function(key,value) {

        var mC=$(value),
            mark=[];
        var fitInHeight=mC.data("fit"),
            sattellite=mC.data("sattellite");

        if(fitInHeight && $(window).width()>=750)
        {
            var h=mC.closest(".row").height();
            mC.height(h+"px");
            mC.css("min-height","100px");
        }else
        {
            mC.css("min-height","300px");
            mC.css("max-height",$(window).height()*0.7);

        }
        var mapProp = {
            center:myLatLng,//new google.maps.LatLng(40.9954546,-1.7811548),
            zoom: 5,
            //maxZoom: 14,
            mapTypeId: sattellite?google.maps.MapTypeId.HYBRID:google.maps.MapTypeId.ROADMAP
        };
        if(typeof map_theme ==="object"){
            mapProp.styles=map_theme;
        }

        var id=mC.prop('id');
        var map = new google.maps.Map(document.getElementById(id), mapProp);

        mark[key] = JSON.parse(window["mark"+mC.data("mapid")]);
        //Create LatLngBounds object.
        var latlngbounds = new google.maps.LatLngBounds();


        var myLatLng = {lat: parseFloat(mark[key].latitude), lng: parseFloat(mark[key].longitude)};
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map
        });

        if (mark[key].tooltip_text != "") {
            var data = mark[key].tooltip_text;
            var infowindow = new google.maps.InfoWindow({
                content: data
            });
            google.maps.event.addListener(marker, 'click', function () {
                //console.log('marker .click()');
                infowindow.open(map, marker);
            });
        }
        latlngbounds.extend(marker.position);

        //Get the boundaries of the Map.
        var bounds = new google.maps.LatLngBounds();

        //Center map and adjust Zoom based on the position of all markers.

        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
        google.maps.event.addDomListener(window, "resize", function () {
            var center = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(center);

        });

        var listener = google.maps.event.addListener(map, "idle", function() { 
          if (map.getZoom() > 12) map.setZoom(12); 
          google.maps.event.removeListener(listener); 
        });
     
    });
}
