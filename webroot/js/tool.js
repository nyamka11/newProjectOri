
/** ORi serverluu nutrientsdata - aag amjilttai ilgeesen tool
    postData(SERVER_+"/webOri/users/nutrientsdata.json", { answer: 42 }, "GET")
    .then(data => {
        console.log(data);
        data.Items.forEach(function(item)  {
            var dataRow = {};
            for(var propt in item) {
                if(propt === "id")  {
                    dataRow["id"] = "ntd_"+item["id"];
                }
                else dataRow[propt] = {
                    "type": "varchar",
                    "value": item[propt]
                }
                dataRow["type"] = "nutrientsData";
            }

            console.log(dataRow);
            dataInsert(oriUrl, dataRow).then(data => { console.log(data); });
        });        
    });
*/

// UPDATE `population` p INNER JOIN `xypos` xy ON `p`.machi = `xy`.`COL 2` SET `p`.latitude = xy.`COL 4`, `p`.longitude = xy.`COL 5` WHERE 1=1

async function postDataFiware(url = '', data = {})  {
    const response = await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer 0ad5c4bd-341f-33bc-a1b0-d3eed6372611',
            'Accept': 'application/json',
            'Fiware-Service': 'ori005',
            'Fiware-ServicePath': '/'
        },
        body: JSON.stringify(data) // body data type must match "Content-Type" header
    });
    return response.json(); // parses JSON response into native JavaScript objects
}

async function postDataLocal(url = '', data = {}) {
    console.log("big data loading ...");
    const response = await fetch(url, {
        method: 'GET', // *GET, POST, PUT, DELETE, etc.
        body: JSON.stringify(data)
    });
    return response.json(); // parses JSON response into native JavaScript objects
}


    /*
    async function dataDelete(url = '', data = {})  {

        return await axios({
            url,
            method: 'DELETE',
            data  : data,
            headers: headers
        })
        .then(response => response.data)
        .catch(error => console.log(error));

        return await axios.delete(url, {
            headers: headers,
            data: data
        });
    }

    dataDelete(oriUrl, { "id": 1 }); */


    
    //ene shuud zvgeer ajilnna.
    /*postData(SERVER_+"/webOri/users/bigdata.json", {  }, "GET")
    .then(data => {
        // console.log(data.Items);
        data.Items.forEach(function(item)  {
            var dataRow = {
                "type": "populationData", 
                "id": `PD_ID${item['id']}`, 
                "prefecture": {
                    "type": "varchar",
                    "value": item["県名"],
                },
                "city": {
                    "type": "varchar",
                    "value": item["市"]
                },
                "ward": {
                    "type": "varchar",
                    "value": item["区"]
                },
                "town": {
                    "type": "varchar",
                    "value": item["町"]
                },
                "chome": {
                    "type": "varchar",
                    "value": item["丁目"]
                },
                "postcode": {
                    "type": "varchar",
                    "value": item["郵便番号"]
                },
                "age": {
                    "type": "int",
                    "value": item["年齢"]
                },
                "male": {
                    "type": "varchar",
                    "value": item["男"]
                },
                "female": {
                    "type": "varchar",
                    "value": item["女"]
                },
                "shikibetsu": {
                    "type": "int",
                    "value": item["識別"]
                },
                "location": {
                    "type": "geo:point",
                    "value": item["longitude"]+", "+ item["latitude"]
                }
            };

            console.log(dataRow);
            dataInsert(oriUrl, dataRow).then(data => { console.log(data); });
        });        
    });*/

 // "id": `PF_${item['id']}`, 
// "type": "populationInfo", 
// "address": {
//     "prefecture": item["県名"],
//     "city": item["市"],
//     "ward": item["区"],
//     "town": item["町"],
//     "Chome": item["丁目"]
// },
// "postcode": item["郵便番号"],
// "age": item["年齢"],
// "male": item["男"],
// "female": item["女"],
// "Shikibetsu": item["識別"]