<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\CompaniesTable&\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\AuthorsTable&\Cake\ORM\Association\BelongsTo $Authors
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        // $this->belongsTo('Companies', [
        //     'foreignKey' => 'company_id',
        //     'joinType' => 'INNER',
        // ]);
        // $this->belongsTo('Authors', [
        //     'foreignKey' => 'author_id',
        //     'joinType' => 'INNER',
        // ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('level')
            ->notEmptyString('level');

        $validator
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->scalar('firstname')
            ->maxLength('firstname', 100)
            ->requirePresence('firstname', 'create')
            ->notEmptyString('firstname');

        $validator
            ->scalar('lastname')
            ->maxLength('lastname', 100)
            ->requirePresence('lastname', 'create')
            ->notEmptyString('lastname');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->notEmptyString('password');

        $validator
            ->sameAs("password_match","password","Password Match feiled!");

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('verified')
            ->notEmptyString('verified');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 50)
            ->notEmptyString('phone');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->notEmptyString('token');

        $validator
            ->dateTime('createDate')
            ->notEmptyDateTime('createDate');

        $validator
            ->dateTime('updateDate')
            ->notEmptyDateTime('updateDate');

        $validator
            ->dateTime('deleteDate')
            ->notEmptyDateTime('deleteDate');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        // $rules->add($rules->existsIn(['company_id'], 'Companies'));
        // $rules->add($rules->existsIn(['author_id'], 'Authors'));

        return $rules;
    }
}
