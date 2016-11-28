<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 10:11
 */

namespace Teknasyon\Phalcon\Auth\Interfaces;

use Phalcon\DiInterface;

/**
 * Interface AuthDriver
 * @package Teknasyon\Phalcon\Auth\Interfaces
 */
interface AuthDriver {

    /**
     * AuthDriver constructor.
     * @param array $config
     * @param UserProvider $userProvider
     * @param DiInterface $di
     */
    public function __construct(array $config, UserProvider $userProvider, DiInterface $di);

    /**
     * @param array $credentials
     * @return bool
     */
    public function attempt(array $credentials = []) : bool;

    /**
     * @return bool
     */
    public function check() : bool;

    /**
     * @param bool $fresh
     * @return mixed
     */
    public function user($fresh = false);

    /**
     * @param User $user
     * @return mixed
     */
    public function login(User $user);

    /**
     * @return mixed
     */
    public function logout();
}