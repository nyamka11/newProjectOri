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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()  {
        $this->paginate = [
            'contain' => [],
        ];
        $users = $this->paginate($this->Users);
        $this->set(['users' => $users, '_serialize' => true]);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)  {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        $this->set(['user' => $user, '_serialize' => true]);
    }

    function getLastQuery() {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);
        return $lastLog['query'];
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

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getdata()  {
        $district = $this->request->getQuery("district");
        $town = $this->request->getQuery("town");
        $type = $this->request->getQuery("type");
        $Table = TableRegistry::getTableLocator()->get('population');

        /** big zone start */
        if($type == "big")  {
            if($district == "init")  {
                $this->set([
                    'data_0_10' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 0, '年齢 <=' => 10]),
                    'data_11_20' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 11, '年齢 <=' => 20]), 
                    'data_21_30' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 21, '年齢 <=' => 30]), 
                    'data_31_40' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 31, '年齢 <=' => 40]), 
                    'data_41_50' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 41, '年齢 <=' => 50]), 
                    'data_51_60' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 51, '年齢 <=' => 60]),
                    'data_61_70' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 61, '年齢 <=' => 70]),
                    'data_71_80' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 71, '年齢 <=' => 80]),
                    'data_81_90' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 81, '年齢 <=' => 90]),
                    'data_91_100' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 91, '年齢 <=' => 100]),
                    'data_101_110' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 101, '年齢 <=' => 110]),
                    'data_111_120' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 111, '年齢 <=' => 120]),
                    '_serialize' => true
                ]);
            }
            else  {
                $this->set([
                    'data_0_10' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 0, '年齢 <=' => 10]),
                    'data_11_20' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 11, '年齢 <=' => 20]), 
                    'data_21_30' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 21, '年齢 <=' => 30]), 
                    'data_31_40' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 31, '年齢 <=' => 40]), 
                    'data_41_50' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 41, '年齢 <=' => 50]), 
                    'data_51_60' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 51, '年齢 <=' => 60]),
                    'data_61_70' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 61, '年齢 <=' => 70]),
                    'data_71_80' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 71, '年齢 <=' => 80]),
                    'data_81_90' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 81, '年齢 <=' => 90]),
                    'data_91_100' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 91, '年齢 <=' => 100]),
                    'data_101_110' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 101, '年齢 <=' => 110]),
                    'data_111_120' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['区'=>$district, '年齢 >=' => 111, '年齢 <=' => 120]),
                    '_serialize' => true
                ]);
            }
        }
        /** big zone end */

        if($type == "small")  {
            if($district == "init")  {
                $this->set([
                    'data_0_10' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 0, '年齢 <=' => 10]),
                    'data_11_20' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 11, '年齢 <=' => 20]), 
                    'data_21_30' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 21, '年齢 <=' => 30]), 
                    'data_31_40' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 31, '年齢 <=' => 40]), 
                    'data_41_50' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 41, '年齢 <=' => 50]), 
                    'data_51_60' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 51, '年齢 <=' => 60]),
                    'data_61_70' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 61, '年齢 <=' => 70]),
                    'data_71_80' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 71, '年齢 <=' => 80]),
                    'data_81_90' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 81, '年齢 <=' => 90]),
                    'data_91_100' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 91, '年齢 <=' => 100]),
                    'data_101_110' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 101, '年齢 <=' => 110]),
                    'data_111_120' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)'])->where(['年齢 >=' => 111, '年齢 <=' => 120]),
                    '_serialize' => true
                ]);
            }
            else  {
                $this->set([
                    'data_0_10' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 0, '年齢 <=' => 10]),
                    'data_11_20' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 11, '年齢 <=' => 20]), 
                    'data_21_30' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 21, '年齢 <=' => 30]), 
                    'data_31_40' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 31, '年齢 <=' => 40]), 
                    'data_41_50' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 41, '年齢 <=' => 50]), 
                    'data_51_60' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 51, '年齢 <=' => 60]),
                    'data_61_70' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 61, '年齢 <=' => 70]),
                    'data_71_80' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 71, '年齢 <=' => 80]),
                    'data_81_90' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 81, '年齢 <=' => 90]),
                    'data_91_100' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 91, '年齢 <=' => 100]),
                    'data_101_110' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 101, '年齢 <=' => 110]),
                    'data_111_120' => $Table ->find()->select(['Male' => 'SUM(男)', 'Female' => 'SUM(女)',"市", "区", "町", "郵便番号"])->where(['町'=>$town, '年齢 >=' => 111, '年齢 <=' => 120]),
                    '_serialize' => true
                ]);
            }
        }
    }

    public function getNutrients()  {
        $SpecialAgeName = $this->request->getQuery("SpecialAgeName");
        $dayWeekMonth = $this->request->getQuery("dayWeekMonth");
        $subOption = (int) $this->request->getQuery("subOption");

        $optionPlus = 1;
        if($dayWeekMonth == "day")  $optionPlus = $subOption * 1;
        if($dayWeekMonth == "week")  $optionPlus = $subOption * 7;
        if($dayWeekMonth == "month")  $optionPlus = $subOption * 30;


        // echo $optionPlus;
        // exit();

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
            <div class="card-body">
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
                            <th scope="row">たんぱく質</th>
                            <td>'.$SECTION1F->protein * $optionPlus.'g</td>
                            <td>'.$SECTION2F->protein * $optionPlus.'g</td>
                            <td>'.$SECTION3F->protein * $optionPlus.'g</td>
                            <td>'.$SECTION1M->protein * $optionPlus.'g</td>
                            <td>'.$SECTION2M->protein * $optionPlus.'g</td>
                            <td>'.$SECTION3M->protein * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">脂質</th>
                            <td>'.$SECTION1F->Lipid * $optionPlus.'g</td>
                            <td>'.$SECTION2F->Lipid * $optionPlus.'g</td>
                            <td>'.$SECTION3F->Lipid * $optionPlus.'g</td>
                            <td>'.$SECTION1M->Lipid * $optionPlus.'g</td>
                            <td>'.$SECTION2M->Lipid * $optionPlus.'g</td>
                            <td>'.$SECTION3M->Lipid * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">炭水化物</th>
                            <td>'.$SECTION1F->carbohydrate * $optionPlus.'g</td>
                            <td>'.$SECTION2F->carbohydrate * $optionPlus.'g</td>
                            <td>'.$SECTION3F->carbohydrate * $optionPlus.'g</td>
                            <td>'.$SECTION1M->carbohydrate * $optionPlus.'g</td>
                            <td>'.$SECTION2M->carbohydrate * $optionPlus.'g</td>
                            <td>'.$SECTION3M->carbohydrate * $optionPlus.'g</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
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
                            <td>'.$SECTION1F->ENERC_KCAL * $optionPlus.'g</td>
                            <td>'.$SECTION2F->ENERC_KCAL * $optionPlus.'g</td>
                            <td>'.$SECTION3F->ENERC_KCAL * $optionPlus.'g</td>
                            <td>'.$SECTION1M->ENERC_KCAL * $optionPlus.'g</td>
                            <td>'.$SECTION2M->ENERC_KCAL * $optionPlus.'g</td>
                            <td>'.$SECTION3M->ENERC_KCAL * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">水分</th>
                            <td>'.$SECTION1F->WATER * $optionPlus.'g</td>
                            <td>'.$SECTION2F->WATER * $optionPlus.'g</td>
                            <td>'.$SECTION3F->WATER * $optionPlus.'g</td>
                            <td>'.$SECTION1M->WATER * $optionPlus.'g</td>
                            <td>'.$SECTION2M->WATER * $optionPlus.'g</td>
                            <td>'.$SECTION3M->WATER * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">食塩相当量</th>
                            <td>'.$SECTION1F->NACL_EQ * $optionPlus.'g</td>
                            <td>'.$SECTION2F->NACL_EQ * $optionPlus.'g</td>
                            <td>'.$SECTION3F->NACL_EQ * $optionPlus.'g</td>
                            <td>'.$SECTION1M->NACL_EQ * $optionPlus.'g</td>
                            <td>'.$SECTION2M->NACL_EQ * $optionPlus.'g</td>
                            <td>'.$SECTION3M->NACL_EQ * $optionPlus.'g</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
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
                            <td>'.$SECTION1F->NA * $optionPlus.'g</td>
                            <td>'.$SECTION2F->NA * $optionPlus.'g</td>
                            <td>'.$SECTION3F->NA * $optionPlus.'g</td>
                            <td>'.$SECTION1M->NA * $optionPlus.'g</td>
                            <td>'.$SECTION2M->NA * $optionPlus.'g</td>
                            <td>'.$SECTION3M->NA * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">カリウム</th>
                            <td>'.$SECTION1F->K * $optionPlus.'g</td>
                            <td>'.$SECTION2F->K * $optionPlus.'g</td>
                            <td>'.$SECTION3F->K * $optionPlus.'g</td>
                            <td>'.$SECTION1M->K * $optionPlus.'g</td>
                            <td>'.$SECTION2M->K * $optionPlus.'g</td>
                            <td>'.$SECTION3M->K * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">カルシウム</th>
                            <td>'.$SECTION1F->CA * $optionPlus.'g</td>
                            <td>'.$SECTION2F->CA * $optionPlus.'g</td>
                            <td>'.$SECTION3F->CA * $optionPlus.'g</td>
                            <td>'.$SECTION1M->CA * $optionPlus.'g</td>
                            <td>'.$SECTION2M->CA * $optionPlus.'g</td>
                            <td>'.$SECTION3M->CA * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">リン</th>
                            <td>'.$SECTION1F->P * $optionPlus.'g</td>
                            <td>'.$SECTION2F->P * $optionPlus.'g</td>
                            <td>'.$SECTION3F->P * $optionPlus.'g</td>
                            <td>'.$SECTION1M->P * $optionPlus.'g</td>
                            <td>'.$SECTION2M->P * $optionPlus.'g</td>
                            <td>'.$SECTION3M->P * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">鉄</th>
                            <td>'.$SECTION1F->FE * $optionPlus.'g</td>
                            <td>'.$SECTION2F->FE * $optionPlus.'g</td>
                            <td>'.$SECTION3F->FE * $optionPlus.'g</td>
                            <td>'.$SECTION1M->FE * $optionPlus.'g</td>
                            <td>'.$SECTION2M->FE * $optionPlus.'g</td>
                            <td>'.$SECTION3M->FE * $optionPlus.'g</td>
                        </tr>
                        <tr>
                            <th scope="row">ヨウ素</th>
                            <td>'.$SECTION1F->Iodine * $optionPlus.'g</td>
                            <td>'.$SECTION2F->Iodine * $optionPlus.'g</td>
                            <td>'.$SECTION3F->Iodine * $optionPlus.'g</td>
                            <td>'.$SECTION1M->Iodine * $optionPlus.'g</td>
                            <td>'.$SECTION2M->Iodine * $optionPlus.'g</td>
                            <td>'.$SECTION3M->Iodine * $optionPlus.'g</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminA -->
            <div class="card-body">
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
                            <td>'.$SECTION1F->RETOL * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->RETOL * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->RETOL * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->RETOL * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->RETOL * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->RETOL * $optionPlus.'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">β-カロテン当量</th>
                            <td>'.$SECTION1F->CARTBEQ * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->CARTBEQ * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->CARTBEQ * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->CARTBEQ * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->CARTBEQ * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->CARTBEQ * $optionPlus.'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">レチノール活性当量</th>
                            <td>'.$SECTION1F->VITA_RAE * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->VITA_RAE * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->VITA_RAE * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->VITA_RAE * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->VITA_RAE * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->VITA_RAE * $optionPlus.'μg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminB -->
            <div class="card-body">
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
                            <td>'.$SECTION1F->THIAHCL * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->THIAHCL * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->THIAHCL * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->THIAHCL * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->THIAHCL * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->THIAHCL * $optionPlus.'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">ビタミンB2</th>
                            <td>'.$SECTION1F->RIBF * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->RIBF * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->RIBF * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->RIBF * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->RIBF * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->RIBF * $optionPlus.'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">ナイアシン</th>
                            <td>'.$SECTION1F->NIA * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->NIA * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->NIA * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->NIA * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->NIA * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->NIA * $optionPlus.'μg</td>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminC -->
            <div class="card-body">
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
                            <td>'.$SECTION1F->VITC * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->VITC * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->VITC * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->VITC * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->VITC * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->VITC * $optionPlus.'μg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminD -->
            <div class="card-body">
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
                            <td>'.$SECTION1F->VITD * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->VITD * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->VITD * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->VITD * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->VITD * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->VITD * $optionPlus.'μg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3"> <!-- vitaminE -->
            <div class="card-body">
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
                            <td>'.$SECTION1F->TOCPHA * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->TOCPHA * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->TOCPHA * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->TOCPHA * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->TOCPHA * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->TOCPHA * $optionPlus.'μg</td>
                        </tr>
                        </tr>
                        <tr>
                            <th scope="row">γ-トコフェロール</th>
                            <td>'.$SECTION1F->TOCPHG * $optionPlus.'μg</td>
                            <td>'.$SECTION2F->TOCPHG * $optionPlus.'μg</td>
                            <td>'.$SECTION3F->TOCPHG * $optionPlus.'μg</td>
                            <td>'.$SECTION1M->TOCPHG * $optionPlus.'μg</td>
                            <td>'.$SECTION2M->TOCPHG * $optionPlus.'μg</td>
                            <td>'.$SECTION3M->TOCPHG * $optionPlus.'μg</td>
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
}
