    /** チャートを描くする機能です。*/
    var myChart;
    var jinkoInfo = [];


    function chart(data)  {
        var countData = [];
        var label =  [];

        jinkoInfo = [];
        for(const key in data) {
            if(data[key][0] == undefined) continue;
            data[key][0].name = key;
            jinkoInfo.push(data[key][0]);
        }

        for(var index in jinkoInfo )  {
            jinkoInfo[index].gTotal = parseInt(jinkoInfo[index].Male) +  parseInt(jinkoInfo[index].Female);
            label.push(jinkoInfo[index].name);
            countData.push(jinkoInfo[index].gTotal);
            // console.log(jinkoInfo[index]);
        }

        if(myChart!=null)  {
            myChart.destroy();
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: label,
                datasets: [{
                    label: '年齢',
                    data: countData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        '#5be67b'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        '#28a745'
                        
                    ],
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
    };

    /** マップを描くする機能です */
    function map()  {
        var map = new L.Map('map', {zoom: 12, center: new L.latLng(34.75181436841190, 137.7239227294922) });	//set center from first location
        map.addLayer(new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));	//base layer

        /** border zone start */
            var cityBorder = L.geoJson(cityBorderJson, {
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
                            map.fitBounds(e.target.getBounds());
                        }
                    });
                }
            });
            cityBorder.addTo(map);
        /** border zone end */

        /** big point zone start */
            var bigZonePoint = L.geoJson(bigZonePointJson, {
                pointToLayer: function (geoJsonPoint, latlng) {
                    return L.marker(latlng).on('click', function(e)  {
                        var own = this;
                        map.setView(latlng, 14);
                        postData(ownServerUrl, { district: geoJsonPoint.properties.Name, type: "big" })
                        .then(data => {
                            this.bindPopup("<div class='p-1'><h4>"+ geoJsonPoint.properties.Name +"</h4></div>");
                            setTownName("浜松市"+geoJsonPoint.properties.Name);
                            chart(data);
                        });
                    })
                    .bindPopup(
                        '<div class="spinner-border" role="status">'+
                            '<span class="sr-only">Loading...</span>'+
                        '</div>'
                    );
                }
            });
            bigZonePoint.addTo(map);
        /** big point zone end */

        /** small point zone start */
            var smallZonePoint = L.geoJson(smallZonePointJson,  {
                pointToLayer: function (geoJsonPoint, latlng) {
                    return L.marker(latlng).on('click', function(e)  {
                        var own = this;
                        map.setView(latlng, 16);
                        postData(ownServerUrl, { town: geoJsonPoint.properties.name, type: "small" })
                        .then(data => {
                            this.bindPopup(
                                "<div class='p-1'>"+
                                    "<h6>"+ getPlaceName(data['0歳'][0]) +"<br/>"+
                                        "郵便番号 : "+ data['0歳'][0]['郵便番号'] +
                                    "</h6>"+
                                "</div>"
                            );

                            setTownName(getPlaceName(data['0歳'][0]));
                            chart(data);
                        });
                    })
                    .bindPopup(
                        '<div class="spinner-border" role="status">'+
                            '<span class="sr-only">Loading...</span>'+
                        '</div>'
                    );
                }
            });
        /** small point zone end */

        /** Data load start */
            postData(ownServerUrl, { 
                district: "init", type: "big"
            }).then(data => {
                chart(data);
            });
        /** Data load end */

        //*** event start ***/
            /** zoom start */
                map.on('zoomend', function()  {
                    var currentZoom = map.getZoom();
                    // console.log(currentZoom);
                    if(currentZoom >= 14)  {
                        smallZonePoint.addTo(map);
                        bigZonePoint.remove();
                    }
                    else {
                        smallZonePoint.remove();
                        bigZonePoint.addTo(map);
                    }
                });
            /** zoom end */


            /** backBtnAction start */    
                // document.querySelector("#backBtnAction").addEventListener("click", function() {
                //     map.setView([34.75181436841190, 137.7239227294922], 12);
                // });
            /** backBtnAction end */

            //*** Chart start ***/
                /** chartShow Btn start */
                    document.querySelector("#chartShow").addEventListener("click", function() {
                        $("#chartDisplay").show();
                        $("#basicDisplay").hide();
                    });
                /** chartShow Btn end */
            //*** Chart end ***/

            //*** RequiredDemand start ***/
                /** backBtnRequiredDemand Btn start */
                    document.querySelector("#backBtnRequiredDemand").addEventListener("click", function() {
                        $("#basicDisplay").show();
                        $("#requiredDemandDisplay").hide();
                    });
                /** backBtnRequiredDemand Btn end */

                /** requiredDemandDisplayShowBtn start */
                    document.querySelector("#requiredDemandDisplayShowBtn").addEventListener("click", function() {
                        $("#basicDisplay").hide();
                        $("#requiredDemandDisplay").show();
                        $("#menuNameCombo").change();
                    });
                /** requiredDemandDisplayShowBtn end */
                
                /** menuNameComboReq event start */
                    $("#menuNameCombo").change(function()  {
                        postData("http://localhost/webOri/users/getMenu.json", {
                            menuName: $("option:selected", this).text()
                        }).then(dataMenu => {
                            $("#foodNameList").html("");
                            $("#townNameNutrientsReq").text(getPlaceName(jinkoInfo[0]));

                            dataMenu.getMenu.forEach(element => {
                                $("#foodNameList").append(
                                    '<button type="button" class="list-group-item list-group-item-action">'
                                        + (element.foodName) +
                                    '</button>'
                                );
                            });

                            getNutrientsReqData();
                        });
                    });

                    $("#dayWeekMonthReq").change(function()  {
                        let subOption = $("#subOptionReq").html("");
                        let optionVal = $(this).val();
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
                        getNutrientsReqData(); 
                    })

                    $("#SpecialAgeNameReq").change(function()  {
                        getNutrientsReqData();
                    });

                    $("#dayWeekMonthReq").change(function()  {
                        getNutrientsReqData();
                    }).change();

                    $("#subOptionReq").change(function()  {
                        getNutrientsReqData();
                    });
                /** menuNameCombo event end */

            //*** RequiredDemand end ***/

            //*** Nutrients start ***/
                /** backBtnDisplay start */    
                    document.querySelector("#backBtnDisplay").addEventListener("click", function() {
                        $("#basicDisplay").show();
                        $("#chartDisplay").hide();
                    });
                /** backBtnAction end */

                /** Nutrients combo change events start */
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

                /** Nutrients combo change events end */

                /** nutrientsShowBtn start */
                    document.querySelector("#nutrientsShowBtn").addEventListener("click", function() {
                        $("#basicDisplay").hide();
                        $("#nutrientsDisplay").show();
                        $("#townNameNutrients").text(getPlaceName(jinkoInfo[0]));
                        $("#dayWeekMonth").change();
                    });
                /** nutrientsShowBtn end */
                
                /** backBtnNutrients start */
                    document.querySelector("#backBtnNutrients").addEventListener("click", function() {
                        $("#nutrientsDisplay").hide();
                        $("#basicDisplay").show();
                    });
                /** backBtnNutrients end */
            //*** Nutrients end ***/

        //*** event end ***/
    };

    //*** functions start ***/
        /** Display name change fn start */
            function setTownName(name)  {
                document.getElementById('townName').innerHTML = name;
                document.getElementById('townNameChart').innerHTML = name;
            }  
        /** Display name change fn end */

        /** getNutrients data loading fn start  */
            function getNutrientsData()  {
                console.log(jinkoInfo);
                postData("http://localhost/webOri/users/getNutrients.json", 
                {
                    SpecialAgeName: $("#SpecialAgeName option:selected").val(),
                    dayWeekMonth: $("#dayWeekMonth option:selected").val(),
                    subOption: $("#subOption option:selected").text(),
                    jinkoInfo: JSON.stringify(jinkoInfo),
                }).then(data => {
                    console.log(data);
                    $("#tableBody").html(data.nutrients);
                });
            }
        /** getNutrients data loading fn  end*/

        /** getNutrientsReqData data loading fn start  */
            function getNutrientsReqData()  {
                let param = {
                    menuName: $("#menuNameCombo option:selected").text(),
                    SpecialAgeName: $("#SpecialAgeNameReq option:selected").val(),
                    dayWeekMonth: $("#dayWeekMonthReq option:selected").val(),
                    subOption: $("#subOptionReq option:selected").text(),
                    jinkoInfo: JSON.stringify(jinkoInfo),
                };

                console.log(param);

                postData("http://localhost/webOri/users/getReqNutrientList.json", param).then(data => {
                    $("#tableBodyReq1").html("").html(data.htmlTable);
                });
            }
        /** getNutrientsReqData data loading fn  end*/

        /** データから場所の名前をもらう。start */
            function getPlaceName(data)  {
                let name;
                if(data['市'] != undefined) name = data['市'];
                if(data['区'] != undefined) name += data['区'];
                if(data['町'] != undefined) name += data['町'];
                return name;
            }
        /** データから場所の名前をもらう。end */
    //*** functions end ***/

    map();
