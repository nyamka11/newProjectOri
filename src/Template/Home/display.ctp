<?= $this->element('header') ?> 
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>

 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

   <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

   <?= $this->Html->css('leaflet-search.css') ?>
   
   <style>
        #map {
            height: 700px; 
            width: 100%; 
        }
        #findbox {
            height:20px;
            margin-top:10px;
        }
        .search-input  {
            width:80%;
        }
        .search-tooltip {
            width: 200px;
        }
        .leaflet-control-search .search-cancel {
            position: static;
            float: left;
            margin-left: -22px;
        }
        .textBox {
            border: 1px solid black;
            float: left;
            padding: 6px 67px 5px 66px;
            text-align: center;
            background-color: #cacaca;
        }
   </style>

    <div id="mainContainer">
        <div id="basicDisplay">
            <div class="row container mt-1  m-auto border">
                <div class="col-12 p-3">
                    <!-- <button id="backBtnAction" class="btn btn-secondary float-left">戻る</button> -->
                    <div id="townName"  class="textBox font-weight-bold w-25">浜松市</div>
                    <select name="" id="" class="btn btn-outline-dark ml-2 w-25">
                        <option value="">住民</option>
                        <option value="">大規模事業者</option>
                    </select>

                    <select name="" id="" class="btn btn-outline-dark ml-2 w-25">
                        <option class="p-3" value="">時間帯</option>
                        <option class="p-3" value="">9:00~16:00</option>
                        <option class="p-3" value="">15:00~23:00</option>
                        <option class="p-3" value="">22:00~5:00</option>
                    </select>
                </div>
                <div class="col-9" style>
                    <div id="map"></div>
                </div>
                <div class="col-3">
                    <!-- <div id="findbox"></div> -->
                    <div style="width:100%; height:100%;">
                        <br/>
                        <button id="chartShow" type="button" class="btn btn-outline-dark float-left w-100">人口構成</button>
                        <button id="nutrientsShowBtn" type="button" class="btn btn-outline-dark float-left w-100 mt-3">必要栄養素</button>
                        <button id="requiredDemandDisplayShowBtn" type="button" class="btn btn-outline-dark float-left w-100 mt-3">必要需要数1</button>
                        <button type="button" class="btn btn-outline-dark float-left w-100 mt-3">必要需要数2</button>
                        <button type="button" class="btn btn-outline-dark float-left w-100 mt-3">必要需要数3</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="chartDisplay" class="container mt-1 m-auto" style="display:none;">
            <div class="row container mt-1  m-auto border">
                <div class="col-12 p-3">
                    <div class="textBox font-weight-bold">人口構成</div>
                    <div id="townNameChart" class="textBox font-weight-bold ml-2">浜松市</div>
                </div> 
                <div class="col-12">
                    <canvas id="myChart" width="" height=""></canvas>
                </div>
                <div class="col-12">
                    <br/><button id="backBtnDisplay" class="btn btn-secondary float-right">初期画面に戻る</button>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                </div>
            </div>   
        </div>
        <br/>
        
        <div id="nutrientsDisplay" class="container m-auto" style="display:none">
            <div class="row container mt-1 m-auto border">
                <div class="col-12 p-3">
                    <div class="textBox font-weight-bold">必要栄養素</div>
                    <div id="townNameNutrients" class="textBox font-weight-bold ml-2">浜松市</div>
                    <select name="" id="SpecialAgeName" class="btn btn-outline-dark ml-2">
                        <option value="乳幼児">乳幼児</option>
                        <option value="小児">小児</option>
                        <option value="一般成人" selected="true">一般成人</option>
                        <option value="特別老人">特別老人</option>
                    </select>
                    <select name="" id="dayWeekMonth" class="btn btn-outline-dark ml-2">
                        <option value="day">日</option>
                        <option value="week">週</option>
                        <option value="month">月</option>
                    </select>
                    <select name="" id="subOption" class="btn btn-outline-dark ml-2">
                    </select>
                    <button id="backBtnNutrients" class="btn btn-secondary float-right">初期画面に戻る</button>
                </div> 
                <div class="col-12">
                    <div id="tableBody">

                    </div>
                </div>
            </div>
        </div>

        <div id="requiredDemandDisplay" class="container m-auto" style="display:none">
            <div class="row container mt-1 m-auto border pb-3">
                <div class="col-12 p-3">
                    <div class="textBox font-weight-bold">必要栄養素</div>
                    <div id="townNameNutrientsReq" class="textBox font-weight-bold ml-2">浜松市</div>
                    <select name="" id="SpecialAgeNameReq" class="btn btn-outline-dark ml-2">
                        <option value="乳幼児">乳幼児</option>
                        <option value="小児">幼児</option>
                        <option value="小児">小児</option>
                        <option value="一般成人" selected="true">一般成人</option>
                        <option value="特別老人">特別老人</option>
                    </select>
                    <select name="" id="dayWeekMonthReq" class="btn btn-outline-dark ml-2">
                        <option value="day">日</option>
                        <option value="week">週</option>
                        <option value="month">月</option>
                    </select>
                    <select name="" id="subOptionReq" class="btn btn-outline-dark ml-2">
                    </select>
                    <button id="backBtnRequiredDemand" class="btn btn-secondary float-right">初期画面に戻る</button>
                </div>

                <div class="col-3">
                    <select name="" id="menuNameCombo" class="btn btn-outline-dark w-100">
                        <option value="">サンプル1日セット冷凍セット</option>
                        <option value="">浜松市備蓄分セット</option>
                    </select>
                    <div id="foodNameList" class="list-group mt-2" style="overflow: auto; height: 555px;">
                        
                    </div>
                </div>
                <div class="col-9">
                    <div id="tableBodyReq1" class="border p-2" style="height: 600px; overflow: auto;">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->Html->script('constant.js') ?> 
    <?= $this->Html->script('main.js') ?> 
    <?= $this->Html->script('leaflet-search.js') ?>
    <?= $this->Html->script('cityBorderJson.js') ?>
    <?= $this->Html->script('bigZonePointJson.js') ?>
    <?= $this->Html->script('smallZonePointJson.js') ?>
    <?= $this->Html->script('home.js') ?>
