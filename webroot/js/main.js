
    // Database name
    const ownDB = "FKC"; //FKC : ORI 
    const url = "http://localhost/webOri/users/getData.json";

    // const ownDB = "ORI";  
    // const url = "https://ori-project.smartcity-open-platform.jp/orion/v2.0/entities";

    //ORI Access token 
    const Authorization = "Bearer ba6f909a-e160-3a5d-96f0-09190dd72e5b";

    // navbar space constant value
    document.querySelector("body").setAttribute("style", "padding-top:52px;");



    //* Example POST method implementation:
    async function postData(url = '', data = {}, method = "POST")  {
        // Default options are marked with *
        console.log(ownDB+" DB pending...");

        var response = null;
        if(ownDB === "FKC")  {
            response = await fetch(url)
            .then(response => response.json())
            .then(data => data)
            .catch((error) => { console.error('Error:', error); });
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

    postData(url, { answer: 42 }, "GET")
    .then(data => {
        console.log(data); // JSON data parsed by `data.json()` call
    });
