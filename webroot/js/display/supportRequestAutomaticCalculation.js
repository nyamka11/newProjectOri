
var AF_listTable = null;
var AF_CD_listTable = null;

$("#automaticCalculationBtn").click(function()  {
    $("#basicDisplay").hide();
    $("#autoFreeDisplay").show();

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

        
        if(AF_listTable !==null)  {
            AF_listTable.destroy();
        }

        $("#AF_listTable tbody").html("").html(rowListHtml);
        AF_listTable = dataTable('AF_listTable');
        tableResize($("#AF_listTable"));
    });
});

$("#AF_requestCheck").click(function()  {
    $("#AF_CD_foodType").text($("#AF_foodTypes option:selected").val());
    $("#AF_CD_selectedDate").text($("#AF_subOption option:selected").text());
    $("#AF_CD_RequiredNumber").text($("#AF_RequiredNumber").val());
    $("#AF_CD_peopleCnt").text($("#AF_peopleCnt").val());

    var selectedRowIds = [];
    $("#AF_listTable #checkBox").each(function()  {  //songogdson idiig tsugluulna
        if($(this).is(":checked"))  {
            selectedRowIds.push($(this).attr("rowId"));
        }
    });

    if(selectedRowIds.length === 0)  {
        alert("リクエストから何か選択してください。");
        return false;
    }

    var rowListHtml = null;
    postData(SERVER_+"/webOri/users/productCheck.json", {
        selectedRowIds: JSON.stringify(selectedRowIds)
    }).then(data => {
        $("#AF_confirmDisplay").show();
        $("#autoFreeDisplay").hide();

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
        

        if(AF_CD_listTable !==null)  {
            AF_CD_listTable.destroy();
        }

        $("#AF_CD_listTable tbody").html("").html(rowListHtml);

        AF_CD_listTable = dataTable('AF_CD_listTable');
        tableResize($("#AF_CD_listTable"));
    });
});

$("#AF_dayWeekMonth").change(function()  {
    let subOption = $("#AF_subOption").html("");
    let optionVal = $(this).val();
    subOptionDraw(subOption, optionVal);
}).change();

$("#AFD_backBtn").click(function()  {  // omnoh tsonhruu butsah
    $("#autoFreeDisplay").show();
    $("#AF_confirmDisplay").hide();
    $("#AF_subOption option:selected").val();
});

$("#requestConfirmedBtn").click(function()  {  //batalgaajuulah towch
    alert("リクエストを受付ました");
});

$("#autoFreeDisplayBackBtn").click(function()  { //vndsen tsonhruu butsah
    $("#basicDisplay").show();
    $("#autoFreeDisplay").hide();
});
