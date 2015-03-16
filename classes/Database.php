<?php
namespace testAuthForm\classes;

class Database 
{
    private $_db;
    static $_instance;
    public $qUserCheck;         // prepered query for checking user login/password
    public $qUpdLastVisit;      // prepered query for updating user last visit
    
    private function __construct()
    {
        $this->_db = new \PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
        $this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->qUserCheck = $this->queryUserCheck();
        $this->qUpdLastVisit = $this->queryUpdLastVisit();
    }

    private function __clone(){}

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function query($sql)
    {
        return $this->_db->query($sql);
    }
    
    // use PDO prepare
    public function prepare($sql)
    {
        return $this->_db->prepare($sql);
    }
    
    // prepare select for checkind user login/password
    private function queryUserCheck()
    {
        return $this->_db->prepare("SELECT id, last_visit FROM users "
                    . "WHERE email = :email AND password = :password");
    }
    
    // prepare query for updating user last visit
    private function queryUpdLastVisit()
    {
        return $this->_db->prepare('UPDATE users SET last_visit = NOW()'
                    .' WHERE id = :user_id');
    }
    
}
