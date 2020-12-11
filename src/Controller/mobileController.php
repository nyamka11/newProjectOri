<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Utility\Security;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MobileController extends AppController  {
    public function initialize(): void  {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function index()  {
        $userTable = TableRegistry::getTableLocator()->get('Users');
        $users = $userTable ->find('all');
        // $this->set([
        //     'data'=>$users,
        //     'status' => 1,
        //     '_serialize' => ['users']
        // ]);
        $this->set('articles', $userTable->paginate());
        $this->set('_serialize', ['articles', 'comments']);
    }

    public function view($id)  {
        $recipe = $this->Recipes->get($id);
        $this->set('recipe', $recipe);
        $this->viewBuilder()->setOption('serialize', ['recipe']);
    }

    public function add()  {
        $this->request->allowMethod(['post', 'put']);
        $recipe = $this->Recipes->newEntity($this->request->getData());
        if ($this->Recipes->save($recipe)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'recipe' => $recipe,
        ]);
        $this->viewBuilder()->setOption('serialize', ['recipe', 'message']);
    }

    public function edit($id)  {
        $this->request->allowMethod(['patch', 'post', 'put']);
        $recipe = $this->Recipes->get($id);
        $recipe = $this->Recipes->patchEntity($recipe, $this->request->getData());
        if ($this->Recipes->save($recipe)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'recipe' => $recipe,
        ]);
        $this->viewBuilder()->setOption('serialize', ['recipe', 'message']);
    }

    public function delete($id)  {
        $this->request->allowMethod(['delete']);
        $recipe = $this->Recipes->get($id);
        $message = 'Deleted';
        if (!$this->Recipes->delete($recipe)) {
            $message = 'Error';
        }
        $this->set('message', $message);
        $this->viewBuilder()->setOption('serialize', ['message']);
    }
}
