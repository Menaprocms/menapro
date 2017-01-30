/**
 * Created by Silvia on 15/07/2016.
 */
    $.extend(true,JSBlocks.blocks, {
        googlemap: {
            group: 'other',
            contentClass: 'eGooglemap',
            icon: 'eIco eIcoGooglemap',
            name: 'googlemap',
            configurable: true,
            dataStructure: {
                type:{},
                fit:{},
                address:{},
                latitude:{
                    required:true
                },
                longitude:{
                    required:true
                },
                tooltip_text:{}
            },
            data: null,
            events: {
                click:[
                    {
                        el: "#googlemap_add",
                        ck: function (e) {
                            JSBlocks.beforeSaveBlockData();
                        }
                    },
                    {
                        el: "#googlemap_advanced",
                        ck: function (e) {
                                if($('.coords').hasClass('open')){
                                    $('.coords').removeClass('open').slideUp();
                                    $('.coords_text').addClass('hidden');
                                }else {
                                    $('.coords').addClass('open').slideDown();
                                    $('.coords_text').removeClass('hidden');
                                }
                                if(JSBlocks.blocks.googlemap.edit){
                                    $('#googlemap_latitude').val(JSBlocks.getCurrentTarget().content.lat);
                                    $('#googlemap_longitude').val(JSBlocks.getCurrentTarget().content.long);
                                }
                        }
                    }
                ]
            },
            /**********************************************************************************************************/
            url:'',
            edit:0,
            index:0,
            lat:0,
            long:0,
            list:'',
            suggestion_class:'',
            last_search:'',
            autocomp:'',
            request:'',
            results:'',
            geocoder:'',
            /**********************************************************************************************************/
            ready: function () {
                $('.coords').slideUp();
                if(gmap_api_key!='') {
                    JSBlocks.blocks.googlemap.autocomp= new google.maps.places.AutocompleteService();
                    $('#googlemap_address').autocomplete({
                        source:function( request, response ){
                        response(JSBlocks.blocks.googlemap.results);
                    },
                focus: function( event, ui ) {

                            event.preventDefault();
                            this.value = ui.item.label;

                        },
                        select: function( event, ui ) {
                            event.preventDefault();
                            var ps=new google.maps.places.PlacesService($('#gmap_result_container')[0]);
                            ps.getDetails({placeId:ui.item.value},JSBlocks.blocks.googlemap.fillInAddress);
                        }
                    }).autocomplete('widget').addClass('gmap_autocomplete');

                    $('#googlemap_address').keyup(function(e){
                        if($('#googlemap_address').val()!="") {

                                    JSBlocks.blocks.googlemap.request = {
                                        input: $('#googlemap_address').val(),
                                        componentRestrictions: {}
                                    };

                                    JSBlocks.blocks.googlemap.autocomp.getPlacePredictions(JSBlocks.blocks.googlemap.request,JSBlocks.blocks.googlemap.fillInSelect);
                        }

                    });
                }
            },
            beforeSave: function () {
                var self=this;
                var latitude = $('#googlemap_latitude').val().trim();
                var lon_field=$('#googlemap_longitude')
                var longitude = lon_field.val().trim();
                var address=$('#googlemap_address').val().trim();
                if( $('#googlemap_longitude').closest('.coords').hasClass('open') && latitude!=="" && longitude!==""){
                    //Han introducido las coordenadas
                    JSBlocks.blocks.googlemap.geocoder = new google.maps.Geocoder;
                    var latlng = {lat: parseFloat(latitude), lng: parseFloat(longitude)};
                    JSBlocks.blocks.googlemap.geocoder.geocode({'location': latlng}, function(results, status) {
                        $('#googlemap_address').val(results[0].formatted_address);
                        JSBlocks._saveItem();
                    });

                }else if(latitude=="" && longitude=="" && address!=="" ){//&& !lon_field.closest('.coords').hasClass('open')
                    //Only with address, I have to geolocated the direction
                    JSBlocks.blocks.googlemap.geocoder = new google.maps.Geocoder;
                    JSBlocks.blocks.googlemap.geocoder.geocode({'address': address}, function(results, status) {
                        $('#googlemap_longitude').val(parseFloat(results[0].geometry.location.lng()));
                        $('#googlemap_latitude').val(parseFloat(results[0].geometry.location.lat()));
                        JSBlocks._saveItem();
                    });
                }else{
                    JSBlocks._saveItem();
                }
            },
            afterOpen: function () {
              
                if(gmap_api_key!=''){
                    $('#googlemap_apikey_error').addClass('hidden');
                    $('#googlemap_form').removeClass('hidden');
                }else{
                    $('#googlemap_apikey_error').removeClass('hidden');
                    $('#googlemap_form').addClass('hidden');
                }


                return true;
            },
            beforeClose:function(){
                $('.coords').removeClass('open').slideUp();
                return true;
            },
            getPreview:function(content){
                if(typeof(content)!='undefined') {
                    html = '<span><i class="eIco eIcoGooglemap"></i></span> <span class="googlemap_preview_title"><i class="fa fa-map-marker"></i>'+ content.address + '</span>';
                    return html;
                }

            },
            fillInSelect:function(predictions, status){
                if(status==google.maps.places.PlacesServiceStatus.OK){
                    var suggestions=[];
                    $.each(predictions,function(k,v){
                        suggestions.push({label:v.description,value:v.place_id});
                    });
                    JSBlocks.blocks.googlemap.results = suggestions;
                }else{
                    JSBlocks.blocks.googlemap.results ='';
                }

            },
            fillInAddress:function(PlaceResult, PlacesServiceStatus){
                $('#googlemap_address').val(PlaceResult.formatted_address);
                $('#googlemap_longitude').val(parseFloat(PlaceResult.geometry.location.lng()));
                $('#googlemap_latitude').val(parseFloat(PlaceResult.geometry.location.lat()));

            }
        }
    });
