<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 09:58
 */

namespace Teknasyon\Phalcon\Auth\Interfaces;

/**
 * Interface UserProvider
 * @package Teknasyon\Phalcon\Auth\Interfaces
 */
interface UserProvider {

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

}