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
     * @param UserManager $userManager
     * @param DiInterface $di
     */
    public function __construct(array $config, UserManager $userManager, DiInterface $di);

    /**
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function attempt(array $credentials = [],bool $remember = false) : bool;

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
     * @param bool $remember
     * @return mixed
     */
    public function login(User $user,bool $remember);

    /**
     * @return mixed
     */
    public function logout();
}