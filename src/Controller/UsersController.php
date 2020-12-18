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

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */

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

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
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

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
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
}
