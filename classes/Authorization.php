<?php
namespace testAuthForm\classes;

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
            echo 'Creating table<br/>';
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
    
    // check login and password
    public static function checkUser($login, $pass, $code)
    {
        $rez = array();  // rezult
        
        if (($_SESSION['wrong_pass'] >= AUTH_ATTEMPTS) && ($code != $_SESSION["simple-php-captcha"]['code'])){
        
            $rez['answer'] = 'NO';
            $rez['msg'] = 'Captcha is not correct';
        
        } else {
            
            $db = Database::getInstance();
            $db->qUserCheck->execute(array('email' => $login, 'password' => md5($pass)));
            
            // if login and pass correct
            if ($db->qUserCheck->rowCount()){
                
                $user = $db->qUserCheck->fetch(\PDO::FETCH_ASSOC);
                // update last login
                $db->qUpdLastVisit->execute(array('user_id' => $user['id']));
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['last_visit'] = date('d/m/Y H:i', strtotime($user['last_visit']));
                $_SESSION['wrong_pass'] = 0;
                if ($user['last_visit']=='0000-00-00 00:00:00'){
                    $lastVisitMsg = "This is your first visit";    
                } else {
                    $lastVisitMsg = "last visit: ".date('d/m/Y H:i', strtotime($user['last_visit']));
                }
                $rez['answer'] = 'OK';
                $rez['msg'] = "Hello! Your id: ".$user['id'].", ".$lastVisitMsg;

            } else {    // login/password not correct
                
                if (isset($_SESSION['wrong_pass']) && ($_SESSION['wrong_pass']>0)){
                    $_SESSION['wrong_pass']++;
                    if ($_SESSION['wrong_pass'] >= AUTH_ATTEMPTS){
                        // show captcha
                        $rez['captcha'] = 1;               
                    } else {
                        $rez['captcha'] = 0;               
                    }
                } else {
                    $_SESSION['wrong_pass'] = 1;
                }
                
                $rez['answer'] = 'NO';
                $rez['msg'] = 'INCORRECT LOGIN or PASSWORD';
                            
            }
        }
        
        // echo results
        echo json_encode($rez);
    }
    
    
    // logout
    public static function logout()
    {
        $_SESSION['user_id'] = 0;
    }
    
    // get logined status at start
    public static function getAuthInfo()
    {
        $authData = array();
        $authData['userId'] = $_SESSION['user_id'] ? $_SESSION['user_id'] : 0;
        $authData['captcha'] = ($_SESSION['wrong_pass']>=AUTH_ATTEMPTS) ? 1 : 0;
        if ($authData['captcha']) {
            require_once("/libs/simple-php-captcha/simple-php-captcha.php");
            $_SESSION['simple-php-captcha'] = simple_php_captcha();
            $authData['captha_img'] = $_SESSION['simple-php-captcha']['image_src'];
        }
        // message for user
        $msg = "Please, enter to site";
        if ($_SESSION['user_id']) {
            $msg = "Hello! Your id: ".$_SESSION['user_id'].", last visit: ".$_SESSION['last_visit'];
        }
        $authData['msg'] = $msg;
        
        echo json_encode($authData);
    }
}