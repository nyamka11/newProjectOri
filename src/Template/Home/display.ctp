<?= $this->element('header') ?> 
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>

 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

   <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

   <?= $this->Html->css('leaflet-search.css') ?>

   
   <style>
        #map {
            height: 700px; 
            width: 100%; 
        }
        #findbox {
            height:20px;
            margin-top:10px;
        }
        .search-input  {
            width:80%;
        }
        .search-tooltip {
            width: 200px;
        }
        .leaflet-control-search .search-cancel {
            position: static;
            float: left;
            margin-left: -22px;
        }
        .textBox {
            border: 1px solid black;
            float: left;
            padding: 6px 67px 5px 66px;
            text-align: center;
            background-color: #cacaca;
        }
   </style>

    <div id="mainContainer">
        <div id="basicDisplay">
            <div class="row container mt-1  m-auto border">
                <div class="col-12 p-3">
                    <!-- <button id="backBtnAction" class="btn btn-secondary float-left">戻る</button> -->
                    <div id="townName"  class="textBox font-weight-bold w-25">浜松市</div>
                    <select name="" id="" class="btn btn-outline-dark ml-2 w-25">
                        <option value="">住民</option>
                        <option value="">大規模事業者</option>
                    </select>

                    <select name="" id="" class="btn btn-outline-dark ml-2 w-25">
                        <option class="p-3" value="">時間帯</option>
                        <option class="p-3" value="">9:00~16:00</option>
                        <option class="p-3" value="">15:00~23:00</option>
                        <option class="p-3" value="">22:00~5:00</option>
                    </select>
                </div>
                <div class="col-9" style>
                    <div id="map"></div>
                </div>
                <div class="col-3">
                    <!-- <div id="findbox"></div> -->
                    <div style="width:100%; height:100%;">
                        <br/>
                        <button id="chartShow" type="button" class="btn btn-outline-dark float-left w-100">人口構成</button>
                        <button id="nutrientsShowBtn" type="button" class="btn btn-outline-dark float-left w-100 mt-3">必要栄養素</button>
                        <button type="button" class="btn btn-outline-dark float-left w-100 mt-3">必要需要数1</button>
                        <button type="button" class="btn btn-outline-dark float-left w-100 mt-3">必要需要数2</button>
                        <button type="button" class="btn btn-outline-dark float-left w-100 mt-3">必要需要数3</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="chartDisplay" class="container mt-1 m-auto" style="display:none;">
            <div class="row container mt-1  m-auto border">
                <div class="col-12 p-3">
                    <div class="textBox font-weight-bold">人口構成</div>
                    <div id="townNameChart" class="textBox font-weight-bold ml-2">浜松市</div>
                </div> 
                <div class="col-12">
                    <canvas id="myChart" width="" height=""></canvas>
                </div>
                <div class="col-12">
                    <br/><button id="backBtnDisplay" class="btn btn-secondary float-right">初期画面に戻る</button>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                </div>
            </div>   
        </div>
        <br/>
        
        <div id="nutrientsDisplay" class="container m-auto" style="display:none">
            <div class="row container mt-1 m-auto border">
                <div class="col-12 p-3">
                    <div class="textBox font-weight-bold">必要栄養素</div>
                    <div id="townNameNutrients" class="textBox font-weight-bold ml-2">浜松市</div>
                    <select name="" id="SpecialAgeName" class="btn btn-outline-dark ml-2">
                        <option value="乳幼児">乳幼児</option>
                        <option value="小児">小児</option>
                        <option value="一般成人" selected="true">一般成人</option>
                        <option value="特別老人">特別老人</option>
                    </select>
                    <select name="" id="dayWeekMonth" class="btn btn-outline-dark ml-2">
                        <option value="day">日</option>
                        <option value="week">週</option>
                        <option value="month">月</option>
                    </select>
                    <select name="" id="subOption" class="btn btn-outline-dark ml-2">
                    </select>
                    <button id="backBtnNutrients" class="btn btn-secondary float-right">初期画面に戻る</button>
                </div> 
                <div class="col-12">
                    <div id="tableBody">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->Html->script('main.js') ?> 
    <?= $this->Html->script('leaflet-search.js') ?>
    <?= $this->Html->script('cityBorderJson.js') ?>
    <?= $this->Html->script('bigZonePointJson.js') ?>
    <?= $this->Html->script('smallZonePointJson.js') ?>


<script>
    /** チャートを描くする機能です。*/
    var bigData = null;
    var myChart;
    function chart(data)  {
        var countData = [];
        for(const property in data) {
            countData.push(data[property]);
            // let obj = data[property][0];


            // for (var key in obj) {
            //     if (obj.hasOwnProperty(key)) {
            //         console.log(key + " -> " + obj[key]);
            //         e
            //     }
            // }
        }

        if(myChart!=null)  {
            myChart.destroy();
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['0-10歳', '11-20歳', '21-30歳', '31-40歳', '41-50歳', '51-60歳', '61-70歳', '71-80歳', '81-90歳', '91-100歳', '100-110歳' , '111-120歳'], //12 row
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
                        'rgba(255, 159, 64, 1)'
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
                            // document.getElementById("chosedPlaceName").innerText = "浜松市 - "+geoJsonPoint.properties.Name;
                            this.bindPopup("<div class='p-1'><h4>"+ geoJsonPoint.properties.Name +"</h4></div>");
                            setTownName("浜松市"+geoJsonPoint.properties.Name);
                            chart({
                                '0-10': parseInt(data['data_0_10'][0]['Male']) + parseInt(data['data_0_10'][0]['Female']),
                                '11-20': parseInt(data['data_11_20'][0]['Male']) + parseInt(data['data_11_20'][0]['Female']), 
                                '21-30': parseInt(data['data_21_30'][0]['Male']) + parseInt(data['data_21_30'][0]['Female']),
                                '31-40': parseInt(data['data_31_40'][0]['Male']) + parseInt(data['data_31_40'][0]['Female']),
                                '41-50': parseInt(data['data_41_50'][0]['Male']) + parseInt(data['data_41_50'][0]['Female']), 
                                '51-60': parseInt(data['data_51_60'][0]['Male']) + parseInt(data['data_51_60'][0]['Female']),
                                '61-70': parseInt(data['data_61_70'][0]['Male']) + parseInt(data['data_61_70'][0]['Female']),
                                '71-80': parseInt(data['data_71_80'][0]['Male']) + parseInt(data['data_71_80'][0]['Female']),
                                '81-90': parseInt(data['data_81_90'][0]['Male']) + parseInt(data['data_81_90'][0]['Female']),
                                '91-100': parseInt(data['data_91_100'][0]['Male']) + parseInt(data['data_91_100'][0]['Female']),
                                '101-110': parseInt(data['data_101_110'][0]['Male']) + parseInt(data['data_101_110'][0]['Female']),
                                '111-120': parseInt(data['data_111_120'][0]['Male']) + parseInt(data['data_111_120'][0]['Female'])
                            });
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
                                    "<h6>"+ data['data_0_10'][0]['市'] + data['data_0_10'][0]['区'] + data['data_0_10'][0]['町'] +"<br/>"+
                                        "郵便番号 : "+ data['data_0_10'][0]['郵便番号'] +
                                    "</h6>"+
                                "</div>"
                            );

                            setTownName(data['data_0_10'][0]['市'] + data['data_0_10'][0]['区'] + data['data_0_10'][0]['町']);
                            chart({
                                '0-10': parseInt(data['data_0_10'][0]['Male']) + parseInt(data['data_0_10'][0]['Female']),
                                '11-20': parseInt(data['data_11_20'][0]['Male']) + parseInt(data['data_11_20'][0]['Female']), 
                                '21-30': parseInt(data['data_21_30'][0]['Male']) + parseInt(data['data_21_30'][0]['Female']),
                                '31-40': parseInt(data['data_31_40'][0]['Male']) + parseInt(data['data_31_40'][0]['Female']),
                                '41-50': parseInt(data['data_41_50'][0]['Male']) + parseInt(data['data_41_50'][0]['Female']), 
                                '51-60': parseInt(data['data_51_60'][0]['Male']) + parseInt(data['data_51_60'][0]['Female']),
                                '61-70': parseInt(data['data_61_70'][0]['Male']) + parseInt(data['data_61_70'][0]['Female']),
                                '71-80': parseInt(data['data_71_80'][0]['Male']) + parseInt(data['data_71_80'][0]['Female']),
                                '81-90': parseInt(data['data_81_90'][0]['Male']) + parseInt(data['data_81_90'][0]['Female']),
                                '91-100': parseInt(data['data_91_100'][0]['Male']) + parseInt(data['data_91_100'][0]['Female']),
                                '101-110': parseInt(data['data_101_110'][0]['Male']) + parseInt(data['data_101_110'][0]['Female']),
                                '111-120': parseInt(data['data_111_120'][0]['Male']) + parseInt(data['data_111_120'][0]['Female'])
                            });
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

            /** chartShow Btn start */
                document.querySelector("#chartShow").addEventListener("click", function() {
                    $("#chartDisplay").show();
                    $("#basicDisplay").hide();
                });
            /** chartShow Btn end */

            /** backBtnNutrients start */
                document.querySelector("#backBtnNutrients").addEventListener("click", function() {
                    $("#nutrientsDisplay").hide();
                    $("#basicDisplay").show();
                });
            /** backBtnNutrients end */

            /** nutrientsShowBtn start */
                document.querySelector("#nutrientsShowBtn").addEventListener("click", function() {
                    $("#basicDisplay").hide();
                    $("#nutrientsDisplay").show();
                });
            /** nutrientsShowBtn end */

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
                            subOption.append("<option>"+ i +"日</option>")
                        }
                    }

                    if(optionVal === "week") {
                        for(let i=1; i<=20; i++) {
                            subOption.append("<option>"+ i +"週</option>")
                        }
                    }

                    if(optionVal === "month") {
                        for(let i=1; i<=12; i++) {
                            subOption.append("<option>"+ i +"か月</option>")
                        }
                    }
                    getNutrientsData(); 
                }).change();

                $("#SpecialAgeName").change(function()  {
                    getNutrientsData();
                });

                $("#subOption").change(function()  {
                    getNutrientsData();
                });
                

            /** Nutrients combo change events end */
        //*** event end ***/
 
    };

    /** Data load start */
        postData(ownServerUrl, { district: "init", type: "big" }).then(data => {
            // chart(data);
            chart({
                '0-10': parseInt(data['data_0_10'][0]['Male']) + parseInt(data['data_0_10'][0]['Female']),
                '11-20': parseInt(data['data_11_20'][0]['Male']) + parseInt(data['data_11_20'][0]['Female']), 
                '21-30': parseInt(data['data_21_30'][0]['Male']) + parseInt(data['data_21_30'][0]['Female']),
                '31-40': parseInt(data['data_31_40'][0]['Male']) + parseInt(data['data_31_40'][0]['Female']),
                '41-50': parseInt(data['data_41_50'][0]['Male']) + parseInt(data['data_41_50'][0]['Female']), 
                '51-60': parseInt(data['data_51_60'][0]['Male']) + parseInt(data['data_51_60'][0]['Female']),
                '61-70': parseInt(data['data_61_70'][0]['Male']) + parseInt(data['data_61_70'][0]['Female']),
                '71-80': parseInt(data['data_71_80'][0]['Male']) + parseInt(data['data_71_80'][0]['Female']),
                '81-90': parseInt(data['data_81_90'][0]['Male']) + parseInt(data['data_81_90'][0]['Female']),
                '91-100': parseInt(data['data_91_100'][0]['Male']) + parseInt(data['data_91_100'][0]['Female']),
                '101-110': parseInt(data['data_101_110'][0]['Male']) + parseInt(data['data_101_110'][0]['Female']),
                '111-120': parseInt(data['data_111_120'][0]['Male']) + parseInt(data['data_111_120'][0]['Female'])
            });
        });
    /** Data load end */

    //*** functions start ***/
        /** Display name change fn start */
            function setTownName(name)  {
                document.getElementById('townName').innerHTML = name;
                document.getElementById('townNameChart').innerHTML = name;
            }  
        /** Display name change fn end */

        /** getNutrients data loading fn start  */
            function getNutrientsData()  {
                postData("http://localhost/webOri/users/getNutrients.json", 
                {
                    SpecialAgeName: $("#SpecialAgeName option:selected").val(),
                    dayWeekMonth: $("#dayWeekMonth option:selected").val(),
                    subOption:  $("#subOption option:selected").text(),
                }).then(data => {
                    // console.log(data);
                    $("#tableBody").html(data.nutrients);
                });
            }
        /** getNutrients data loading fn  end*/
    //*** functions end ***/

    map();
</script>
