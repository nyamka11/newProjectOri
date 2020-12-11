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

    <div class="row mt-1">
      <div class="col-8" style>
        <div id="map"></div>
      </div>
      <div class="col-4">
        <!-- <div id="findbox"></div> -->
        <div style="width:100%; height:50%; border:1px solid red;">
          <canvas id="myChart" width="" height=""></canvas>
        </div>
      </div>
    </div>
    
   <?= $this->Html->script('leaflet-search.js') ?>
   <?= $this->Html->script('hamamatsuJson.js') ?>

   <script>
    function chart()  {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['0-10', '11-20', '21-30', '31-40', '41-50', '51-60', '61-70', '71-80', '81-90', '91-100', '100-110' , '111-120'], //12 row
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3,12, 19, 3, 5, 2, 3],
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
    }

    console.log(jsonData);

    function map()  {
        var map = new L.Map('map', {zoom: 10, center: new L.latLng(34.79181436843145, 137.7239227294922) });	//set center from first location
        map.addLayer(new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));	//base layer

        var markersLayer = new L.LayerGroup();	//layer contain searched elements
        map.addLayer(markersLayer);

        // map.addControl( new L.Control.Search({
        //     container: 'findbox',
        //     layer: markersLayer,
        //     initial: false,
        //     collapsed: false
        // }));

        geojson = L.geoJson(jsonData, {
            style: function (feature) {
                return {
                    fillColor: "#cacaca",
                    weight: 2,
                    opacity: 0.5,
                    color: 'black',
                    // dashArray: '3',
                    fillOpacity: 0.4
                };
            }
        })
        .bindPopup(function (layer) {
            console.log(layer.feature);
            return "<div><b>"+ layer.feature.properties.Level2_Name +"</b></div>";
        })
        .addTo(map);
     
        // var title = data[i].title,	//value searched
        //     loc = data[i].loc,		//position found
        //     marker = new L.Marker(new L.latLng(loc), {title: title} );//se property searched
        //     marker.bindPopup('title: '+ title );
        //     markersLayer.addLayer(marker);
        // }
    }

    async function postData(url = '') {
        console.log("pending...");
        const response = await fetch(url)
        .then(response => response.json())
        .then(data => data1 = data);
        return response;
    }


    var nakaku=[];
    var higashiku=[];
    var nishiku=[];
    var minamiku=[];
    var kitaku=[];
    var hamakitaku=[];
    var tenryuoku=[];

    postData('http://localhost/webOri/users/data.json')
    .then(data =>  {
        let obj = data.data;
        for (const [key, value] of Object.entries(obj)) {
            if(obj[key]['区'] === "中区") nakaku.push(obj[key]);
            if(obj[key]['区'] === "東区") higashiku.push(obj[key]);
            if(obj[key]['区'] === "西区") nishiku.push(obj[key]);
            if(obj[key]['区'] === "南区") minamiku.push(obj[key]);
            if(obj[key]['区'] === "北区") kitaku.push(obj[key]);
            if(obj[key]['区'] === "浜北区") hamakitaku.push(obj[key]);
            if(obj[key]['区'] === "天竜区") tenryuoku.push(obj[key]);

        }
        chart();
        map();

        console.log(nakaku);
        console.log(higashiku);
        console.log(nishiku);
        console.log(minamiku);
        console.log(kitaku);
        console.log(hamakitaku);
        console.log(tenryuoku);
     });

   </script>
