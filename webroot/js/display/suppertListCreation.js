
var SCD_dataTable =null;
var SCD_CD_listTable =null;

$("#SDSD_ListCreationBtn").click(function()  {
    $("#supportDestinationSearchDisplay").hide();
    $("#supportListCreationDisplay").show();

    var rowListHtml = null;
    postData(SERVER_+"/webOri/users/productFree.json").then(data => {
        $.each(data.Items, function()  {
            rowListHtml +=
            '<tr>'+
                '<th scope="row" width="2%">'+
                    '<input id="checkBox" type="checkbox" rowId='+ this.id +' />'+
                '</th>'+
                '<td width="25%">'+ this.good_name +'</td>'+
                '<td width="10%">'+ this.save_date +'</td>'+
                '<td width="10%">'+ this.storage_area +'</td>'+
                '<td width="5%">'+ this.available_count +'</td>'+
                '<td width="5%">'+ this.packing +'</td>'+
                '<td width="5%">'+ this.in_the_package_count +'</td>'+
                '<td width="5%">'+ this.unit_in_package +'</td>'+
                '<td class="bg-white" width="5%">'+
                    '<input type="text" class="form-control" value='+ this.request_package +' style="height:20px; padding:0px;">'+
                '</td>'+
            '</tr>'
        });

        if(SCD_dataTable !==null)  {
            SCD_dataTable.destroy();
        }

        $("#SCD_listTable tbody").html("").html(rowListHtml);

        SCD_dataTable = dataTable('SCD_listTable');
        tableResize($("#SCD_listTable"));
    });
});

// $("#SCD_searchBtn").click(function()  {
//     var searchVal = $("#SCD_searchInput").val();
//     $("#SCD_listTable_filter input").val(searchVal).focus().click();
// });


$("#SCD_ListCreationBtn").click(function()  {
    $("#SCD_CD_foodType").text($("#SCD_foodTypes option:selected").val());
    $("#SCD_CD_selectedDate").text($("#SCD_subOption option:selected").text());
    $("#SCD_CD_RequiredNumber").text($("#SCD_RequiredNumber").val());
    $("#SCD_CD_peopleCnt").text($("#SCD_peopleCnt").val());

    var selectedRowIds = [];
    $("#SCD_listTable #checkBox").each(function()  {  //songogdson idiig tsugluulna
        if($(this).is(":checked"))  {
            selectedRowIds.push($(this).attr("rowId"));
        }
    });

    if(selectedRowIds.length === 0)  {
        alert("リクエストから選択してください。");
        return false;
    }

    var rowListHtml = null;
    postData(SERVER_+"/webOri/users/productCheck.json", {
        selectedRowIds: JSON.stringify(selectedRowIds)
    }).then(data => {
        $("#SCD_confirmDisplay").show();
        $("#supportListCreationDisplay").hide();

        $.each(data.Items, function()  {
            rowListHtml +=
            '<tr>'+
                '<th scope="row" width="2%">'+
                    '<input id="checkBox" type="checkbox" rowId='+ this.id +' />'+
                '</th>'+
                '<td width="25%">'+ this.good_name +'</td>'+
                '<td width="10%">'+ this.save_date +'</td>'+
                '<td width="10%">'+ this.storage_area +'</td>'+
                '<td width="5%">'+ this.available_count +'</td>'+
                '<td width="5%">'+ this.packing +'</td>'+
                '<td width="5%">'+ this.in_the_package_count +'</td>'+
                '<td width="5%">'+ this.unit_in_package +'</td>'+
                '<td class="bg-white" width="5%">'+
                    '<input type="text" class="form-control" value='+ this.request_package +' style="height:20px; padding:0px;">'+
                '</td>'+
            '</tr>'
        });
        
        if(SCD_CD_listTable !==null)  {
            SCD_CD_listTable.destroy();
        }

        $("#SCD_CD_listTable tbody").html("").html(rowListHtml);

        SCD_CD_listTable = dataTable('SCD_CD_listTable');
        tableResize($("#SCD_CD_listTable"));
    });
});

$("#SCD_dayWeekMonth").change(function()  {
    let subOption = $("#SCD_subOption").html("");
    let optionVal = $(this).val();
    subOptionDraw(subOption, optionVal);
}).change();

$("#SCD_backBtn").click(function()  {  // omnoh tsonhruu butsah
    $("#supportListCreationDisplay").show();
    $("#SCD_confirmDisplay").hide();
    $("#SCD_subOption option:selected").val();
});

$("#SCD_CD_requestConfirmedBtn").click(function()  {  //batalgaajuulah towch
    alert("リクエストを受付ました");
});

$("#supportListCreationDisplayBackBtn").click(function()  { //vndsen tsonhruu butsah
    $("#supportDestinationSearchDisplay").show();
    $("#supportListCreationDisplay").hide();
});
