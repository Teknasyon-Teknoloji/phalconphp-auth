<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 10:04
 */

namespace Teknasyon\Phalcon\Auth\Interfaces;

/**
 * Interface User
 * @package Teknasyon\Interfaces\Auth
 */
interface User {

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getPassword();


}