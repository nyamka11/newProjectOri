
var SDSD_dataTable =null;
var SDSD_CD_listTable =null;

$("#supportDestinationSearchBtn").click(function()  {
    $("#basicDisplay").hide();
    $("#supportDestinationSearchDisplay").show();

    var rowListHtml = null;
    postData(SERVER_+"/webOri/users/supportDestinationSearch.json").then(data => {
        $.each(data.Items, function()  {
            rowListHtml +=
            '<tr rowId='+ this.id +'>'+
                '<td width="10%">'+ this.group_name+'</td>'+
                '<td width="5%">'+ this.deadline_support +'</td>'+
                '<td width="10%">'+ this.district +'</td>'+
                '<td width="3%">'+ this.basic_number_people +'</td>'+
                '<td width="3%">'+ this.number_of_people_this_time +'</td>'+
                '<td width="6%">'+ this.application +'</td>'+
                '<td width="25%">'+ this.destination_comment +'</td>'+
            '</tr>'
        });

        if(SDSD_dataTable !==null)  {
            SDSD_dataTable.destroy();
        }

        $("#SDSD_listTable tbody").html("").html(rowListHtml);

        SDSD_dataTable = dataTable('SDSD_listTable');
        tableResize($("#SDSD_listTable"));

        $("#SDSD_listTable tbody tr").click(function()  {
            $("#SDSD_listTable tbody tr").removeClass("selectedRow");
            $(this).addClass("selectedRow");
        });

        $("#SDSD_searchInput").html(
            $("#SDSD_listTable_filter input").clone(true)
            .addClass("form-control")
        );
    });
});

$("#SDSD_ListCreationBtn").click(function()  {
    $("#SDSD_CD_foodType").text($("#SDSD_foodTypes option:selected").val());

    var selectedRowIds = [];
    $("#SDSD_listTable tbody tr").each(function()  {  //songogdson idiig tsugluulna
        if($(this).hasClass("selectedRow"))  {
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
        $("#SDSD_confirmDisplay").show();
        $("#supportDestinationSearchDisplay").hide();

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
        
        if(SDSD_CD_listTable !==null)  {
            SDSD_CD_listTable.destroy();
        }

        $("#SDSD_CD_listTable tbody").html("").html(rowListHtml);

        SDSD_CD_listTable = dataTable('SDSD_CD_listTable');
        tableResize($("#SDSD_CD_listTable"));
    });
});


$("#SDSD_backBtn").click(function()  {  // omnoh tsonhruu butsah
    $("#supportDestinationSearchDisplay").show();
    $("#SDSD_confirmDisplay").hide();
});

// $("#SDSD_CD_requestConfirmedBtn").click(function()  {  //batalgaajuulah towch
//     // alert("リクエストを受付ました");
// });

$("#SDSD_BackBtn").click(function()  { //vndsen tsonhruu butsah
    $("#basicDisplay").show();
    $("#supportDestinationSearchDisplay").hide();
});
