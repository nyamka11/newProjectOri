

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
        selectedPlaceFullName = null;

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

    // init
    function init()  {
        if(myMap !==null )  {
            myMap.mapObj.eachLayer(function (layer) { 
                myMap.mapObj.removeLayer(layer); 
            });

            myMap.mapObj.off();
            myMap.mapObj.remove();
        }

        postData("http://192.168.120.3/webOri/users/getLocations.json",{
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

    document.querySelector("#chartShow").addEventListener("click", function() {
        $("#chartDisplay").show();
        $("#basicDisplay").hide();

        $("#chartLoader").show();
        $("#myChart").hide();

        postData(ownServerUrl, { 
            kenName: selectedKenName,
            shiName: selectedShiName,  
            kuName: selectedKuName,
            townName: selectedTownName, 
            type: type,
            timeOption: timeOption,
            liveAndWork: liveAndWork

        })
        .then(data => {
            setTownName(getPlaceName(data['0歳'][0]));
            ChartObj.bodyDraw(data);
            $("#selectTownAllPeopleCnt cnt").text(selectedTownAllPeoplesCount);
            $("#chartLoader").hide();
            $("#myChart").show();
        });
    });

    document.querySelector("#backBtnDisplay").addEventListener("click", function() {
        $("#basicDisplay").show();
        $("#chartDisplay").hide();
        myChart.destroy();
    });

    document.querySelector("#backBtnRequiredDemand").addEventListener("click", function() {
        $("#basicDisplay").show();
        $("#requiredDemandDisplay").hide();
    });

    document.querySelector("#requiredDemandDisplayShowBtn").addEventListener("click", function() {
        $("#basicDisplay").hide();
        $("#requiredDemandDisplay").show();
        $("#menuNameCombo").change();
        $("#dayWeekMonthReq").change();
    });

    document.querySelector("#nutrientsShowBtn").addEventListener("click", function() {
        $("#basicDisplay").hide();
        $("#nutrientsDisplay").show();
        $("#townNameNutrients").text(getPlaceName(jinkoInfo[0]));
        $("#dayWeekMonth").change();
    });

    document.querySelector("#backBtnNutrients").addEventListener("click", function() {
        $("#nutrientsDisplay").hide();
        $("#basicDisplay").show();
    });

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

    var ChartObj = {
        countDatas: [],
        labelDatas: [],
        bodyDraw: function(data)  {
            ChartObj.countDatas = [];
            ChartObj.labelDatas = [];
            jinkoInfo = [];

            for(const key in data) {
                if(data[key][0] == undefined) continue;
                data[key][0].name = key;
                jinkoInfo.push(data[key][0]);
            }
    
            for(var index in jinkoInfo )  {
                jinkoInfo[index].gTotal = parseInt(jinkoInfo[index].Male) +  parseInt(jinkoInfo[index].Female);
                ChartObj.labelDatas.push(jinkoInfo[index].name);
                ChartObj.countDatas.push(jinkoInfo[index].gTotal);
            }

            selectedTownAllPeoplesCount  = ChartObj.countDatas.reduce((a, b) => a + b, 0);
    
            if(myChart!=null)  {
                myChart.destroy();
            }
    
            var ctx = document.getElementById('myChart').getContext('2d');
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ChartObj.labelDatas,
                    datasets: [{
                        label: '年齢',
                        data: ChartObj.countDatas,
                        backgroundColor: CHART_BACKGROUND_COLORS,
                        borderColor: CHART_BORDER_COLORS,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    },
                    title: {
                        display: true,
                        text: '年齢'
                    }
                }
            });
        }
    }

    //*** RequiredDemand event start ***/
        $("#menuNameCombo").change(function()  {
            selectedMenuName = $("option:selected", this).text();
            getMenu();
        });

        //header---
        $("#SpecialAgeNameReq").change(function()  {
            selectedSpecialAgeName = $("option:selected", this).text(), 
            getNutrientsReqData();
        });

        $("#dayWeekMonthReq").change(function()  {
            let subOption = $("#subOptionReq").html("");
            let optionVal = $(this).val();
            subOptionDraw(subOption, optionVal);

            selectedDayWeekMonth = $("option:selected", this).val();
            $("#subOptionReq").change();

        });

        $("#subOptionReq").change(function()  {
            selectedSubOption = $("option:selected", this).text();
            getMenu();
            // getNutrientsReqData();
        });

        function getMenu() {

            console.log({
                menuName: selectedMenuName,
                dayWeekMonth: selectedDayWeekMonth,
                subOption: selectedSubOption,
            });

            postData("http://192.168.120.3/webOri/users/getMenu.json", {
                menuName: selectedMenuName,
                dayWeekMonth: selectedDayWeekMonth,
                subOption: selectedSubOption,
            }).then(dataMenu => {
                $("#foodNameList").html("");
                $("#townNameNutrientsReq").text(getPlaceName(jinkoInfo[0]));

                dataMenu.getMenu.forEach(element => {
                    $("#foodNameList").append(
                        '<li class="list-group-item d-flex justify-content-between align-items-center">'
                            + (element.foodName.replace(/\s/g, '')) +"<b>"+ (element.oneServingCoefficients)+ "</b>"+
                        '</li>'
                    );
                });

                getNutrientsReqData();
            });
        }

        function getNutrientsReqData()  {  // getNutrientsReqData data loading fn start 
            var param = {
                menuName: $("#menuNameCombo option:selected").text(),
                SpecialAgeName: selectedSpecialAgeName,
                dayWeekMonth: selectedDayWeekMonth,
                subOption: selectedSubOption,
                jinkoInfo: JSON.stringify(jinkoInfo),
            };

            postData("http://192.168.120.3/webOri/users/getReqNutrientList.json", param).then(data => {
                $("#tableBodyReq1").html("").html(data.htmlTable);
            });
        }

        function subOptionDraw(subOption, optionVal)  {
            if(optionVal === "day") {
                for(let i=1; i<=31; i++) {
                    subOption.append("<option>"+ i +"日</option>");
                }
            }
            if(optionVal === "week")  {
                for(let i=1; i<=20; i++)  {
                    subOption.append("<option>"+ i +"週</option>");
                }
            }
            if(optionVal === "month") {
                for(let i=1; i<=12; i++)  {
                    subOption.append("<option>"+ i +"か月</option>");
                }
            }
        }

    //*** RequiredDemand event end ***/

    //*** Nutrients start ***/
        $("#dayWeekMonth").change(function()  {
            let subOption = $("#subOption").html("");
            let optionVal = $(this).val();
            if(optionVal === "day") {
                for(let i=1; i<=31; i++) {
                    subOption.append("<option>"+ i +"日</option>");
                }
            }

            if(optionVal === "week") {
                for(let i=1; i<=20; i++) {
                    subOption.append("<option>"+ i +"週</option>");
                }
            }

            if(optionVal === "month") {
                for(let i=1; i<=12; i++) {
                    subOption.append("<option>"+ i +"か月</option>");
                }
            }
            getNutrientsData(); 
        })

        $("#SpecialAgeName").change(function()  {
            getNutrientsData();
        });

        $("#subOption").change(function()  {
            getNutrientsData();
        });
    //*** Nutrients end ***/

    //*** functions start ***/
        function setTownName(name)  { // Display name change fn start 
            document.getElementById('townName').innerHTML = name;
            document.getElementById('townNameChart').innerHTML = name;
        }

        function getNutrientsData()  {  // getNutrients data loading fn start
            postData("http://192.168.120.3/webOri/users/getNutrients.json", 
            {
                SpecialAgeName: $("#SpecialAgeName option:selected").val(),
                dayWeekMonth: $("#dayWeekMonth option:selected").val(),
                subOption: $("#subOption option:selected").text(),
                jinkoInfo: JSON.stringify(jinkoInfo),
            }).then(data => {
                $("#tableBody").html(data.nutrients);
            });
        }

        function getPlaceName(data)  {  /** データから場所の名前をもらう。start */
            let name;
            // if(data['市'] != undefined) name = data['市'];
            // if(data['区'] != undefined) name += data['区'];
            // if(data['町'] != undefined) name += data['町'];
            return name;
        }
    //*** functions end ***/