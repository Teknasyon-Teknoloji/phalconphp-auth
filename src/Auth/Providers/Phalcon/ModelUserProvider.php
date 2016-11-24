<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 22/11/2016
 * Time: 10:47
 */

namespace Teknasyon\Phalcon\Auth\Providers\Phalcon;


use Teknasyon\Phalcon\Auth\Interfaces\UserProvider;

/**
 * Class ModelUserProvider
 * @package Teknasyon\Phalcon\Auth\Providers\Phalcon
 * @author Ilyas Serter <ilyasserter@teknasyon.com>
 */
class ModelUserProvider implements UserProvider
{

    protected $model;

    /**
     * ModelUserProvider constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if(!class_exists($options['model'])) {
            throw new \InvalidArgumentException('Model does not exist: ' . $options['model']);
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
}