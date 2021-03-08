$("#requiredDemandDisplayShowBtn").click(function()  {
    $("#basicDisplay").hide();
    $("#requiredDemandDisplay").show();
    $("#menuNameCombo").change();
    $("#dayWeekMonthReq").change();
});

$("#backBtnRequiredDemand").click(function()  {
    $("#basicDisplay").show();
    $("#requiredDemandDisplay").hide();
});

$("#menuNameCombo").change(function()  {
    selectedMenuName = $("option:selected", this).val();
    getMenu();
});

//header---
$("#SpecialAgeNameReq").change(function()  {
    selectedSpecialAgeName = $("option:selected", this).val(), 
    getNutrientsReqData();
});

$("#dayWeekMonthReq").change(function()  {
    let subOption = $("#subOptionReq").html("");
    let optionVal = $(this).val();
    subOptionDraw(subOption, optionVal);

    selectedDayWeekMonth = $("option:selected", this).val();
    $("#subOptionReq").change();

});

$("#subOptionReq").change(function()  {
    selectedSubOption = $("option:selected", this).text();
    getMenu();
    // getNutrientsReqData();
});

function getMenu() {
    $("#townNameNutrientsReq").text(selectedPlaceFullName);
    postData(SERVER_+"/webOri/users/getMenu.json", {
        menuName: selectedMenuName,
        dayWeekMonth: selectedDayWeekMonth,
        subOption: selectedSubOption,
    }).then(dataMenu => {
        $("#foodNameList").html("");
        dataMenu.getMenu.forEach(element => {
                $("#foodNameList").append(
                '<li class="list-group-item d-flex justify-content-between align-items-center" style="font-size:14px;">'+
                    (element.foodName.replace(/\s/g, '')) +
                    '<span class="badge badge-primary">'+ (element.oneServingCoefficients)+ "食、</td><td>"+ (element.box)+'箱</span>'+
                '</li>'
            );
        });

        getNutrientsReqData();
    });
}

function getNutrientsReqData()  {  // getNutrientsReqData data loading fn start 
    var param = {
        menuName: $("#menuNameCombo option:selected").text(),
        SpecialAgeName: selectedSpecialAgeName,
        dayWeekMonth: selectedDayWeekMonth,
        subOption: selectedSubOption,
        jinkoInfo: JSON.stringify(jinkoInfo),
    };

    postData(SERVER_+"/webOri/users/getReqNutrientList.json", param).then(data => {
        $("#tableBodyReq1").html("").html(data.htmlTable);
    });
}