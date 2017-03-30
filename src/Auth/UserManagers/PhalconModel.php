<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 22/11/2016
 * Time: 10:47
 */

namespace Teknasyon\Phalcon\Auth\UserManagers;


use Phalcon\Mvc\Model;
use Teknasyon\Phalcon\Auth\Interfaces\User;
use Teknasyon\Phalcon\Auth\Interfaces\UserManager;

/**
 * Class PhalconModel
 * @package Teknasyon\Phalcon\Auth\UserManagers
 * @author Ilyas Serter <ilyasserter@teknasyon.com>
 */
class PhalconModel implements UserManager
{

    protected $model;
    protected $authTokenColumn = 'auth_token';

    /**
     * ModelUserProvider constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if(!class_exists($options['model'])) {
            throw new \InvalidArgumentException('Model does not exist: ' . $options['model']);
        }

        if(isset($options['authTokenColumn'])) {
            $this->authTokenColumn = $options['authTokenColumn'];
        }

        $this->model = $options['model'];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findUserById($id)
    {
        return call_user_func($this->model . '::findFirstById',$id);
    }

    /**
     * @param array $credentials
     * @return mixed
     */
    public function findUserByCredentials(array $credentials)
    {
        $query = call_user_func_array($this->model . '::query', []);
        $bindings = [];
        foreach ($credentials as $key => $val) {
            if($key != 'password') {
                $query->andWhere("$key = :$key:");
                $bindings[$key] = $val;
            }
        }
        $query->bind($bindings);
        return $query->limit(1)->execute()->getFirst();
    }

    public function updateAuthToken(User $user, $token)
    {
        if($user instanceof Model) {
            return $user->save([$this->authTokenColumn => $token]);
        }

        return false;
    }
}