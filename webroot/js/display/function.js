function subOptionDraw(subOption, optionVal)  {
    if(optionVal === "day") {
        for(let i=1; i<=31; i++) {
            subOption.append("<option value="+ i +">"+ i +"日</option>");
        }
    }
    if(optionVal === "week")  {
        for(let i=1; i<=20; i++)  {
            subOption.append("<option value="+ i +">"+ i +"週</option>");
        }
    }
    if(optionVal === "month") {
        for(let i=1; i<=12; i++)  {
            subOption.append("<option value="+ i +">"+ i +"か月</option>");
        }
    }
}

function setTownName(name)  { // Display name change fn start 
    document.getElementById('townName').innerHTML = name;
    document.getElementById('townNameChart').innerHTML = name;
}

function tableResize(tableObj)  {
    // Change the selector if needed

    var $table = tableObj,
    $bodyCells = $table.find('tbody tr:first').children(),
    colWidth;

    // Get the tbody columns width array
    colWidth = $bodyCells.map(function() {
    return $(this).width();
    }).get();

    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
       $(v).width(colWidth[i]);
    });   
}

function dataTable(tableObj)  {
    var dtl =  $('#'+tableObj).DataTable({
        "columnDefs": [ {
            "targets": 'no-sort',
            "orderable": false,
        }],
        "order": [[ 2, "asc" ]]
    });
    $('.dataTables_length').addClass('bs-select');

    $("#"+ tableObj +"_filter").hide();
    $("#"+ tableObj +"_info").hide();
    $("#"+ tableObj +"_paginate").hide();

    $("[name ='"+ tableObj +"_length'] option:last")
    .attr('selected',true).change();
    $("#"+ tableObj +"_length").hide();

    return dtl;
}


function markerIcon(liveAndWork, latlng)  {
    var marker;
    if(liveAndWork == MODE_LIVE)  {
        marker = L.marker(latlng);
    }

    if(liveAndWork == MODE_WORK)  {
        var greenIcon = L.icon({
            iconUrl: 'img/marker-icon-green.png',
            iconSize:     [25, 41], // size of the icon
            iconAnchor:   [10, 41], // point of the icon which will correspond to marker's location
            popupAnchor:  [0, -10] // point from which the popup should open relative to the iconAnchor
        });
        marker = L.marker(latlng, {icon: greenIcon});
    }

    if(liveAndWork == MODE_DEPO)  {
        var redIcon = L.icon({
            iconUrl: 'img/marker-icon-red.png',
            iconSize:     [25, 41], // size of the icon
            iconAnchor:   [10, 41], // point of the icon which will correspond to marker's location
            popupAnchor:  [0, -10] // point from which the popup should open relative to the iconAnchor
        });
        marker = L.marker(latlng, {icon: redIcon});
    }

    return marker;
}
