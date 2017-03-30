<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 13:35
 */

namespace Teknasyon\Phalcon\Auth\UserManagers;


use Phalcon\Db\Adapter\Pdo as DbAdapter;
use Teknasyon\Phalcon\Auth\Interfaces\UserManager;
use Teknasyon\Phalcon\Auth\User;


/**
 * Class PhalconDb
 * @package Teknasyon\Phalcon\Auth\UserManagers
 * @author Ilyas Serter <ilyasserter@teknasyon.com>
 */
class PhalconDb implements UserManager  {

    /**
     * @var DbAdapter
     */
    private $db;
    /**
     * @var array
     */
    private $options;

    /**
     * DbUserProvider constructor.
     * @param DbAdapter $db
     * @param array $options
     * @throws \Exception
     */
    public function __construct(DbAdapter $db, array $options)
    {
        if(!isset($options['table'])) {
            throw new \Exception('DB User provider options require a target table.');
        }

        if(!isset($options['identifierColumn'])) {
            throw new \Exception('DB User provider options require an identifier column name.');
        }

        if(!isset($options['passwordColumn'])) {
            throw new \Exception('DB User provider options require a password column name.');
        }

        if(!isset($options['tokenColumn'])) {
            $options['tokenColumn'] = 'auth_token';
        }

        $this->db = $db;
        $this->options = $options;
    }

    /**
     * @param $id
     * @return User
     */
    public function findUserById($id)
    {
        $sql = 'SELECT * FROM ' . $this->options['table'] . ' WHERE ' . $this->options['identifierColumn'] .  ' = ? LIMIT 1';
        $result = $this->db->query($sql,[$id]);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
        $result = $result->fetch();
        if($result) {
            return new User($result,$this->options['identifierColumn'],$this->options['passwordColumn']);
        }
    }

    /**
     * @param array $credentials
     * @return User
     */
    public function findUserByCredentials(array $credentials)
    {
        $where = [];
        $bindings = [];
        foreach ($credentials as $key => $val) {
            if($key != $this->options['passwordColumn']) {
                $where[] = $this->db->escapeIdentifier($key) . ' = ?';
                $bindings[] = $val;
            }
        }

        $sql = 'SELECT * FROM ' . $this->db->escapeIdentifier($this->options['table']) . ' WHERE ' . implode(' AND ',$where) . ' LIMIT 1';
        $result = $this->db->query($sql,$bindings);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
        $result = $result->fetch();
        if($result) {
            return new User($result,$this->options['identifierColumn'],$this->options['passwordColumn']);
        }
    }

    public function updateAuthToken(\Teknasyon\Phalcon\Auth\Interfaces\User $user, $token)
    {
        // TODO: Implement updateAuthToken() method.
    }
}