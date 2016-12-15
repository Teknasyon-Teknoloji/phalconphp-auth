<?php
namespace Teknasyon\Phalcon\Auth\Interfaces;

use Teknasyon\Phalcon\Auth\Interfaces\User;

/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 06/12/2016
 * Time: 16:59
 */

interface UserManager {
    
    /**
     * @param $id
     * @return User
     */
    public function findUserById($id);

    /**
     * @param array $credentials
     * @return User
     */
    public function findUserByCredentials(array $credentials);


    public function updateAuthToken(User $user,$token);
}