$("#chartShow").click(function()  {
    $("#chartDisplay").show();
    $("#basicDisplay").hide();

    $("#chartLoader").show();
    $("#myChart").hide();
    $("#selectTownAllPeopleCnt cnt").text("0");

    postData(ownServerUrl, { 
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
        $("#chartLoader").hide();
        $("#myChart").show();
    });
});

var ChartObj = {
    countDatas: [],
    labelDatas: [],
    bodyDraw: function(data)  {
        ChartObj.countDatas = [];
        ChartObj.labelDatas = [];
        jinkoInfo = [];

        for(const key in data) {
            if(data[key][0] == undefined) continue;
            data[key][0].name = key;
            jinkoInfo.push(data[key][0]);
        }

        for(var index in jinkoInfo)  {
            if(jinkoInfo[index].Male == null || jinkoInfo[index].Female == null) {
               continue;
            }

            var ttl = parseInt(jinkoInfo[index].Male) +  parseInt(jinkoInfo[index].Female);
            if(ttl == 0) continue;
            jinkoInfo[index].gTotal =ttl;
            ChartObj.labelDatas.push(jinkoInfo[index].name);
            ChartObj.countDatas.push(jinkoInfo[index].gTotal);
        }

        if(myChart!=null)  {
            myChart.destroy();
        }

        var canvas = document.getElementById('myChart')
        var ctx = canvas.getContext("2d");

        if(ChartObj.countDatas.length === 0)  {
            ctx.font = "70px Arial";
            ctx.textAlign = "center";
            ctx.fillText("0人です。", canvas.width/2+30, canvas.height/2+30);
            return false;
        }

        selectedTownAllPeoplesCount  = ChartObj.countDatas.reduce(function(total, num) { 
            total = isNaN(total) ? 0 : total;
            num = isNaN(num) ? 0 : num;
            return total + num;
        });

        $("#selectTownAllPeopleCnt cnt").text(selectedTownAllPeoplesCount);

        myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ChartObj.labelDatas,
                datasets: [{
                    label: '年齢',
                    data: ChartObj.countDatas,
                    backgroundColor: CHART_BACKGROUND_COLORS,
                    borderColor: CHART_BORDER_COLORS,
                    borderWidth: 1
                }]
            },
            options: {
                // responsive: false,
                // maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 100,
                        right: 100,
                        top: 130,
                        bottom: 100
                    }
                },
                legend: {
                    display: true,
                    position: 'right',
                },
                
                plugins: {
                    outlabels: {
                       text: '%l %p',
                       color: 'black',
                       stretch: 25,
                       font: {
                           resizable: true,
                           minSize: 15,
                           maxSize: 18
                       }
                    }
                }

            }
        });
    }
}

$("#backBtnDisplay").click(function()  {
    $("#basicDisplay").show();
    $("#chartDisplay").hide();
    myChart.destroy();
});