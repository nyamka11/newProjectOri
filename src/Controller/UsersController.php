<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;
use Cake\View\ViewBuilder;
use Cake\Datasource\ConnectionManager;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController  {
    public function login()  {
        if($this->request->is('post'))  {
            $user = $this->Auth->identify();
            if($user)  {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('Your username or password is incorrect');
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function register()  {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->createDate = time();
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
            // debug($user->errors());
        }
        $this->set(compact('user',));
    }

    public function index()  {
        $this->paginate = [
            'contain' => [],
        ];
        $users = $this->paginate($this->Users);
        $this->set(['users' => $users, '_serialize' => true]);
    }

    public function view($id = null)  {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        $this->set(['user' => $user, '_serialize' => true]);
    }

    public function add()  {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user->createDate = time();
            $user->password = "1200";
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
            // debug($user->errors());
        }
        $this->set(compact('user',));
    }

    public function edit($id = null)  {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    public function delete($id = null) {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getLocations()  {
        $timeOption = $this->request->getQuery("timeOption");
        $liveAndWork = $this->request->getQuery("liveAndWork");

        $Table = TableRegistry::getTableLocator()->get('population');
        $locations;

        if($liveAndWork == 2)  { //aguulah ni hvn dawhardahgui bolohoor
            $locations = $Table->find()
            ->select(['id','県名','市','区','町','場所名前','latitude','longitude','map_layer_level'])
            ->where(['live_and_work'=>$liveAndWork]);
        }
        else {
            $locations = $Table->find()
            ->select(['県名','市','区','町','場所名前','latitude','longitude','map_layer_level'])
            ->where(['live_and_work'=>$liveAndWork])
            ->group(['県名','市','区','町']);
        }

        $this->set([
            'locations' => $locations,
            '_serialize' => true
        ]);
    }

    public function getdata()  {
        $kenName = $this->request->getQuery("kenName");
        $shiName = $this->request->getQuery("shiName");
        $kuName = $this->request->getQuery("kuName");
        $town = $this->request->getQuery("townName");
        $type = $this->request->getQuery("type");
        $timeOption = $this->request->getQuery("timeOption");
        $liveAndWork = $this->request->getQuery("liveAndWork");

        $Table = TableRegistry::getTableLocator()->get('population');

        $data = array();
        $mainWhere = [];

        $selectFields = ['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市","区", "町"];
        $fields = array('0','1-2','3-5','6-7','8-9','10-11','12-14','15-17','18-29','30-49','50-64','65-74','75');
        foreach ($fields as $key => $item)  {
            $mainWhere = [];

            if($timeOption !== '時間帯')  { //tsag songoson bol gadnaas nemgdej ireh hvn
                $timeArr = explode("~", $timeOption);
                $mainWhere['start_time'] = $timeArr[0];
                $mainWhere['end_time'] = $timeArr[1];
            }

            if(!strpos($item, "-"))  {
                if($key == count($fields)-1) $mainWhere['年齢 >= '] = $item; //Hamgiin svvliin element
                else                         $mainWhere['年齢 = '] = $item; //Hamgiin ehnii element
            }
            else {
                $itemArr = explode("-",$item);
                $mainWhere['年齢 >= '] = $itemArr[0];
                $mainWhere['年齢 <= '] = $itemArr[1];
            }

            if($type == "big" && $kuName != null) {
                // tom ni dotroo 2 tvshintei bgaa
                    // 1 ni hotiig bvheleer ni harah
                    // 2 ni hotiig bvseer ni harah
                $mainWhere['区'] = $kuName;
            }
            else if($type == "small") {
                $mainWhere['町'] = $town;
            }

            $mainWhere['live_and_work'] = $liveAndWork;

            //Databasees datagaa awch irj bgaa query
            $data[$item."歳"] = $Table ->find()->select($selectFields)->where($mainWhere);
        }

        $data['_serialize'] = true;
        $this->set($data);

    }

    public function getNutrients()  {
        $SpecialAgeName = $this->request->getQuery("SpecialAgeName");
        $dayWeekMonth = $this->request->getQuery("dayWeekMonth");
        $subOption = (int) $this->request->getQuery("subOption");
        $jinkoInfo = json_decode($this->request->getQuery("jinkoInfo"));

        $optionPlus = 1;
        if($dayWeekMonth == "day")  $optionPlus = $subOption * 1;
        if($dayWeekMonth == "week")  $optionPlus = $subOption * 7;
        if($dayWeekMonth == "month")  $optionPlus = $subOption * 30;

        $group = array(
            "乳幼児" => array('M'=>0, 'F'=>0),
            "小児" => array('M'=>0, 'F'=>0),
            "一般成人" => array('M'=>0, 'F'=>0),
            "特別老人" => array('M'=>0, 'F'=>0)
        );

        foreach($jinkoInfo as $key => $value)  {
            $age = $jinkoInfo[$key]->name;

            switch ($age) {
                case "0歳": 
                    $group["乳幼児"]['M'] = (int) $jinkoInfo[$key]->Male;  
                    $group["乳幼児"]['F'] = (int) $jinkoInfo[$key]->Female;  
                break;
                case "1-2歳": 
                    $group["小児"]['M'] = (int) $group["小児"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["小児"]['F'] = (int) $group["小児"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "3-5歳": 
                    $group["小児"]['M'] = (int) $group["小児"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["小児"]['F'] = (int) $group["小児"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "6-7歳": 
                    $group["小児"]['M'] = (int) $group["小児"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["小児"]['F'] = (int) $group["小児"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "8-9歳": 
                    $group["小児"]['M'] = (int) $group["小児"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["小児"]['F'] = (int) $group["小児"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "10-11歳": 
                    $group["小児"]['M'] = (int) $group["小児"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["小児"]['F'] = (int) $group["小児"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "12-14歳": 
                    $group["小児"]['M'] = (int) $group["小児"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["小児"]['F'] = (int) $group["小児"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "15-17歳": 
                    $group["一般成人"]['M'] = (int) $group["一般成人"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["一般成人"]['F'] = (int) $group["一般成人"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "18-29歳": 
                    $group["一般成人"]['M'] = (int) $group["一般成人"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["一般成人"]['F'] = (int) $group["一般成人"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "30-49歳": 
                    $group["一般成人"]['M'] = (int) $group["一般成人"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["一般成人"]['F'] = (int) $group["一般成人"]['F'] + (int) $jinkoInfo[$key]->Female;  
                break;
                case "50-64歳": 
                    $group["一般成人"]['M'] = (int) $group["一般成人"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["一般成人"]['F'] = (int) $group["一般成人"]['F'] + (int) $jinkoInfo[$key]->Female;
                break;
                case "65-74歳": 
                    $group["一般成人"]['M'] = (int) $group["一般成人"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["一般成人"]['F'] = (int) $group["一般成人"]['F'] + (int) $jinkoInfo[$key]->Female;
                break;
                case "75歳から以上": 
                    $group["特別老人"]['M'] = (int) $group["特別老人"]['M'] + (int) $jinkoInfo[$key]->Male;  
                    $group["特別老人"]['F'] = (int) $group["特別老人"]['F'] + (int) $jinkoInfo[$key]->Female;
                break;
            }
        }

        $selectArr = array(
            'SpecialAgeName','SECTION','GENDER',
            'ENERC_KCAL'=> 'sum(ENERC_KCAL)',
            'WATER'=>'sum(`WATER`)',
            'protein'=>'sum(`protein`)',
            'Lipid'=>'sum(`Lipid`)',
            'carbohydrate'=>'sum(`carbohydrate`)',
            'NA'=>'sum(`NA`)',
            'K'=>'sum(`K`)',
            'CA'=>'sum(`CA`)',
            'P'=>'sum(`P`)',
            'FE'=>'sum(`FE`)',
            'Iodine'=>'sum(`Iodine`)',
            'RETOL'=>'sum(`RETOL`)',
            'CARTBEQ'=>'sum(`CARTBEQ`)',
            'VITA_RAE'=>'sum(`VITA_RAE`)',
            'VITD'=>'sum(`VITD`)',
            'TOCPHA'=>'sum(`TOCPHA`)',
            'TOCPHG'=>'sum(`TOCPHG`)',
            'THIAHCL'=>'sum(`THIAHCL`)',
            'RIBF'=>'sum(`RIBF`)',
            'NIA'=>'sum(`NIA`)',
            'VITC'=>'sum(`VITC`)',
            'NACL_EQ'=>'sum(`NACL_EQ`)'
        );

        $Table = TableRegistry::getTableLocator()->get('nutrients_data');
        $SECTION1F = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'F', 'SECTION'=> 1])->first();
        $SECTION2F = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'F', 'SECTION'=> 2])->first();
        $SECTION3F = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'F', 'SECTION'=> 3])->first();

        $SECTION1M = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'M', 'SECTION'=> 1])->first();
        $SECTION2M = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'M', 'SECTION'=> 2])->first();
        $SECTION3M = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'M', 'SECTION'=> 3])->first();

        $table = '
        <div class="card">
            <div class="card-body listYellowColor">
                <table class="table table-borderless w-100">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">たんぱく質</th>
                            <td>'.$SECTION1F->protein * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->protein * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->protein * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->protein * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->protein * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->protein * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">脂質</th>
                            <td>'.$SECTION1F->Lipid * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->Lipid * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->Lipid * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->Lipid * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->Lipid * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->Lipid * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">炭水化物</th>
                            <td>'.$SECTION1F->carbohydrate * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->carbohydrate * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->carbohydrate * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->carbohydrate * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->carbohydrate * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->carbohydrate * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body listPinkColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">エネルギー</th>
                            <td>'.$SECTION1F->ENERC_KCAL * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->ENERC_KCAL * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->ENERC_KCAL * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->ENERC_KCAL * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->ENERC_KCAL * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->ENERC_KCAL * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">水分</th>
                            <td>'.$SECTION1F->WATER * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->WATER * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->WATER * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->WATER * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->WATER * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->WATER * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">食塩相当量</th>
                            <td>'.$SECTION1F->NACL_EQ * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->NACL_EQ * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->NACL_EQ * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->NACL_EQ * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->NACL_EQ * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->NACL_EQ * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body listBlueColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">無機質</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>

                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">ナトリウム</th>
                            <td>'.$SECTION1F->NA * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->NA * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->NA * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->NA * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->NA * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->NA * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">カリウム</th>
                            <td>'.$SECTION1F->K * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->K * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->K * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->K * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->K * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->K * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">カルシウム</th>
                            <td>'.$SECTION1F->CA * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->CA * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->CA * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->CA * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->CA * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->CA * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">リン</th>
                            <td>'.$SECTION1F->P * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->P * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->P * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->P * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->P * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->P * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">鉄</th>
                            <td>'.$SECTION1F->FE * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->FE * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->FE * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->FE * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->FE * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->FE * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">ヨウ素</th>
                            <td>'.$SECTION1F->Iodine * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION2F->Iodine * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION3F->Iodine * $optionPlus * $group[$SpecialAgeName]['F'].'g</td>
                            <td>'.$SECTION1M->Iodine * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION2M->Iodine * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                            <td>'.$SECTION3M->Iodine * $optionPlus * $group[$SpecialAgeName]['M'].'g</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminA -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンA</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">レチノール</th>
                            <td>'.$SECTION1F->RETOL * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->RETOL * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->RETOL * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->RETOL * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->RETOL * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->RETOL * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">β-カロテン当量</th>
                            <td>'.$SECTION1F->CARTBEQ * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->CARTBEQ * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->CARTBEQ * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->CARTBEQ * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->CARTBEQ * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->CARTBEQ * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">レチノール活性当量</th>
                            <td>'.$SECTION1F->VITA_RAE * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->VITA_RAE * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->VITA_RAE * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->VITA_RAE * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->VITA_RAE * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->VITA_RAE * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminB -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンB</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">ビタミンB1</th>
                            <td>'.$SECTION1F->THIAHCL * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->THIAHCL * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->THIAHCL * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->THIAHCL * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->THIAHCL * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->THIAHCL * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">ビタミンB2</th>
                            <td>'.$SECTION1F->RIBF * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->RIBF * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->RIBF * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->RIBF * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->RIBF * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->RIBF * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">ナイアシン</th>
                            <td>'.$SECTION1F->NIA * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->NIA * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->NIA * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->NIA * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->NIA * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->NIA * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminC -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンC</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"></th>
                            <td>'.$SECTION1F->VITC * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->VITC * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->VITC * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->VITC * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->VITC * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->VITC * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminD -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンD</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"></th>
                            <td>'.$SECTION1F->VITD * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->VITD * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->VITD * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->VITD * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->VITD * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->VITD * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminE -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンE</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">α-トコフェロール</th>
                            <td>'.$SECTION1F->TOCPHA * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->TOCPHA * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->TOCPHA * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->TOCPHA * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->TOCPHA * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->TOCPHA * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                        </tr>
                        <tr>
                            <th scope="row">γ-トコフェロール</th>
                            <td>'.$SECTION1F->TOCPHG * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION2F->TOCPHG * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION3F->TOCPHG * $optionPlus * $group[$SpecialAgeName]['F'].'μg</td>
                            <td>'.$SECTION1M->TOCPHG * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION2M->TOCPHG * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                            <td>'.$SECTION3M->TOCPHG * $optionPlus * $group[$SpecialAgeName]['M'].'μg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><br/>';

        $this->set([
            'nutrients' => trim($table), 
            '_serialize' => true
        ]);
    }

    public function getMenu()  {
        $menuName = $this->request->getQuery("menuName");
        $dayWeekMonth = $this->request->getQuery("dayWeekMonth");
        $subOption = (int) $this->request->getQuery("subOption");

        $optionPlus = 1;
        if($dayWeekMonth == "day")  $optionPlus = $subOption * 1;
        if($dayWeekMonth == "week")  $optionPlus = $subOption * 7;
        if($dayWeekMonth == "month")  $optionPlus = $subOption * 30;

        $connect = ConnectionManager::get('default');
        $menuRows = $connect->query("
            SELECT foodName, oneServingCoefficient * {$optionPlus} as oneServingCoefficients, box, in_one_box FROM menu INNER JOIN foodmaster
            ON menu.foodNumber=foodmaster.foodNumber 
            WHERE menu.name ='{$menuName}'" 
        )->fetchAll('assoc');

        $this->set([
            'getMenu' => $menuRows,
            '_serialize' => true
        ]);
    }

    public function getReqNutrientList()  {
        $menuName = $this->request->getQuery("menuName");
        $SpecialAgeName = $this->request->getQuery("SpecialAgeName");
        $dayWeekMonth = $this->request->getQuery("dayWeekMonth");
        $subOption = (int) $this->request->getQuery("subOption");
        $jinkoInfo = json_decode($this->request->getQuery("jinkoInfo"));

        $optionPlus = 1;
        if($dayWeekMonth == "day")  $optionPlus = $subOption * 1;
        if($dayWeekMonth == "week")  $optionPlus = $subOption * 7;
        if($dayWeekMonth == "month")  $optionPlus = $subOption * 30;

        $connect = ConnectionManager::get('default');
        $foodMaster = $connect->query("
            SELECT 
               SUM(ENERC_KCAL) ENERC_KCAL, SUM(WATER) WATER, SUM(protein) protein, SUM(Lipid) Lipid,
               SUM(carbohydrate) carbohydrate, SUM(NA) NA, SUM(K) K, SUM(CA) CA, SUM(P) P, SUM(FE) FE,
               SUM(Iodine) Iodine, SUM(RETOL) RETOL, SUM(CARTBEQ) CARTBEQ, SUM(VITA_RAE) VITA_RAE,
               SUM(VITD) VITD, SUM(TOCPHA) TOCPHA, SUM(TOCPHG) TOCPHG, SUM(THIAHCL) THIAHCL,
               SUM(RIBF) RIBF, SUM(NIA) NIA, SUM(VITC) VITC, SUM(NACL_EQ) NACL_EQ
            FROM menu INNER JOIN foodmaster
            ON menu.foodNumber=foodmaster.foodNumber 
            WHERE menu.name ='{$menuName}'" 
        )->fetchAll('assoc');

        $selectArr = array(
            'SpecialAgeName','SECTION','GENDER','ENERC_KCAL'=>'AVG(ENERC_KCAL)','WATER'=>'AVG(`WATER`)','protein'=>'AVG(`protein`)',
            'Lipid'=>'AVG(`Lipid`)','carbohydrate'=>'AVG(`carbohydrate`)','NA'=>'AVG(`NA`)','K'=>'AVG(`K`)','CA'=>'AVG(`CA`)',
            'P'=>'AVG(`P`)','FE'=>'AVG(`FE`)','Iodine'=>'AVG(`Iodine`)','RETOL'=>'AVG(`RETOL`)','CARTBEQ'=>'AVG(`CARTBEQ`)',
            'VITA_RAE'=>'AVG(`VITA_RAE`)', 'VITD'=>'AVG(`VITD`)', 'TOCPHA'=>'AVG(`TOCPHA`)','TOCPHG'=>'AVG(`TOCPHG`)',
            'THIAHCL'=>'AVG(`THIAHCL`)','RIBF'=>'AVG(`RIBF`)','NIA'=>'AVG(`NIA`)','VITC'=>'AVG(`VITC`)','NACL_EQ'=>'AVG(`NACL_EQ`)'
        );

        $Table = TableRegistry::getTableLocator()->get('nutrients_data');
        $SECTION1F = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'F', 'SECTION'=> 1])->first();
        $SECTION2F = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'F', 'SECTION'=> 2])->first();
        $SECTION3F = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'F', 'SECTION'=> 3])->first();

        $SECTION1M = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'M', 'SECTION'=> 1])->first();
        $SECTION2M = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'M', 'SECTION'=> 2])->first();
        $SECTION3M = $Table ->find('all')->select($selectArr)->where(['SpecialAgeName'=>$SpecialAgeName, 'GENDER'=>'M', 'SECTION'=> 3])->first();

        function calcOfnutrients(float $arg1, float $arg2) {
            if($arg2 == 0) $arg2 = 1; // tur zuur
            return round(($arg1/$arg2)*100, 1);
        }

        $htmlTable ='
        <div class="card">
            <div class="card-body listYellowColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">合計</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">たんぱく質</th>
                            <td>'.round($foodMaster[0]['protein'], 1).'g</td>
                            <td>'.calcOfnutrients($foodMaster[0]['protein'], $SECTION1F->protein) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['protein'], $SECTION2F->protein) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['protein'], $SECTION3F->protein) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['protein'], $SECTION1M->protein) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['protein'], $SECTION2M->protein) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['protein'], $SECTION3M->protein) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">脂質</th>
                            <td>'.round($foodMaster[0]['Lipid'], 1).'g</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Lipid'], $SECTION1F->Lipid) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Lipid'], $SECTION2F->Lipid) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Lipid'], $SECTION3F->Lipid) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Lipid'], $SECTION1M->Lipid) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Lipid'], $SECTION2M->Lipid) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Lipid'], $SECTION3M->Lipid) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">炭水化物</th>
                            <td>'.round($foodMaster[0]['carbohydrate'], 1).'g</td>
                            <td>'.calcOfnutrients($foodMaster[0]['carbohydrate'], $SECTION1F->carbohydrate) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['carbohydrate'], $SECTION2F->carbohydrate) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['carbohydrate'], $SECTION3F->carbohydrate) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['carbohydrate'], $SECTION1M->carbohydrate) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['carbohydrate'], $SECTION2M->carbohydrate) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['carbohydrate'], $SECTION3M->carbohydrate) * $optionPlus.'%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body listPinkColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">合計</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">エネルギー</th>
                            <td>'.round($foodMaster[0]['ENERC_KCAL'], 1).'kcal</td>
                            <td>'.calcOfnutrients($foodMaster[0]['ENERC_KCAL'], $SECTION1F->ENERC_KCAL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['ENERC_KCAL'], $SECTION2F->ENERC_KCAL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['ENERC_KCAL'], $SECTION3F->ENERC_KCAL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['ENERC_KCAL'], $SECTION1M->ENERC_KCAL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['ENERC_KCAL'], $SECTION2M->ENERC_KCAL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['ENERC_KCAL'], $SECTION3M->ENERC_KCAL) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">水分</th>
                            <td>'.round($foodMaster[0]['WATER'], 1).'g</td>
                            <td>'.calcOfnutrients($foodMaster[0]['WATER'], $SECTION1F->WATER) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['WATER'], $SECTION2F->WATER) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['WATER'], $SECTION3F->WATER) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['WATER'], $SECTION1M->WATER) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['WATER'], $SECTION2M->WATER) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['WATER'], $SECTION3M->WATER) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">食塩相当量</th>
                            <td>'.round($foodMaster[0]['NACL_EQ'], 1).'g</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NACL_EQ'], $SECTION1F->NACL_EQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NACL_EQ'], $SECTION2F->NACL_EQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NACL_EQ'], $SECTION3F->NACL_EQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NACL_EQ'], $SECTION1M->NACL_EQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NACL_EQ'], $SECTION2M->NACL_EQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NACL_EQ'], $SECTION3M->NACL_EQ) * $optionPlus.'%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body listBlueColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">無機質</th>
                            <th scope="col">合計</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">ナトリウム</th>
                            <td>'.round($foodMaster[0]['NA'], 1).'mg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NA'], $SECTION1F->NA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NA'], $SECTION2F->NA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NA'], $SECTION3F->NA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NA'], $SECTION1M->NA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NA'], $SECTION2M->NA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NA'], $SECTION3M->NA) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">カリウム</th>
                            <td>'.round($foodMaster[0]['K'], 1).'mg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['K'], $SECTION1F->K) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['K'], $SECTION2F->K) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['K'], $SECTION3F->K) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['K'], $SECTION1M->K) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['K'], $SECTION2M->K) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['K'], $SECTION3M->K) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">カルシウム</th>
                            <td>'.round($foodMaster[0]['CA'], 1).'mg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CA'], $SECTION1F->CA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CA'], $SECTION2F->CA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CA'], $SECTION3F->CA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CA'], $SECTION1M->CA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CA'], $SECTION2M->CA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CA'], $SECTION3M->CA) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">リン</th>
                            <td>'.round($foodMaster[0]['P'], 1).'mg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['P'], $SECTION1F->P) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['P'], $SECTION2F->P) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['P'], $SECTION3F->P) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['P'], $SECTION1M->P) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['P'], $SECTION2M->P) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['P'], $SECTION3M->P) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">鉄</th>
                            <td>'.round($foodMaster[0]['FE'], 1).'mg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['FE'], $SECTION1F->FE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['FE'], $SECTION2F->FE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['FE'], $SECTION3F->FE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['FE'], $SECTION1M->FE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['FE'], $SECTION2M->FE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['FE'], $SECTION3M->FE) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">ヨウ素</th>
                            <td>'.round($foodMaster[0]['Iodine'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Iodine'], $SECTION1F->Iodine) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Iodine'], $SECTION2F->Iodine) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Iodine'], $SECTION3F->Iodine) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Iodine'], $SECTION1M->Iodine) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Iodine'], $SECTION2M->Iodine) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['Iodine'], $SECTION3M->Iodine) * $optionPlus.'%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminA -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンA</th>
                            <th scope="col">合計</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">レチノール</th>
                            <td>'.round($foodMaster[0]['RETOL'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RETOL'], $SECTION1F->RETOL) * $optionPlus.'%g</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RETOL'], $SECTION2F->RETOL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RETOL'], $SECTION3F->RETOL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RETOL'], $SECTION1M->RETOL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RETOL'], $SECTION2M->RETOL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RETOL'], $SECTION3M->RETOL) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">β-カロテン当量</th>
                            <td>'.round($foodMaster[0]['CARTBEQ'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CARTBEQ'], $SECTION1F->CARTBEQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CARTBEQ'], $SECTION2F->CARTBEQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CARTBEQ'], $SECTION3F->CARTBEQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CARTBEQ'], $SECTION1M->CARTBEQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CARTBEQ'], $SECTION2M->CARTBEQ) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['CARTBEQ'], $SECTION3M->CARTBEQ) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">レチノール活性当量</th>
                            <td>'.round($foodMaster[0]['VITA_RAE'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITA_RAE'], $SECTION1F->VITA_RAE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITA_RAE'], $SECTION2F->VITA_RAE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITA_RAE'], $SECTION3F->VITA_RAE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITA_RAE'], $SECTION1M->VITA_RAE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITA_RAE'], $SECTION2M->VITA_RAE) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITA_RAE'], $SECTION3M->VITA_RAE) * $optionPlus.'%</td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminB -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンB</th>
                            <th scope="col">合計</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">ビタミンB1</th>
                            <td>'.round($foodMaster[0]['THIAHCL'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['THIAHCL'], $SECTION1F->THIAHCL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['THIAHCL'], $SECTION2F->THIAHCL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['THIAHCL'], $SECTION3F->THIAHCL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['THIAHCL'], $SECTION1M->THIAHCL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['THIAHCL'], $SECTION2M->THIAHCL) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['THIAHCL'], $SECTION3M->THIAHCL) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">ビタミンB2</th>
                            <td>'.round($foodMaster[0]['RIBF'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RIBF'], $SECTION1F->RIBF) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RIBF'], $SECTION2F->RIBF) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RIBF'], $SECTION3F->RIBF) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RIBF'], $SECTION1M->RIBF) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RIBF'], $SECTION2M->RIBF) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['RIBF'], $SECTION3M->RIBF) * $optionPlus.'%</td>
                        </tr>
                        <tr>
                            <th scope="row">ナイアシン</th>
                            <td>'.round($foodMaster[0]['NIA'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NIA'], $SECTION1F->NIA).'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NIA'], $SECTION2F->NIA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NIA'], $SECTION3F->NIA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NIA'], $SECTION1M->NIA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NIA'], $SECTION2M->NIA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['NIA'], $SECTION3M->NIA) * $optionPlus.'%</td>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminC -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンC</th>
                            <th scope="col">合計</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"></th>
                            <td>'.round($foodMaster[0]['VITC'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITC'], $SECTION1F->VITC) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITC'], $SECTION2F->VITC) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITC'], $SECTION3F->VITC) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITC'], $SECTION1M->VITC) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITC'], $SECTION2M->VITC) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITC'], $SECTION3M->VITC) * $optionPlus.'%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminD -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンD</th>
                            <th scope="col">合計</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row"></th>
                            <td>'.round($foodMaster[0]['VITD'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITD'], $SECTION1F->VITD) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITD'], $SECTION2F->VITD) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITD'], $SECTION3F->VITD) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITD'], $SECTION1M->VITD) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITD'], $SECTION2M->VITD) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['VITD'], $SECTION3M->VITD) * $optionPlus.'%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminE -->
            <div class="card-body listGreenColor">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ビタミンE</th>
                            <th scope="col">合計</th>
                            <th scope="col">F最低</th>
                            <th scope="col">F推奨</th>
                            <th scope="col">F目標量</th>
                            <th scope="col">M最低</th>
                            <th scope="col">M推奨</th>
                            <th scope="col">M目標量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">α-トコフェロール</th>
                            <td>'.round($foodMaster[0]['TOCPHA'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHA'], $SECTION1F->TOCPHA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHA'], $SECTION2F->TOCPHA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHA'], $SECTION3F->TOCPHA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHA'], $SECTION1M->TOCPHA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHA'], $SECTION2M->TOCPHA) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHA'], $SECTION3M->TOCPHA) * $optionPlus.'%</td>
                        </tr>
                        </tr>
                        <tr>
                            <th scope="row">γ-トコフェロール</th>
                            <td>'.round($foodMaster[0]['TOCPHG'], 1).'μg</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHG'], $SECTION1F->TOCPHG) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHG'], $SECTION2F->TOCPHG) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHG'], $SECTION3F->TOCPHG) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHG'], $SECTION1M->TOCPHG) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHG'], $SECTION2M->TOCPHG) * $optionPlus.'%</td>
                            <td>'.calcOfnutrients($foodMaster[0]['TOCPHG'], $SECTION3M->TOCPHG) * $optionPlus.'%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><br/>';

        $this->set([
            'htmlTable' => $htmlTable,
            '_serialize' => true
        ]);
    }

    public function productFree()  {
        $selectedDepoId = $this->request->getQuery("selectedDepoId");
        $selectedKuName = $this->request->getQuery("selectedKuName");
        $liveAndWork = $this->request->getQuery("liveAndWork");

        $where = [];
        if($liveAndWork == 2)  {
            if($selectedDepoId > 0)  {
                $where['repo_id'] = $selectedDepoId;
            }
    
            if($selectedDepoId == 0 && $selectedKuName !="")  { //herwee aguulah bish duureg songoson bol duuregeer ni
                $where['区'] = $selectedKuName;
            }
        }

        $Table = TableRegistry::getTableLocator()->get('product_free');
        $Items = $Table->find('all')
        ->join([
            'table' => 'population',
            'type' => 'INNER',
            'alias' => 'pp',
            'conditions' => 'product_free.repo_id = pp.id'
        ])
        ->where($where)
        ->order(['good_name'=>'ASC','save_date'=>'ASC','available_count'=>'ASC']);

        $this->set([
            'Items' => $Items,
            '_serialize' => true
        ]);
    }

    public function supportDestinationSearch()  {
        $Table = TableRegistry::getTableLocator()->get('support_destination');
        $Items = $Table->find('all')->order(['deadline_support'=>'ASC','number_of_people_this_time'=>'DESC']);

        $this->set([
            'Items' => $Items,
            '_serialize' => true
        ]);
    }

    public function productCheck()  {
        $selectedRowIds = json_decode($this->request->getQuery("selectedRowIds"));
        $Table = TableRegistry::getTableLocator()->get('product_free');
        $Items = $Table->find('all')->where(['id IN' => $selectedRowIds]);

        $this->set([
            'Items' => $Items,
            '_serialize' => true
        ]);
    }

    // ------------ tools
    public function nutrientsdata()  {
        $Table = TableRegistry::getTableLocator()->get('nutrients_data');
        $Items = $Table->find('all');

        $this->set([
            'Items' => $Items,
            '_serialize' => true
        ]);
    }

    public function bigdata()  {
        // $Table = TableRegistry::getTableLocator()->get('population');
        // $Items = $Table->find('all')->limit(1000)->offset(66000);

        // $this->set([
        //     'Items' => $Items,
        //     '_serialize' => true
        // ]);
    }

    public function xyupdate()  {
        $placeName = $this->request->getQuery("placeName"); 
        $long = $this->request->getQuery("long");
        $lat = $this->request->getQuery("lat");

        $connect = ConnectionManager::get('default');
        $query = $connect->query("UPDATE `population` SET `latitude`='$lat', `longitude`='$long' WHERE `町`='$placeName'")->fetchAll('assoc');
        // $query = $connect->query("SELECT * FROM population WHERE  `町`='$placeName' AND `latitude`=$lat")->fetchAll('assoc');

        $this->set([
            'query' => $query,
            'data' => $placeName,
            'long' => $long,
            'lat' => $lat,
            '_serialize' => true
        ]);
    }

}
