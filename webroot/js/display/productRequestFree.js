
var PRF_listTable = null;
var PRF_CD_listTable = null;

$("#productRequestFreeBtn").click(function()  {
    $("#basicDisplay").hide();
    $("#productRequestFreeDisplay").show();

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

        
        if(PRF_listTable !==null)  {
            PRF_listTable.destroy();
        }

        $("#PRF_listTable tbody").html("").html(rowListHtml);
        PRF_listTable = dataTable('PRF_listTable');
        tableResize($("#PRF_listTable"));

        $("#PRF_searchInput").html(
            $("#PRF_listTable_filter input").clone(true)
            .addClass("form-control")
        );
    });
});

$("#PRF_requestCheck").click(function()  {
    $("#PRF_CD_foodType").text($("#PRF_foodTypes option:selected").val());
    $("#PRF_CD_selectedDate").text($("#PRF_subOption option:selected").text());
    $("#PRF_CD_RequiredNumber").text($("#PRF_RequiredNumber").val());
    $("#PRF_CD_peopleCnt").text($("#PRF_peopleCnt").val());

    var selectedRowIds = [];
    $("#PRF_listTable #checkBox").each(function()  {  //songogdson idiig tsugluulna
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
        $("#PRF_confirmDisplay").show();
        $("#productRequestFreeDisplay").hide();

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
        

        if(PRF_CD_listTable !==null)  {
            PRF_CD_listTable.destroy();
        }

        $("#PRF_CD_listTable tbody").html("").html(rowListHtml);

        PRF_CD_listTable = dataTable('PRF_CD_listTable');
        tableResize($("#PRF_CD_listTable"));
    });
});

$("#PRF_dayWeekMonth").change(function()  {
    let subOption = $("#PRF_subOption").html("");
    let optionVal = $(this).val();
    subOptionDraw(subOption, optionVal);
}).change();

$("#PRD_backBtn").click(function()  {  // omnoh tsonhruu butsah
    $("#productRequestFreeDisplay").show();
    $("#PRF_confirmDisplay").hide();
    $("#PRF_subOption option:selected").val();
});

$("#requestConfirmedBtn").click(function()  {  //batalgaajuulah towch
    alert("リクエストを受付ました");
});

$("#productRequestFreeDisplayBackBtn").click(function()  { //vndsen tsonhruu butsah
    $("#basicDisplay").show();
    $("#productRequestFreeDisplay").hide();
});
