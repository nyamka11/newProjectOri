$("#chartShow").click(function()  {
    $("#chartDisplay").show();
    $("#basicDisplay").hide();

    $("#chartLoader").show();
    $("#myChart").hide();

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
        $("#selectTownAllPeopleCnt cnt").text(selectedTownAllPeoplesCount);
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

        for(var index in jinkoInfo )  {
            jinkoInfo[index].gTotal = parseInt(jinkoInfo[index].Male) +  parseInt(jinkoInfo[index].Female);
            ChartObj.labelDatas.push(jinkoInfo[index].name);
            ChartObj.countDatas.push(jinkoInfo[index].gTotal);
        }

        selectedTownAllPeoplesCount  = ChartObj.countDatas.reduce(function(total, num) { 
            total = isNaN(total) ? 0 : total;
            num = isNaN(num) ? 0 : num;
            return total + num;
        });

        if(myChart!=null)  {
            myChart.destroy();
        }

        var ctx = document.getElementById('myChart').getContext('2d');
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
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                title: {
                    display: true,
                    text: '年齢'
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