<?php

class Authorization
{
    public function __construct()
    {
        $this->createUserTable();
    }
    
    // create user table if not exist
    private function createUserTable()
    {
        $db = Database::getInstance();
        
        // check existing table users
        $statement = "SELECT * FROM information_schema.tables
                      WHERE table_schema = 'test' AND table_name = 'users'
                      LIMIT 1;";
                
        $q = $db->query($statement);
        
        // if table not exist - create table and add user
        if (!($q->rowCount())){
            // 1. Create table
            $db->query("CREATE TABLE users (
                            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            email VARCHAR(50) NOT NULL,
                            password VARCHAR(255) NOT NULL,
                            last_visit TIMESTAMP
                            )");
            // 2. Add test user
            $db->query("INSERT INTO users (email, password, last_visit) "
                        . "VALUES ('mail@mail.ua', md5('1111'), "
                        . "'0000-00-00 00:00:00')");
        }       
    }
    
    // message for  user
    public function writeHello()
    {
        $msg = "Please, enter to site";
        if (isset($_SESSION['user_id'])) {
            $msg = "Hello! Your id: ".$_SESSION['user_id'].", last visit: ".$_SESSION['last_visit'];
        }
        return $msg;
    }

    // check login and password
    public static function checkUser($login, $pass)
    {
        $db = Database::getInstance();
        
        $q = $db->query("SELECT id, last_visit FROM users "
                . "WHERE email = '$login' AND password = md5('$pass')");
	
	// if login and pass correct
	if ($q->rowCount()){
            $user = $q->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['last_visit'] = date('d/m/Y H:i', strtotime($user['last_visit']));
            $_SESSION['wrong_pass'] = 0;
            // update last login
            $q2 = $db->query('UPDATE users SET last_visit = "'.date('Y-d-m H:i:s').'"');
            if ($user['last_visit']=='0000-00-00 00:00:00'){
                $lastVisitMsg = "This is your first visit";    
            } else {
                $lastVisitMsg = "last visit: ".date('d/m/Y H:i', strtotime($user['last_visit']));
            }
            echo "Hello! Your id: ".$user['id'].", ".$lastVisitMsg;
	} else {
            if (isset($_SESSION['wrong_pass']) && ($_SESSION['wrong_pass']>0)){
                $_SESSION['wrong_pass']++;
                if ($_SESSION['wrong_pass']>=AUTH_ATTEMPTS){
                    echo "CAPTCHA";
                }
            } else {
                $_SESSION['wrong_pass'] = 1;
            }
            echo "INCORRECT LOGIN or PASSWORD";
	}
    }
    
    
    // logout
    public static function logout()
    {
        $_SESSION['user_id'] = 0;
    }
}