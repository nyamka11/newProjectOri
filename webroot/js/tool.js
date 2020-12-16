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
        type: 'bar',
        data: {
            labels: ['0-10', '11-20', '21-30', '31-40', '41-50', '51-60', '61-70', '71-80', '81-90', '91-100', '100-110' , '111-120'], //12 row
            datasets: [{
                label: 'Nenrei',
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
}

function map()  {
    var map = new L.Map('map', {zoom: 10, center: new L.latLng(34.79181436843145, 137.7239227294922) });	//set center from first location
    map.addLayer(new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));	//base layer
    var prevLayerClicked = null;

    geojson = L.geoJson(jsonData, {
        style: function (feature) {
            return {
                fillColor: "#cacaca",
                weight: 2,
                opacity: 0.5,
                color: 'black',
                fillOpacity: 0.4
            };
        },
        pointToLayer: function(geoJsonPoint, latlng) {
            return L.marker(latlng).bindPopup(
                "<div class='p-2'><h3>"+geoJsonPoint.properties.Name+"</h3></div>"+
                "<div>"+geoJsonPoint.properties.Name+"</div>"+
                "<div>"+geoJsonPoint.properties.Name+"</div>"
            );
        },
        onEachFeature: function(feature, layer)  {
            console.log(layer);
            layer.on({
                click: function(e)  {
                    chart(feature.properties.nenrei);
                    map.fitBounds(e.target.getBounds());
                }
            });
        }
    })
    .addTo(map);

    //jsonoor ni dawtaj bgaa
    // jsonData.features.forEach(element => {
    //     if(element.geometry.type === 'Point')  {
    //         console.log(element.geometry);
    //         var coord = element.geometry.coordinates;
    //         var marker = L.marker([coord[1],coord[0]], {
    //             elevation: 260.0,
    //             title: "Transamerica Pyramid"
    //         }).on('click', function(e)  {
    //             chart(element.properties.nenrei);
    //         }).addTo(map);

    //         marker.bindPopup("<b><h4>"+element.properties.Name+"</h4></b>");
    //     }
    // });


    // var markersLayer = new L.LayerGroup();	//layer contain searched elements
    // map.addLayer(markersLayer);

    // map.addControl( new L.Control.Search({
    //     container: 'findbox',
    //     layer: markersLayer,
    //     initial: false,
    //     collapsed: false
    // }));

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
    return;
    data.data.forEach(async item => {
        var itemData = {
            "type": "hamamatsuData", 
            "id": `IdAQQ_${item['id']}`, 
            "Kenmei" : {
                "type" : "varchar",
                "value" : item["県名"]
            },
            "Shi" : {
                "type" : "varchar",
                "value" : item["市"]
            },
            "Machi" : {
                "type" : "varchar",
                "value" : item["町"]
            },
            "Chome" : {
                "type" : "varchar",
                "value" : item["丁目"]
            },
            "PostCode" : {
                "type" : "double",
                "value" : item["郵便番号"]
            },
            "Nenrei" : {
                "type" : "double",
                "value" : item["年齢"]
            },
            "Otoko" : {
                "type" : "double",
                "value" : item["男"]
            },
            "Onna" : {
                "type" : "double",
                "value" : item["男"]
            },
            "Shikibetsu" : {
                "type" : "double",
                "value" : item["識別"]
            }
        };


        // var itemData = {
        //     "id": `oriData${item['id']}`, 
        //     "type": "Feature", 
        //     "Address": {
        //         "Kenmei":  item["県名"],
        //         "Shi":  item["市"],
        //         "Machi":  item["町"],
        //         "Chome":  item["丁目"]
        //     },
        //     "postCode": item["郵便番号"],
        //     "properties": {
        //         "Nenrei": item["年齢"],
        //         "Otoko":  item["男"],
        //         "Onna": item["男"],
        //         "Shikibetsu":  item["識別"]
        //     }
        // };
        // console.log(JSON.stringify(itemData));
        // return;

        await fetch('https://ori-project.smartcity-open-platform.jp/orion/v2.0/entities', {
            method: 'POST', // or 'PUT'
            headers: {
                'Content-Type': 'application/json',
                'Authorization' : 'Bearer 610323c9-bea4-3ef5-956f-4bc796d913be',
                'Accept' : 'application/json',
                'Fiware-Service' : 'ori005',
                'Fiware-ServicePath' : '/'
            },
            body: JSON.stringify(itemData),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
            return false;
        });
    });

    return;

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
 
    map();

    console.log(nakaku);
    console.log(higashiku);
    console.log(nishiku);
    console.log(minamiku);
    console.log(kitaku);
    console.log(hamakitaku);
    console.log(tenryuoku);
 });
