$("#nutrientsShowBtn").click(function()  {

    postData(ownServerUrl, {  // tur zuur gal untraaw
        kenName: selectedKenName,
        shiName: selectedShiName,  
        kuName: selectedKuName,
        townName: selectedTownName, 
        type: type,
        timeOption: timeOption,
        liveAndWork: liveAndWork
    })
    .then(data => {
        ChartObj.bodyDraw(data);
            
        $("#basicDisplay").hide();
        $("#nutrientsDisplay").show();
        $("#townNameNutrients").text(selectedPlaceFullName);
        $("#dayWeekMonth").change();
    });

});

$("#backBtnNutrients").click(function()  {
    $("#nutrientsDisplay").hide();
    $("#basicDisplay").show();
});

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

function getNutrientsData()  {  // getNutrients data loading fn start


    postData(SERVER_+"/webOri/users/getNutrients.json", 
    {
        SpecialAgeName: $("#SpecialAgeName option:selected").val(),
        dayWeekMonth: $("#dayWeekMonth option:selected").val(),
        subOption: $("#subOption option:selected").text(),
        jinkoInfo: JSON.stringify(jinkoInfo),
    }).then(data => {
        $("#tableBody").html(data.nutrients);
    });
}
