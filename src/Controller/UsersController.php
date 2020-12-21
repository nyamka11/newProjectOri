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
        // $town = $this->request->getQuery("town");
        // $type = $this->request->getQuery("type");

        $Table = TableRegistry::getTableLocator()->get('nutrients');
        $nutrients = $Table ->find('all')->where(['SpecialAgeName'=>'乳幼児']);

        $dbData = [];
        foreach ($nutrients as $item)  {
            $dbData['protein_'.$item->GENDER.$item->SECTION] = $item->protein;
            $dbData['Lipid_'.$item->GENDER.$item->SECTION] = $item->Lipid;
            $dbData['carbohydrate_'.$item->GENDER.$item->SECTION] = $item->carbohydrate;
            $dbData['ENERC_KCAL_'.$item->GENDER.$item->SECTION] = $item->ENERC_KCAL;
            $dbData['WATER_'.$item->GENDER.$item->SECTION] = $item->WATER;
            $dbData['NACL_EQ_'.$item->GENDER.$item->SECTION] = $item->NACL_EQ;
            $dbData['NA_'.$item->GENDER.$item->SECTION] = $item->NA;
            $dbData['K_'.$item->GENDER.$item->SECTION] = $item->K;
            $dbData['CA_'.$item->GENDER.$item->SECTION] = $item->CA;
            $dbData['P_'.$item->GENDER.$item->SECTION] = $item->P;
            $dbData['FE_'.$item->GENDER.$item->SECTION] = $item->FE;
            $dbData['Iodine_'.$item->GENDER.$item->SECTION] = $item->Iodine;
            $dbData['RETOL_'.$item->GENDER.$item->SECTION] = $item->RETOL;
            $dbData['CARTBEQ_'.$item->GENDER.$item->SECTION] = $item->CARTBEQ;
            $dbData['VITA_RAE_'.$item->GENDER.$item->SECTION] = $item->VITA_RAE;

            
        }

        // debug($dbData);

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
                            <td>'.$dbData['protein_F1'].'g</td>
                            <td>'.$dbData['protein_F2'].'g</td>
                            <td>'.$dbData['protein_F3'].'g</td>
                            <td>'.$dbData['protein_M1'].'g</td>
                            <td>'.$dbData['protein_M2'].'g</td>
                            <td>'.$dbData['protein_M3'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">脂質</th>
                            <td>'.$dbData['Lipid_F1'].'g</td>
                            <td>'.$dbData['Lipid_F2'].'g</td>
                            <td>'.$dbData['Lipid_F3'].'g</td>
                            <td>'.$dbData['Lipid_M1'].'g</td>
                            <td>'.$dbData['Lipid_M2'].'g</td>
                            <td>'.$dbData['Lipid_M3'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">炭水化物</th>
                            <td>'.$dbData['carbohydrate_F1'].'g</td>
                            <td>'.$dbData['carbohydrate_F2'].'g</td>
                            <td>'.$dbData['carbohydrate_F3'].'g</td>
                            <td>'.$dbData['carbohydrate_M1'].'g</td>
                            <td>'.$dbData['carbohydrate_M2'].'g</td>
                            <td>'.$dbData['carbohydrate_M3'].'g</td>
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
                            <td>'.$dbData['ENERC_KCAL_F1'].'kcal</td>
                            <td>'.$dbData['ENERC_KCAL_F2'].'kcal</td>
                            <td>'.$dbData['ENERC_KCAL_F3'].'kcal</td>
                            <td>'.$dbData['ENERC_KCAL_M1'].'kcal</td>
                            <td>'.$dbData['ENERC_KCAL_M2'].'kcal</td>
                            <td>'.$dbData['ENERC_KCAL_M3'].'kcal</td>
                        </tr>
                        <tr>
                            <th scope="row">水分</th>
                            <td>'.$dbData['WATER_F1'].'g</td>
                            <td>'.$dbData['WATER_F2'].'g</td>
                            <td>'.$dbData['WATER_F3'].'g</td>
                            <td>'.$dbData['WATER_M1'].'g</td>
                            <td>'.$dbData['WATER_M2'].'g</td>
                            <td>'.$dbData['WATER_M3'].'g</td>
                        </tr>
                        <tr>
                            <th scope="row">食塩相当量</th>
                            <td>'.$dbData['NACL_EQ_F1'].'g</td>
                            <td>'.$dbData['NACL_EQ_F2'].'g</td>
                            <td>'.$dbData['NACL_EQ_F3'].'g</td>
                            <td>'.$dbData['NACL_EQ_M1'].'g</td>
                            <td>'.$dbData['NACL_EQ_M2'].'g</td>
                            <td>'.$dbData['NACL_EQ_M3'].'g</td>
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
                            <td>'.$dbData['NA_F1'].'mg</td>
                            <td>'.$dbData['NA_F2'].'mg</td>
                            <td>'.$dbData['NA_F3'].'mg</td>
                            <td>'.$dbData['NA_M1'].'mg</td>
                            <td>'.$dbData['NA_M2'].'mg</td>
                            <td>'.$dbData['NA_M3'].'mg</td>
                        </tr>
                        <tr>
                            <th scope="row">カリウム</th>
                            <td>'.$dbData['K_F1'].'mg</td>
                            <td>'.$dbData['K_F2'].'mg</td>
                            <td>'.$dbData['K_F3'].'mg</td>
                            <td>'.$dbData['K_M1'].'mg</td>
                            <td>'.$dbData['K_M2'].'mg</td>
                            <td>'.$dbData['K_M3'].'mg</td>
                        </tr>
                        <tr>
                            <th scope="row">カルシウム</th>
                            <td>'.$dbData['CA_F1'].'mg</td>
                            <td>'.$dbData['CA_F2'].'mg</td>
                            <td>'.$dbData['CA_F3'].'mg</td>
                            <td>'.$dbData['CA_M1'].'mg</td>
                            <td>'.$dbData['CA_M2'].'mg</td>
                            <td>'.$dbData['CA_M3'].'mg</td>
                        </tr>
                        <tr>
                            <th scope="row">リン</th>
                            <td>'.$dbData['P_F1'].'mg</td>
                            <td>'.$dbData['P_F2'].'mg</td>
                            <td>'.$dbData['P_F3'].'mg</td>
                            <td>'.$dbData['P_M1'].'mg</td>
                            <td>'.$dbData['P_M2'].'mg</td>
                            <td>'.$dbData['P_M3'].'mg</td>
                        </tr>
                        <tr>
                            <th scope="row">鉄</th>
                            <td>'.$dbData['FE_M1'].'mg</td>
                            <td>'.$dbData['FE_F2'].'mg</td>
                            <td>'.$dbData['FE_F3'].'mg</td>
                            <td>'.$dbData['FE_M1'].'mg</td>
                            <td>'.$dbData['FE_M2'].'mg</td>
                            <td>'.$dbData['FE_M3'].'mg</td>
                        </tr>
                        <tr>
                            <th scope="row">ヨウ素</th>
                            <td>'.$dbData['Iodine_M1'].'μg</td>
                            <td>'.$dbData['Iodine_F2'].'μg</td>
                            <td>'.$dbData['Iodine_F3'].'μg</td>
                            <td>'.$dbData['Iodine_M1'].'μg</td>
                            <td>'.$dbData['Iodine_M2'].'μg</td>
                            <td>'.$dbData['Iodine_M3'].'μg</td>
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
                            <td>'.$dbData['RETOL_M1'].'μg</td>
                            <td>'.$dbData['RETOL_F2'].'μg</td>
                            <td>'.$dbData['RETOL_F3'].'μg</td>
                            <td>'.$dbData['RETOL_M1'].'μg</td>
                            <td>'.$dbData['RETOL_M2'].'μg</td>
                            <td>'.$dbData['RETOL_M3'].'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">β-カロテン当量</th>
                            <td>'.$dbData['CARTBEQ_M1'].'μg</td>
                            <td>'.$dbData['CARTBEQ_F2'].'μg</td>
                            <td>'.$dbData['CARTBEQ_F3'].'μg</td>
                            <td>'.$dbData['CARTBEQ_M1'].'μg</td>
                            <td>'.$dbData['CARTBEQ_M2'].'μg</td>
                            <td>'.$dbData['CARTBEQ_M3'].'μg</td>
                        </tr>
                        <tr>
                            <th scope="row">レチノール活性当量</th>
                            <td>'.$dbData['VITA_RAE_M1'].'μg</td>
                            <td>'.$dbData['VITA_RAE_F2'].'μg</td>
                            <td>'.$dbData['VITA_RAE_F3'].'μg</td>
                            <td>'.$dbData['VITA_RAE_M1'].'μg</td>
                            <td>'.$dbData['VITA_RAE_M2'].'μg</td>
                            <td>'.$dbData['VITA_RAE_M3'].'μg</td>
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
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                        </tr>
                        <tr>
                            <th scope="row">ビタミンB2</th>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                        </tr>
                        <tr>
                            <th scope="row">ナイアシン</th>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                        </tr>
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
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
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
                            <td>μg</td>
                            <td>μg</td>
                            <td>μg</td>
                            <td>μg</td>
                            <td>μg</td>
                            <td>μg</td>
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
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                        </tr>
                        <tr>
                            <th scope="row">γ-トコフェロール</th>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                            <td>mg</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>';

        $this->set(['nutrients' => $table, '_serialize' => true]);
    }
}
