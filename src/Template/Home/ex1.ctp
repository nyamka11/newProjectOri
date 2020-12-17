<?= $this->element('header') ?> 



<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
crossorigin=""></script>


<!-- Load Esri Leaflet from CDN -->
<script src="https://unpkg.com/esri-leaflet@2.4.1/dist/esri-leaflet.js"
integrity="sha512-xY2smLIHKirD03vHKDJ2u4pqeHA7OQZZ27EjtqmuhDguxiUvdsOuXMwkg16PQrm9cgTmXtoxA6kwr8KBy3cdcw=="
crossorigin=""></script>

<!-- Load Esri Leaflet Geocoder from CDN -->
<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.css"
integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
crossorigin="">
<script src="https://unpkg.com/esri-leaflet-geocoder@2.3.3/dist/esri-leaflet-geocoder.js"
integrity="sha512-HrFUyCEtIpxZloTgEKKMq4RFYhxjJkCiF5sDxuAokklOeZ68U2NPfh4MFtyIVWlsKtVbK5GD2/JzFyAfvT5ejA=="
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
    <div id="findbox"></div>
    <div style="width:100%; height:50%; border:1px solid red;">
        <canvas id="myChart" width="" height=""></canvas>
    </div>
    </div>
</div>
<?= $this->Html->script('leaflet-search.js') ?>

<script>

    
        var map = L.map('map').setView([34.867927560000055, 138.30806817000007], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var searchControl = L.esri.Geocoding.geosearch({
            collapseAfterResult: false,
            expanded: true,
            placeholder: "find address"
        }).addTo(map);
        var results = L.layerGroup().addTo(map);

        searchControl.on('results', function (data)  {
            console.log(data);
            results.clearLayers();
            for (var i = data.results.length - 1; i >= 0; i--)  {
                results.addLayer(L.marker(data.results[i].latlng).bindPopup('A pretty CSS3 popup.<br> Easily customizable.').openPopup());

            }
        });
    

    function chart()  {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
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

    async function postData(url = '') {
        console.log("pending...");
        const response = await fetch(url)
        .then(response => response.json())
        .then(data => data1 = data);
        return response;
    }

    postData('http://localhost/webOri/users/data.json')
    .then(data =>  {
       
        chart();
     });

</script>
