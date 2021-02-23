    

    var myMap = null;
    var myChart;
    var jinkoInfo = [];
    var bigZoneMarkerLayer;
    var smallZoneMarkerLayer;

    var type = "big";
    var timeOption,
        liveAndWork;

    var selectedKenName = null,
        selectedShiName = null,
        selectedKuName = null,
        selectedTownName = null,
        selectedPlaceFullName = "浜松市";

    var selectedTownAllPeoplesCount = 0;
    var smallZonePointJson1 = null;
    var bigZonePointJson1 = null;

    var selectedMenuName = null;

    var selectedSpecialAgeName = $("#SpecialAgeNameReq option:selected").text(), 
        selectedDayWeekMonth =  $("#dayWeekMonthReq option:selected").val(),
        selectedSubOption = $("#dayWeekMonthReq option:selected").text();

    // init events 
    $("#timeOption").change(function()  {
        timeOption = $("option:selected",this).text();
    }).change();

    $("#liveAndWork").change(function()  {
        liveAndWork = $("option:selected",this).val();
        init();
    }).change();

    function init()  {
        if(myMap !==null )  {
            myMap.mapObj.eachLayer(function (layer) { 
                myMap.mapObj.removeLayer(layer); 
            });

            myMap.mapObj.off();
            myMap.mapObj.remove();
        }

        postData(SERVER_+"/webOri/users/getLocations.json",{
            timeOption: timeOption,
            liveAndWork: liveAndWork
        }).then(data => {
            smallZonePointJson1 = {
                "type": "FeatureCollection",
                "features": []
            };

            bigZonePointJson1 = {
                "type": "FeatureCollection",
                "features": []
            };

            $.each(data.locations, function(i,v)  {
                if(this.map_layer_level === 1) {
                    bigZonePointJson1.features.push({
                        "type":"Feature",
                        "properties":{
                            "kenName" : this.県名,
                            "shiName" : this.市,
                            "kuName": this.区
                        },
                        "geometry":{
                            "type":"Point",
                            "coordinates":[
                                this.latitude,
                                this.longitude
                            ]
                        }
                    });
                }

                if(this.map_layer_level === 2)  {
                    smallZonePointJson1.features.push({
                        "type":"Feature",
                        "properties":{
                            "kenName" : this.県名,
                            "shiName" : this.市,
                            "kuName": this.区,
                            "townName" : this.町
                        },
                        "geometry":{
                            "type":"Point",
                            "coordinates":[
                                this.latitude,
                                this.longitude
                            ]
                        }
                    });
                }
            });
            myMap.init();
        });
    }
  
    myMap = {
        mapObj: null,
        bigZoneMarkerLayer: null,
        smallZoneMarkerLayer: null,
        init: function()  {
            this.draw();
            this.cityBorderDraw();
            this.bigZoneMarkerDraw();
            this.smallZoneMarkerDraw();

            //event 
            this.zoom();
        },
        draw: function()  {
            this.mapObj = new L.Map('map', {zoom: 12, center: new L.latLng(34.75181436841190, 137.7239227294922) });	//set center from first location
            this.mapObj.addLayer(new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));	//base layer
        },
        cityBorderDraw: function()  {
            L.geoJson(cityBorderJson, {
                style: function (feature)  {
                    return {
                        fillColor: "#cacaca",
                        weight: 2,
                        color: 'black',
                        fillOpacity: 0
                    };
                },
                onEachFeature: function(feature, layer)  {
                    layer.on({
                        click: function(e)  {
                            myMap.mapObj.fitBounds(e.target.getBounds());
                        }
                    });
                }
            }).addTo(myMap.mapObj);
        },
        bigZoneMarkerDraw: function()  {  //big zone marker
            bigZoneMarkerLayer = L.geoJson(bigZonePointJson1, {
                pointToLayer: function(geoJsonPoint, latlng)  {
                    return L.marker(latlng).on('click', function(e)  {
                        type = "big";
                        var p = geoJsonPoint.properties;
                        selectedPlaceFullName = p.shiName + p.kuName;
                        selectedKenName = p.kenName;
                        selectedShiName = p.shiName
                        selectedKuName =  p.kuName;
                        
                        this.bindPopup("<div class='p-1'><h4>"+ p.kuName +"</h4></div>");
                        myMap.mapObj.setView(latlng, 14);
                        setTownName(selectedPlaceFullName);
                    }).bindPopup();
                }
            }).addTo(myMap.mapObj);
        },

        smallZoneMarkerDraw: function()  {
            smallZoneMarkerLayer =  L.geoJson(smallZonePointJson1,  {
                pointToLayer: function (geoJsonPoint, latlng) {
                    return L.marker(latlng).on('click', function(e)  {
                        type = "small";
                        var p = geoJsonPoint.properties;
                        selectedPlaceFullName = p.shiName + p.kuName + p.townName;
                        selectedKenName = p.kenName;
                        selectedShiName = p.shiName
                        selectedKuName =  p.kuName;
                        selectedTownName = p.townName;

                        myMap.mapObj.setView(latlng, 16);
                        this.bindPopup("<div class='p-1'>"+"<h6>"+ selectedPlaceFullName +"</h6>"+"</div>");
                        setTownName(selectedPlaceFullName);
                    }).bindPopup();
                }
            });
        },
        zoom: function()  {
            myMap.mapObj.on('zoomend', function()  {
                var currentZoom = myMap.mapObj.getZoom();
                if(currentZoom >= 14)  {
                    smallZoneMarkerLayer.addTo(myMap.mapObj);
                    bigZoneMarkerLayer.remove();
                }
                else  {
                    smallZoneMarkerLayer.remove();
                    bigZoneMarkerLayer.addTo(myMap.mapObj);
                }
            });
        }
    }
