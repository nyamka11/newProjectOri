    

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

    var selectedDepoId = 0;

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
        /** tolow solj bgaa hamgiin chuhal heseg 
         * 0: LIVE
         * 1: WORK
         * 2: DEPO
        */
        liveAndWork = parseInt($("option:selected",this).val());

        if(liveAndWork === MODE_DEPO)  {
            $("#chartShow").hide();
            $("#nutrientsShowBtn").hide();
            $(".sysPinkColor").hide();
        }
        else {
            $("#chartShow").show();
            $("#nutrientsShowBtn").show();
            $(".sysPinkColor").show();
        }

        init();
    }).change();

    function init()  {
        selectedShiName = "浜松市";
        selectedKuName = null;
        selectedTownName = null;
        type = "big";
        setTownName(selectedShiName);
        $('#timeOption').val(0).change();
     
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
                            "kuName": this.区,
                            "assignedName": this.場所名前
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
                            "id" : this.id,
                            "kenName" : this.県名,
                            "shiName" : this.市,
                            "kuName": this.区,
                            "townName" : this.町,
                            "assignedName": this.場所名前
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
                            // myMap.mapObj.fitBounds(e.target.getBounds());
                        }
                    });
                }
            }).addTo(myMap.mapObj);
        },
        bigZoneMarkerDraw: function()  {  //big zone marker
            bigZoneMarkerLayer = L.geoJson(bigZonePointJson1, {
                
                pointToLayer: function(geoJsonPoint, latlng)  {
                    var marker = markerIcon(liveAndWork, latlng);

                    return marker.on('click', function(e)  {
                        type = "big";
                        var p = geoJsonPoint.properties;
                        selectedPlaceFullName = p.shiName + p.kuName;
                        selectedKenName = p.kenName;
                        selectedShiName = p.shiName
                        selectedKuName =  p.kuName;
                        
                        this.bindPopup("<div class='p-1'><h4>"+ p.kuName +"</h4></div>");
                        // myMap.mapObj.setView(latlng, 13);
                        setTownName(selectedPlaceFullName);
                    }).bindPopup();
                }
            }).addTo(myMap.mapObj);
        },

        smallZoneMarkerDraw: function()  {
            smallZoneMarkerLayer =  L.geoJson(smallZonePointJson1,  {
                pointToLayer: function (geoJsonPoint, latlng) {
                    var marker = markerIcon(liveAndWork, latlng);
                    return marker.on('click', function(e)  {
                        type = "small";
                        var p = geoJsonPoint.properties;
                        selectedPlaceFullName = p.shiName + p.kuName + p.townName;
                        selectedKenName = p.kenName;
                        selectedShiName = p.shiName
                        selectedKuName =  p.kuName;
                        selectedTownName = p.townName;

                        if(liveAndWork == 2)  { //aguulah songoson bol
                            selectedPlaceFullName=p.kuName + p.assignedName;
                            selectedDepoId =  p.id;
                        }

                        // myMap.mapObj.setView(latlng, 16);
                        this.bindPopup(
                            "<div class='p-1'>"+
                                "<h4>"+ selectedPlaceFullName +"</h4>"+
                                "<h6 class='text-muted'><i class='fa fa-link'></i><a href=''> www.example.com</a></h6>"+
                                "<h6 class='text-muted'><i class='fa fa-phone'></i> 070-8888-9999</h6>"+
                                "<img src='https://image.minkou.jp/images/school_img/1754/750_hamamatsugakuinkoukou.jpg' width='200px' height='150px'>"+
                            "</div>"
                        );

                        if(liveAndWork === MODE_DEPO)  {
                            this.bindPopup(
                                "<div class='p-1'>"+
                                    "<h4>"+ p.id +"</h4>"+
                                    "<h4>"+ selectedPlaceFullName +"</h4>"+
                                    "<h6 class='text-muted'><i class='fa fa-link'></i><a href=''> www.example.com</a></h6>"+
                                    "<h6 class='text-muted'><i class='fa fa-phone'></i> 070-8888-9999</h6>"+
                                    "<img src='https://image.minkou.jp/images/school_img/1754/750_hamamatsugakuinkoukou.jpg' width='200px' height='150px'>"+
                                "</div>"
                            );
                        }
                        else this.bindPopup("<div class='p-1'>"+"<h4>"+ selectedPlaceFullName +"</h4>");

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
                    selectedDepoId = 0;
                    smallZoneMarkerLayer.remove();
                    bigZoneMarkerLayer.addTo(myMap.mapObj);
                }
            });
        }
    }
