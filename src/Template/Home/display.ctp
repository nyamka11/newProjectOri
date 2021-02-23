<?= $this->element('header') ?> 
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>

 <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>

   <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
   <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>


   <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>

   <?= $this->Html->css('leaflet-search.css') ?>
   
    <div id="mainContainer"><br/>
        <div id="basicDisplay" class="container m-auto border basicBackground">
            <div class="row">
                <div class="col-12 p-3">
                    <!-- <button id="backBtnAction" class="btn btn-secondary float-left">戻る</button> -->
                    <div id="townName"  class="textBox font-weight-bold w-25">浜松市</div>
                    <select name="" id="liveAndWork" class="btn btn-outline-dark ml-2 w-25 sysWhiteColor">
                        <option value="0">住民</option>
                        <option value="1">大規模事業者</option>
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

                        <button id="productRequestFreeBtn" type="button" class="btn btn-outline-dark float-left w-100 mt-3">支援品リクエストフリー指定</button>
                        <button type="button" class="btn btn-outline-dark float-left w-100 mt-3">支援品リクエスト自動計算</button>

                        <button id="supportListCreationBtn" type="button" class="btn btn-outline-dark float-left w-100 mt-3">支援先指定</button>
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
                        <option value="サンプル1日セット冷凍セット">サンプル1日セット冷凍セット</option>
                        <option value="浜松市備蓄分セット">浜松市備蓄分セット</option>
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

        <div id="productRequestFreeDisplay" class="container m-auto border basicBackground" style="display:none">
            <div class="row">
                <div class="col-6 mt-3">
                    <div class="textBox font-weight-bold w-100">支援品リクエストフリー指定</div>
                </div>
                <div class="col-3 mt-3">
                    <div class="textBox w-100">ことも支援食堂</div>
                </div>
                <div class="col-3 mt-3">
                    <button id="productRequestFreeDisplayBackBtn" class="btn btn-secondary float-right sysBackBtn">初期画面に戻る</button>
                </div>

                <div class="col-6 mt-3">
                    <select name="" id="PRF_foodTypes" class="btn btn-outline-dark w-50 sysWhiteColor">
                        <option value="食品・食料品">食品・食料品</option>
                        <option value="日用品">日用品</option>
                        <option value="薬">薬</option>
                        <option value="非常用品">非常用品</option>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <div class="textBox bg-white w-50" style="padding: 2% 6% 2% 6%;">人数</div>
                    <input id="PRF_peopleCnt" type="text" class="form-control w-50" value="30">
                </div>

                <div class="col-3 mt-3">
                    <select id="PRF_dayWeekMonth" class="btn btn-outline-dark sysWhiteColor">
                        <option value="day">日</option>
                        <option value="week">週</option>
                        <option value="month">月</option>
                    </select>

                    <select id="PRF_subOption" class="btn btn-outline-dark ml-2 sysWhiteColor">
                    </select>
                </div>

                <div class="col-6 mt-3">
                    <div class="input-group mb-3 float-left">
                        <div class="textBox bg-white"  style="padding: 1% 2% 1% 2%;">検索ワード</div>
                        <input type="text" class="form-control" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary bg-green text-dark" style="background: #a0ffa0;" type="button">検索</button>
                        </div>
                    </div>
                </div>

                <div class="col-6 mt-3">
                    <div class="textBox w-25 bg-white"  style="padding: 1% 0% 1% 0%;">必要数</div>
                    <input id="PRF_RequiredNumber" type="text" class="form-control w-25 float-left" value="90">
                    <div class="textBox w-25 bg-white" style="padding: 1% 0% 1% 0%;">食</div>
                </div>

                <div class="col-12" style="height:554px;">
                    <table id="PRF_listTable" class="table table-bordered table-sm">
                        <thead style="background-color:#dedefb">
                            <tr>
                                <th scope="col" width="40">CK</th>
                                <th scope="col">物品名</th>
                                <th scope="col" width="100">保管期限</th>
                                <th scope="col">保管場所</th>

                                <th scope="col" width="70">供出可能数量</th>
                                <th scope="col" width="70">荷姿</th>
                                <th scope="col" width="70">荷姿内数量</th>
                                <th scope="col" width="70">荷姿内単位</th>
                                <th scope="col" width="100">リクエスト数量荷姿内単位</th>
                            </tr>
                        </thead>
                        <tbody style="background-color:#f9f2ff; height:400px">
                            
                        </tbody>
                    </table>
                    <button id="PRF_requestCheck" class="btn btn-secondary float-right text-dark sysPinkColor">リクエスト確認</button>
                </div>
            </div>
        </div>

        <div id="PRF_confirmDisplay" class="container m-auto border basicBackground" style="display:none">
            <div class="row">
                <div class="col-6 mt-3">
                    <div class="textBox font-weight-bold w-100">支援品リクエストフリー確定画面</div>
                </div>
                <div class="col-3 mt-3">
                    <div class="textBox w-100">ことも支援食堂</div>
                </div>
                <div class="col-3 mt-3">
                    <button id="PRD_backBtn" class="btn btn-secondary float-right sysBackBtn">リクエスト画面へ</button>
                </div>

                <div class="col-6 mt-3">
                    <div id="PRF_CD_foodType" class="textBox w-50"></div>
                </div>

                <div class="col-3 mt-3">
                    <div class="textBox w-50 bg-white" style="padding: 2% 6% 2% 6%;">人数</div>
                    <div id="PRF_CD_peopleCnt" class="textBox w-50" style="padding: 2% 6% 2% 6%;">30</div>
                </div>

                <div class="col-3 mt-3">
                    <div id="PRF_CD_selectedDate" class="textBox w-100"></div>
                </div>

                <div class="col-6 mt-3">
                <div class="textBox w-25" style="padding: 1% 0% 1% 0%;">入荷希望日</div>
                    <div class="textBox w-25 bg-white" style="padding: 1% 0% 1% 0%;">2021/4/10</div>
                </div>

                <div class="col-6 mt-3">
                    <div class="textBox w-25 bg-white"  style="padding: 1% 0% 1% 0%;">必要数</div>
                    <div id="PRF_CD_RequiredNumber" class="textBox w-25" style="padding: 1% 0% 1% 0%;">90</div>
                    <div class="textBox w-25 bg-white" style="padding: 1% 0% 1% 0%;">食</div>
                </div>

                <div class="col-12" style="height:554px;">
                    <table id="PRF_CD_listTable" class="table table-bordered table-sm mt-3">
                        <thead style="background-color:#dedefb">
                            <tr>
                                <th scope="col" width="40">CK</th>
                                <th scope="col">物品名</th>
                                <th scope="col" width="100">保管期限</th>
                                <th scope="col">保管場所</th>

                                <th scope="col" width="70">供出可能数量</th>
                                <th scope="col" width="70">荷姿</th>
                                <th scope="col" width="70">荷姿内数量</th>
                                <th scope="col" width="70">荷姿内単位</th>
                                <th scope="col" width="100">リクエスト数量荷姿内単位</th>
                            </tr>
                        </thead>
                        <tbody style="background-color:#f9f2ff; height:384px">
                        </tbody>
                    </table>
                    <button id="requestConfirmedBtn" class="btn btn-secondary float-right text-dark sysPinkColor">リクエスト確定</button>
                </div>
            </div>
        </div>

        <div id="supportListCreationDisplay" class="container m-auto border basicBackground" style="display:none">
            <div class="row">
                <div class="col-6 mt-3">
                    <div class="textBox font-weight-bold w-100">支援リスト作成</div>
                </div>
                <div class="col-3 mt-3">
                    <div class="textBox w-100">ことも支援食堂</div>
                </div>
                <div class="col-3 mt-3">
                    <button id="supportListCreationDisplayBackBtn" class="btn btn-secondary float-right bg-success">初期画面に戻る</button>
                </div>

                <div class="col-6 mt-3">
                    <select name="" id="SCD_foodTypes" class="btn btn-outline-dark w-50 sysWhiteColor">
                        <option value="食品・食料品">食品・食料品</option>
                        <option value="日用品">日用品</option>
                        <option value="薬">薬</option>
                        <option value="非常用品">非常用品</option>
                    </select>
                </div>

                <div class="col-3 mt-3">
                    <div class="textBox bg-white w-50" style="padding: 2% 6% 2% 6%;">人数</div>
                    <input id="SCD_peopleCnt" type="text" class="form-control w-50" value="30">
                </div>

                <div class="col-3 mt-3">
                    <select id="SCD_dayWeekMonth" class="btn btn-outline-dark sysWhiteColor">
                        <option value="day">日</option>
                        <option value="week">週</option>
                        <option value="month">月</option>
                    </select>

                    <select id="SCD_subOption" class="btn btn-outline-dark ml-2 sysWhiteColor">
                    </select>
                </div>

                <div class="col-6 mt-3">
                    <div class="input-group mb-3 float-left">
                        <div class="textBox bg-white"  style="padding: 1% 2% 1% 2%;">検索ワード</div>
                        <input id="SCD_searchInput" type="text" class="form-control" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button id="SCD_searchBtn" class="btn btn-outline-secondary bg-green text-dark" style="background: #a0ffa0;" type="button">検索</button>
                        </div>
                    </div>
                </div>

                <div class="col-6 mt-3">
                    <div class="textBox w-25 bg-white"  style="padding: 1% 0% 1% 0%;">必要数</div>
                    <input id="SCD_RequiredNumber" type="text" class="form-control w-25 float-left" value="90">
                    <div class="textBox w-25 bg-white" style="padding: 1% 0% 1% 0%;">食</div>
                </div>

                <div class="col-12" style="height:554px;">
                    <table  id="SCD_listTable" class="table table-striped table-bordered table-sm" cellspacing="0">
                        <thead style="background-color:#dedefb">
                            <tr>
                                <th class="no-sort" scope="col">CK</th>
                                <th scope="col">物品名</th>
                                <th scope="col">保管期限</th>
                                <th class="no-sort" scope="col">保管場所</th>

                                <th scope="col">供出可能数量</th>
                                <th class="no-sort" scope="col">荷姿</th>
                                <th class="no-sort" scope="col">荷姿内数量</th>
                                <th class="no-sort" scope="col">荷姿内単位</th>
                                <th class="no-sort" scope="col">リクエスト数量荷姿内単位</th>
                            </tr>
                        </thead>
                        <tbody style="background-color:#f9f2ff; height:400px">
                            
                        </tbody>
                    </table>
                    <button id="SCD_ListCreationBtn" class="btn btn-secondary float-right text-dark sysPinkColor">支援リスト作成</button>
                </div>
            </div>
        </div>

        <div id="SCD_confirmDisplay" class="container m-auto border basicBackground" style="display:none">
            <div class="row">
                <div class="col-6 mt-3">
                    <div class="textBox font-weight-bold w-100">支援品リクエストフリー確定画面</div>
                </div>
                <div class="col-3 mt-3">
                    <div class="textBox w-100">ことも支援食堂</div>
                </div>
                <div class="col-3 mt-3">
                    <button id="SCD_backBtn" class="btn btn-secondary float-right bg-success">リクエスト画面へ</button>
                </div>

                <div class="col-6 mt-3">
                    <div id="SCD_CD_foodType" class="textBox w-50"></div>
                </div>

                <div class="col-3 mt-3">
                    <div class="textBox w-50 bg-white" style="padding: 2% 6% 2% 6%;">人数</div>
                    <div id="SCD_CD_peopleCnt" class="textBox w-50" style="padding: 2% 6% 2% 6%;">30</div>
                </div>

                <div class="col-3 mt-3">
                    <div id="SCD_CD_selectedDate" class="textBox w-100"></div>
                </div>

                <div class="col-6 mt-3">
                </div>

                <div class="col-6 mt-3">
                    <div class="textBox w-25 bg-white"  style="padding: 1% 0% 1% 0%;">必要数</div>
                    <div id="SCD_CD_RequiredNumber" class="textBox w-25" style="padding: 1% 0% 1% 0%;">90</div>
                    <div class="textBox w-25 bg-white" style="padding: 1% 0% 1% 0%;">食</div>
                </div>

                <div class="col-12" style="height:554px;">
                    <table  id="SCD_CD_listTable" class="table table-striped table-bordered table-sm mt-3" cellspacing="0">
                        <thead style="background-color:#dedefb">
                            <tr>
                                <th class="no-sort" scope="col">CK</th>
                                <th scope="col">物品名</th>
                                <th scope="col">保管期限</th>
                                <th class="no-sort" scope="col">保管場所</th>

                                <th scope="col">供出可能数量</th>
                                <th class="no-sort" scope="col">荷姿</th>
                                <th class="no-sort" scope="col">荷姿内数量</th>
                                <th class="no-sort" scope="col">荷姿内単位</th>
                                <th class="no-sort" scope="col">リクエスト数量荷姿内単位</th>
                            </tr>
                        </thead>
                        <tbody style="background-color:#f9f2ff; height:400px">
                            
                        </tbody>
                    </table>
                    <button id="requestConfirmedBtn" class="btn btn-secondary float-right text-dark sysPinkColor">リクエスト確定</button>
                </div>
            </div>
        </div>
    </div>

    <?= $this->Html->script('constant.js') ?> 
    <?= $this->Html->script('main.js') ?> 
    <?= $this->Html->script('leaflet-search.js') ?>
    <?= $this->Html->script('cityBorderJson.js') ?>
    <?= $this->Html->script('home.js') ?>
