
    // Database name
    const ownDB = "FKC"; //FKC : ORI 
    const ownServerUrl = "http://192.168.120.3/webOri/users/getdata.json";

    // const ownDB = "ORI";  
    const oriUrl = "https://ori-project.smartcity-open-platform.jp/orion/v2.0/entities";

    //ORI Access token 
    const Authorization = "Bearer 98a2b6a2-beaa-39bc-83f9-c5a6c0873b41";

    // navbar space constant value
    document.querySelector("body").setAttribute("style", "padding-top:52px;");
    
    //* Example POST method implementation:
    async function postData(url = '', dataObj = {})  {
        // Default options are marked with *
        console.log(ownDB+" DB pending...");

        var response = null;
        if(ownDB === "FKC")  {
            response = axios({
                url,
                method: 'GET',
                params  : dataObj
            })
            .then(response =>response.data)
            .catch(error => console.log(error));
            return response;
        }

        if(ownDB === "ORI")  {
            response = axios({
                url,
                method: 'GET',
                headers: {
                    'Authorization' : Authorization,
                    'Accept' : 'application/json',
                    'Fiware-Service' : 'ori005',
                    'Fiware-ServicePath' : '/'
                },
                data: null
            })
            .then(response =>response.data)
            .catch(error => console.log(error));
            return response;
        }
    }

    async function dataInsert(url = '', data = {}) {
        return await axios({
            url,
            method: 'POST',
            data  : data,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': Authorization,
                'Accept': 'application/json',
                'Fiware-Service': 'ori005',
                'Fiware-ServicePath': '/'
            }
        })
        .then(response =>response.data)
        .catch(error => console.log(error));
    }

    async function dataSelect(url = '', data = {}) {
        return await axios({
            url,
            method: 'GET',
            params  : data,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': Authorization,
                'Accept': 'application/json',
                'Fiware-Service': 'ori005',
                'Fiware-ServicePath': '/'
            }
        })
        .then(response =>response.data)
        .catch(error => console.log(error));
    }

    // dataInsert(oriUrl, { 
    //     "id": "PF_111113",
    //     "type": "testData" 
    // }).then(data => { console.log(data); });

    // dataSelect(oriUrl)
    // .then(data => {
    //     console.log(data); // JSON data parsed by `data.json()` call
    // });

    //ene shuud zvgeer ajilnna.
    // postData("http://192.168.120.3/webOri/users/bigdata.json", {  }, "GET")
    // .then(data => {
    //     console.log(data.Items);
    //     data.Items.forEach(function(item)  {
    //         var dataRow = {
    //             "type": "populationDataTest1", 
    //             "id": `PDTest1_${item['id']}`, 
    //             "prefecture": {
    //                 "type": "varchar",
    //                 "value": item["県名"],
    //             },
    //             "city": {
    //                 "type": "varchar",
    //                 "value": item["市"]
    //             },
    //             "ward": {
    //                 "type": "varchar",
    //                 "value": item["区"]
    //             },
    //             "town": {
    //                 "type": "varchar",
    //                 "value": item["町"]
    //             },
    //             "chome": {
    //                 "type": "varchar",
    //                 "value": item["丁目"]
    //             },
    //             "postcode": {
    //                 "type": "varchar",
    //                 "value": item["郵便番号"]
    //             },
    //             "age": {
    //                 "type": "int",
    //                 "value": item["年齢"]
    //             },
    //             "male": {
    //                 "type": "varchar",
    //                 "value": item["男"]
    //             },
    //             "female": {
    //                 "type": "varchar",
    //                 "value": item["female"]
    //             },
    //             "shikibetsu": {
    //                 "type": "int",
    //                 "value": item["識別"]
    //             },
    //             "location": {
    //                 "type": "geo:point",
    //                 "value": item["longitude"]+", "+ item["latitude"]
    //             }
    //         };

    //         console.log(dataRow);
    //         dataInsert(oriUrl, dataRow).then(data => { console.log(data); });
    //     });        
    // });
