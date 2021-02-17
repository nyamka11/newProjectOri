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
   
    <div id="mainContainer"><br/>
        <div id="basicDisplay" class="container m-auto border basicBackground">
            <div class="row">
                <div class="col-12 p-3">
                    <!-- <button id="backBtnAction" class="btn btn-secondary float-left">戻る</button> -->
                    <div id="townName"  class="textBox font-weight-bold w-25">浜松市</div>
                    <select name="" id="" class="btn btn-outline-dark ml-2 w-25 sysWhiteColor">
                        <option value="">住民</option>
                        <option value="">大規模事業者</option>
                    </select>

                    <select name="" id="timeOption" class="btn btn-outline-dark ml-2 w-25 sysWhiteColor">
                        <option class="p-3" value="">時間帯</option>
                        <option class="p-3" value="">09:00~16:00</option>
                        <option class="p-3" value="">15:00~23:00</option>
                        <option class="p-3" value="">22:00~5:00</option>
                    </select>
                </div>
                <div class="col-9" class="border">
                    <div id="map" class="mb-3"></div>
                </div>
                <div class="col-3">
                    <!-- <div id="findbox"></div> -->
                    <div style="width:100%; height:100%;">
                        <br/>
                        <button id="chartShow" type="button" class="btn btn-outline-dark float-left w-100 sysGreenColor">人口構成</button>
                        <button id="nutrientsShowBtn" type="button" class="btn btn-outline-dark float-left w-100 mt-3 sysYellowColor">必要栄養素</button>
                        <button id="requiredDemandDisplayShowBtn" type="button" class="btn btn-outline-dark float-left w-100 mt-3 sysPinkColor">必要需要数（食品）</button>
                        <button type="button" class="btn btn-outline-dark float-left w-100 mt-3 sysPinkColor">必要需要数（備蓄品）</button>
                        <button type="button" class="btn btn-outline-dark float-left w-100 mt-3 sysPinkColor">必要需要数（医薬品）</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="chartDisplay" class="container m-auto border basicBackground" style="display:none;">
            <div class="row">
                <div class="col-12 p-3">
                    <div class="textBox font-weight-bold">人口構成</div>
                    <div id="townNameChart" class="textBox font-weight-bold ml-2">浜松市</div>
                    <div id="selectTownAllPeopleCnt" class="textBox font-weight-bold ml-2">全員数: <cnt>0</cnt></div>
                </div> 
                <div class="col-12 sysWhiteColor" style="display: flex; align-items: center; justify-content: center;">
                    <canvas id="myChart" width="" height=""></canvas>
                    <div id="chartLoader" class="spinner-border" role="status" style="margin: 100px 0px 100px 0px">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="col-12">
                    <button id="backBtnDisplay" class="btn btn-secondary float-right sysBackBtn mt-4 mb-4">初期画面に戻る</button>
                </div>
            </div>   
        </div>
        
        <div id="nutrientsDisplay" class="container m-auto border basicBackground" style="display:none">
            <div class="row">
                <div class="col-12 p-3">
                    <div class="textBox font-weight-bold">必要栄養素</div>
                    <div id="townNameNutrients" class="textBox font-weight-bold ml-2">浜松市</div>
                    <select name="" id="SpecialAgeName" class="btn btn-outline-dark ml-2 sysWhiteColor">
                        <option value="乳幼児">乳幼児</option>
                        <option value="小児">小児</option>
                        <option value="一般成人" selected="true">一般成人</option>
                        <option value="特別老人">特別老人</option>
                    </select>
                    <select name="" id="dayWeekMonth" class="btn btn-outline-dark ml-2 sysWhiteColor">
                        <option value="day">日</option>
                        <option value="week">週</option>
                        <option value="month">月</option>
                    </select>
                    <select name="" id="subOption" class="btn btn-outline-dark ml-2 sysWhiteColor">
                    </select>
                    <button id="backBtnNutrients" class="btn btn-secondary float-right sysBackBtn">初期画面に戻る</button>
                </div> 
                <div class="col-12">
                    <div id="tableBody">

                    </div>
                </div>
            </div>
        </div>

        <div id="requiredDemandDisplay" class="container m-auto border basicBackground" style="display:none">
            <div class="row">
                <div class="col-12 p-3">
                    <div class="textBox font-weight-bold">必要栄養素</div>
                    <div id="townNameNutrientsReq" class="textBox font-weight-bold ml-2">浜松市</div>
                    <select name="" id="SpecialAgeNameReq" class="btn btn-outline-dark ml-2 sysWhiteColor">
                        <option value="乳幼児">乳幼児</option>
                        <option value="幼児">幼児</option>
                        <option value="小児">小児</option>
                        <option value="一般成人" selected="true">一般成人</option>
                        <option value="特別老人">特別老人</option>
                    </select>
                    <select name="" id="dayWeekMonthReq" class="btn btn-outline-dark ml-2 sysWhiteColor">
                        <option value="day">日</option>
                        <option value="week">週</option>
                        <option value="month">月</option>
                    </select>
                    <select name="" id="subOptionReq" class="btn btn-outline-dark ml-2 sysWhiteColor">
                    </select>
                    <button id="backBtnRequiredDemand" class="btn btn-secondary float-right sysBackBtn">初期画面に戻る</button>
                </div>

                <div class="col-3">
                    <select name="" id="menuNameCombo" class="btn btn-outline-dark w-100 sysWhiteColor">
                        <option value="">サンプル1日セット冷凍セット</option>
                        <option value="">浜松市備蓄分セット</option>
                    </select>
                    <ul  id="foodNameList" class="list-group  mt-2" style="overflow: auto; height: 555px;">
                    </ul>
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
    <?= $this->Html->script('home.js') ?>
