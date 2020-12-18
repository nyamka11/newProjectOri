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
   </style>

    <div class="row mt-1 container m-auto border">
      <div class="col-8" style>
            <div class="row">
                <div class="col-12 p-3">
                    <button type="button" class="btn btn-outline-dark float-left w-25">浜松市</button>
                    <select name="" id="" class="btn btn-outline-dark ml-2 w-25">
                        <option value="">asdfasdf</option>
                        <option value="">asdfasdf</option>
                        <option value="">asdfasdf</option>
                    </select>

                    <select name="" id="" class="btn btn-outline-dark ml-2 w-25">
                        <option class="p-3" value="">asdfasdf</option>
                        <option class="p-3" value="">asdfasdf</option>
                        <option class="p-3" value="">asdfasdf</option>
                    </select>
                    <button id="backBtn" class="btn btn-secondary ml-2">戻る</button>
                </div>
            </div>
        <div id="map"></div>
      </div>
      <div class="col-4">
        <!-- <div id="findbox"></div> -->
        <div style="width:100%; height:100%; border:1px solid red;">
            <br/>
            
            <h3 class="text-center" id="chosedPlaceName">浜松市</h3>
            <!-- <h5 class="text-center">年齢</h5> -->
            <canvas id="myChart" width="" height=""></canvas>
            
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
        }

        if(myChart!=null)  {
            myChart.destroy();
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['0-10歳', '11-20歳', '21-30歳', '31-40歳', '41-50歳', '51-60歳', '61-70歳', '71-80歳', '81-90歳', '91-100歳', '100-110歳' , '111-120歳'], //12 row
                datasets: [{
                    label: '年齢',
                    data: countData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
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
                        document.getElementById("chosedPlaceName").innerText = "浜松市 - "+geoJsonPoint.properties.Name;
                        this.bindPopup("<div class='p-1'><h4>"+ geoJsonPoint.properties.Name +"</h4></div>");

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
        var smallZonePoint = L.geoJson(smallZonePointJson, {
            pointToLayer: function (geoJsonPoint, latlng) {
                return L.marker(latlng).on('click', function(e)  {
                    var own = this;
                    map.setView(latlng, 16);
                    postData(ownServerUrl, { town: geoJsonPoint.properties.name, type: "small" })
                    .then(data => {
                        console.log(data);

                        document.getElementById("chosedPlaceName").innerText = "浜松市 - "+geoJsonPoint.properties.name;
                        this.bindPopup(
                            "<div class='p-1'>"+
                                "<h6>"+ data['data_0_10'][0]['市'] + data['data_0_10'][0]['区'] + data['data_0_10'][0]['町'] +"<br/>"+
                                    "郵便番号 : "+ data['data_0_10'][0]['郵便番号'] +
                                "</h6>"+
                            "</div>"
                        );

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

    /** zoom start */
        map.on('zoomend', function()  {
            var currentZoom = map.getZoom();
            console.log(currentZoom);
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

    /** backBtn start */    
        document.querySelector("#backBtn").addEventListener("click", function() {
            map.setView([34.75181436841190, 137.7239227294922], 12);
        });
    /** backBtn end */            
    };

    /** Data load start */
        postData(ownServerUrl, { district: "init", type: "big" }).then(data => {
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

    map();
</script>
