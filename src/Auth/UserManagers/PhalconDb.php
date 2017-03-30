<?php
/**
 * Created by PhpStorm.
 * User: iserter
 * Date: 21/11/2016
 * Time: 13:35
 */

namespace Teknasyon\Phalcon\Auth\UserManagers;


use Phalcon\Db;
use Phalcon\Db\Adapter\Pdo as DbAdapter;
use Teknasyon\Phalcon\Auth\Interfaces\User as UserInterface;
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
    private $table = 'users';
    private $identifierColumn = 'id';
    private $passwordColumn = 'password';
    private $authTokenColumn = 'auth_token';

    /**
     * DbUserProvider constructor.
     * @param DbAdapter $db
     * @param array $options
     * @throws \Exception
     */
    public function __construct(DbAdapter $db, array $options)
    {
        if(isset($options['table'])) {
            $this->table = $options['table'];
        }

        if(isset($options['identifierColumn'])) {
            $this->identifierColumn = $options['identifierColumn'];
        }

        if(isset($options['passwordColumn'])) {
            $this->passwordColumn = $options['passwordColumn'];
        }

        if(isset($options['authTokenColumn'])) {
            $this->authTokenColumn = $options['authTokenColumn'];
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
        $sql = 'SELECT * FROM ' . $this->db->escapeIdentifier($this->table)
                . ' WHERE ' . $this->db->escapeIdentifier($this->identifierColumn) .  ' = ? LIMIT 1';
        $result = $this->db->query($sql,[$id]);
        $result->setFetchMode(Db::FETCH_ASSOC);
        $result = $result->fetch();
        if($result) {
            return new User($result,
                $this->identifierColumn,
                $this->passwordColumn,
                $this->authTokenColumn);
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
            if($key != $this->passwordColumn) {
                $where[] = $this->db->escapeIdentifier($key) . ' = ?';
                $bindings[] = $val;
            }
        }

        $sql = 'SELECT * FROM ' . $this->db->escapeIdentifier($this->table) . ' WHERE ' . implode(' AND ',$where) . ' LIMIT 1';
        $result = $this->db->query($sql,$bindings);
        $result->setFetchMode(Db::FETCH_ASSOC);
        $result = $result->fetch();
        if($result) {
            return new User($result,
                $this->identifierColumn,
                $this->passwordColumn,
                $this->authTokenColumn
            );
        }
    }

    public function updateAuthToken(UserInterface $user, $token)
    {
        $sql = 'UPDATE ' . $this->db->escapeIdentifier($this->table) . ' SET '
            . $this->db->escapeIdentifier($this->authTokenColumn).' = ? WHERE '
            . $this->db->escapeIdentifier($this->identifierColumn) .  ' = ?';

        return $this->db->execute($sql,[$token,$user->getId()]);
    }
}