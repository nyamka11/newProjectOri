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

    $("#productRequestFreeBtn").click(function()  {
        $("#basicDisplay").hide();
        $("#productRequestFreeDisplay").show();

        var rowListHtml = null;
        postData("http://192.168.120.3/webOri/users/productFree.json").then(data => {
            $.each(data.Items, function()  {
                rowListHtml +=
                '<tr>'+
                    '<th scope="row" width="40">'+
                        '<input id="checkBox" type="checkbox" rowId='+ this.id +' />'+
                    '</th>'+
                    '<td>'+ this.good_name +'</td>'+
                    '<td width="100">'+ this.save_date +'</td>'+
                    '<td>'+ this.storage_area +'</td>'+
                    '<td width="70">'+ this.available_count +'</td>'+
                    '<td width="70">'+ this.packing +'</td>'+
                    '<td width="70">'+ this.in_the_package_count +'</td>'+
                    '<td width="70">'+ this.unit_in_package +'</td>'+
                    '<td class="bg-white" width="100">'+
                        '<input type="text" class="form-control" value='+ this.request_package +' style="height:20px; padding:0px;">'+
                    '</td>'+
                '</tr>'
            });

            $("#PRF_listTable tbody").html("").html(rowListHtml);
        });
    });

    $("#requestCheck").click(function()  { //------------------------
        $("#CD_foodType").text($("#PRF_foodTypes option:selected").val());
        $("#CD_selectedDate").text($("#PRF_subOption option:selected").text());
        $("#CD_RequiredNumber").text($("#PRF_RequiredNumber").val());
        $("#CD_peopleCnt").text($("#PRF_peopleCnt").val());

        var selectedRowIds = [];
        $("#PRF_listTable #checkBox").each(function()  {  //songogdson idiig tsugluulna
            if($(this).is(":checked"))  {
                selectedRowIds.push($(this).attr("rowId"));
            }
        });

        var rowListHtml = null;
        postData("http://192.168.120.3/webOri/users/productCheck.json", {
            selectedRowIds: JSON.stringify(selectedRowIds)
        }).then(data => {
            $("#confirmDisplay").show();
            $("#productRequestFreeDisplay").hide();

            $.each(data.Items, function()  {
                rowListHtml +=
                '<tr>'+
                    '<th scope="row" width="40">'+
                        '<input id="checkBox" type="checkbox" rowId='+ this.id +' />'+
                    '</th>'+
                    '<td>'+ this.good_name +'</td>'+
                    '<td width="100">'+ this.save_date +'</td>'+
                    '<td>'+ this.storage_area +'</td>'+
                    '<td width="70">'+ this.available_count +'</td>'+
                    '<td width="70">'+ this.packing +'</td>'+
                    '<td width="70">'+ this.in_the_package_count +'</td>'+
                    '<td width="70">'+ this.unit_in_package +'</td>'+
                    '<td class="bg-white" width="100">'+ this.request_package +'</td>'+
                '</tr>'
            });
            $("#CD_listTable tbody").html("").html(rowListHtml);
        });

    });

    $("#PRD_backBtn").click(function()  {
        $("#productRequestFreeDisplay").show();
        $("#confirmDisplay").hide();
        $("#PRF_subOption option:selected").val();
    });

    $("#requestConfirmedBtn").click(function()  {
        alert("リクエストを受付ました");
    });

    //-------------------------------
    $("#productRequestFreeDisplayBackBtn").click(function()  {
        $("#basicDisplay").show();
        $("#productRequestFreeDisplay").hide();
    });

    $("#PRF_dayWeekMonth").change(function()  {
        let subOption = $("#PRF_subOption").html("");
        let optionVal = $(this).val();
        subOptionDraw(subOption, optionVal);
    }).change();

    $("#chartShow").click(function()  {
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
            ChartObj.bodyDraw(data);
            $("#selectTownAllPeopleCnt cnt").text(selectedTownAllPeoplesCount);
            $("#chartLoader").hide();
            $("#myChart").show();
        });
    });

    $("#backBtnDisplay").click(function()  {
        $("#basicDisplay").show();
        $("#chartDisplay").hide();
        myChart.destroy();
    });

    $("#backBtnRequiredDemand").click(function()  {
        $("#basicDisplay").show();
        $("#requiredDemandDisplay").hide();
    });

    $("#requiredDemandDisplayShowBtn").click(function()  {
        $("#basicDisplay").hide();
        $("#requiredDemandDisplay").show();
        $("#menuNameCombo").change();
        $("#dayWeekMonthReq").change();
    });

    $("#nutrientsShowBtn").click(function()  {
        $("#basicDisplay").hide();
        $("#nutrientsDisplay").show();
        $("#townNameNutrients").text(selectedPlaceFullName);
        $("#dayWeekMonth").change();
    });

    $("#backBtnNutrients").click(function()  {
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

            selectedTownAllPeoplesCount  = ChartObj.countDatas.reduce(function(total, num) { 
                total = isNaN(total) ? 0 : total;
                num = isNaN(num) ? 0 : num;
                return total + num;
            });
    
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
            selectedMenuName = $("option:selected", this).val();
            getMenu();
        });

        //header---
        $("#SpecialAgeNameReq").change(function()  {
            selectedSpecialAgeName = $("option:selected", this).val(), 
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
            $("#townNameNutrientsReq").text(selectedPlaceFullName);
            postData("http://192.168.120.3/webOri/users/getMenu.json", {
                menuName: selectedMenuName,
                dayWeekMonth: selectedDayWeekMonth,
                subOption: selectedSubOption,
            }).then(dataMenu => {
                $("#foodNameList").html("");
                dataMenu.getMenu.forEach(element => {
                    $("#foodNameList").append(
                        "<div class='row' style='border:1px solid #cacaca; background-color:white; padding:5px; margin:2px;'>"+
                            "<div class='col-8' style='font-size:14px;'>"+ (element.foodName.replace(/\s/g, '')) +"</div>"+
                            "<div class='col-4'><b>"+ (element.oneServingCoefficients)+ "食</b></div>"+
                        "</div>"
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
                    subOption.append("<option value="+ i +">"+ i +"日</option>");
                }
            }
            if(optionVal === "week")  {
                for(let i=1; i<=20; i++)  {
                    subOption.append("<option value="+ i +">"+ i +"週</option>");
                }
            }
            if(optionVal === "month") {
                for(let i=1; i<=12; i++)  {
                    subOption.append("<option value="+ i +">"+ i +"か月</option>");
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
    //*** functions end ***/
