
    // Database name
    var SERVER_ ='http://192.168.120.3';
    const ownDB = "FKC"; //FKC : ORI 
    const ownServerUrl = SERVER_+"/webOri/users/getdata.json";

    // const ownDB = "ORI";  
    const oriUrl = "https://ori-project.smartcity-open-platform.jp/orion/v2.0/entities";

    //ORI Access token 
    const token = "1db3e157-1b75-32dd-bc98-e3e32ecbfe7e";
    const Authorization = "Bearer " + token;
    const headers = {
        'Content-Type': 'application/json',
        'Authorization': Authorization,
        'Accept': 'application/json',
        'Fiware-Service': 'ori005',
        'Fiware-ServicePath': '/'
    };

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
            headers: headers
        })
        .then(response => response.data)
        .catch(error => console.log(error));
    }

    async function dataSelect(url = '', params = {})  {
        return await axios({
            url,
            method: 'GET',
            params  : params,
            headers: headers
        })
        .then(response => response.data)
        .catch(error => console.log(error));
    }

    async function dataDelete(url = '', params = {})  {
        return await axios({
            url,
            method: 'DELETE',
            params  : params,
            headers: headers
        })
        .then(response => response.data)
        .catch(error => console.log(error));
    }

    // dataDelete(oriUrl, {
    //     "id": "1",
    //     "type": "typeText" 
    // }).then(data => { console.log(data); });
    

    // dataInsert(oriUrl, {
    //     "id": "PF_111113",
    //     "type": "testData" 
    // }).then(data => { console.log(data); });
    
    // dataSelect(oriUrl)
    // .then(data => {
    //     console.log(data); // JSON data parsed by `data.json()` call
    // });
