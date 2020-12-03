<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property int $company_id
 * @property int $author_id
 * @property string $level
 * @property string $username
 * @property string $password
 * @property string $email
 * @property int $verified
 * @property string $phone
 * @property string $token
 * @property \Cake\I18n\FrozenTime $createDate
 * @property \Cake\I18n\FrozenTime $updateDate
 * @property \Cake\I18n\FrozenTime $deleteDate
 *
 * @property \App\Model\Entity\Company $company
 * @property \App\Model\Entity\Author $author
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'company_id' => true,
        'author_id' => true,
        'level' => true,
        'firstname' => true,
        'lastname' => true,
        'username' => true,
        'password' => true,
        'email' => true,
        'verified' => true,
        'phone' => true,
        'token' => true,
        'createDate' => true,
        'updateDate' => true,
        'deleteDate' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password',
        'token',
    ];

    protected function _setPassword($password) {
        return (new DefaultPasswordHasher)->hash($password);
    }
}
