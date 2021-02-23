

$("#productRequestFreeBtn").click(function()  {
    $("#basicDisplay").hide();
    $("#productRequestFreeDisplay").show();

    var rowListHtml = null;
    postData(SERVER_+"/webOri/users/productFree.json").then(data => {
        $.each(data.Items, function()  {
            rowListHtml +=
            '<tr>'+
                '<th scope="row" width="40">'+
                    '<input id="checkBox" type="checkbox" rowId='+ this.id +' />'+
                '</th>'+
                '<td>'+ this.good_name +'</td>'+
                '<td width="100">'+ this.save_date +'</td>'+
                '<td>'+ this.storage_area +'</td>'+
                '<td width="70">'+ this.available_count +'</td>'+
                '<td width="70">'+ this.packing +'</td>'+
                '<td width="70">'+ this.in_the_package_count +'</td>'+
                '<td width="70">'+ this.unit_in_package +'</td>'+
                '<td class="bg-white" width="100">'+
                    '<input type="text" class="form-control" value='+ this.request_package +' style="height:20px; padding:0px;">'+
                '</td>'+
            '</tr>'
        });

        $("#PRF_listTable tbody").html("").html(rowListHtml);
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

    var rowListHtml = null;
    postData(SERVER_+"/webOri/users/productCheck.json", {
        selectedRowIds: JSON.stringify(selectedRowIds)
    }).then(data => {
        $("#PRF_confirmDisplay").show();
        $("#productRequestFreeDisplay").hide();

        $.each(data.Items, function()  {
            rowListHtml +=
            '<tr>'+
                '<th scope="row" width="40">'+
                    '<input id="checkBox" type="checkbox" rowId='+ this.id +' />'+
                '</th>'+
                '<td>'+ this.good_name +'</td>'+
                '<td width="100">'+ this.save_date +'</td>'+
                '<td>'+ this.storage_area +'</td>'+
                '<td width="70">'+ this.available_count +'</td>'+
                '<td width="70">'+ this.packing +'</td>'+
                '<td width="70">'+ this.in_the_package_count +'</td>'+
                '<td width="70">'+ this.unit_in_package +'</td>'+
                '<td class="bg-white" width="100">'+ this.request_package +'</td>'+
            '</tr>'
        });
        $("#PRF_CD_listTable tbody").html("").html(rowListHtml);
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
