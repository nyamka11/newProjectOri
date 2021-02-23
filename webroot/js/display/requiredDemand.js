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
                "<div class='row' style='border:1px solid #cacaca; background-color:white; padding:5px; margin:2px;'>"+
                    "<div class='col-8' style='font-size:14px;'>"+ (element.foodName.replace(/\s/g, '')) +"</div>"+
                    "<div class='col-4'><b>"+ (element.oneServingCoefficients)+ "食</b></div>"+
                "</div>"
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