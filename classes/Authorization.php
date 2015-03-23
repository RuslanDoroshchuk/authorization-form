<?php
namespace testAuthForm;

/**
 * Class for user authorization
 */
class Authorization
{
    /**
     * prepered query for select information about user by login/password
     * @access private
     * @var PDOStatement
     */
    private $qUserCheck;     
    
    /**
     * prepered query for updating user last visit
     * @access private
     * @var PDOStatement
     */
    private $qUpdLastVisit;
    
    /**
     * constructor
     * create table users and add user if table not exist
     * prepare queries for future using
     */
    public function __construct()
    {
        $this->createUserTable();
        $this->qUserCheck = $this->queryUserCheck();
        $this->qUpdLastVisit = $this->queryUpdLastVisit();
    }
    
    /**
     * prepare select information about user by login/password
     * @uses Database::getInstance() for access to DB
     * @return PDOStatement 
     */
    private function queryUserCheck()
    {
        $db = Database::getInstance();
        return $db->prepare("SELECT id, last_visit FROM users "
                    . "WHERE email = :email AND password = :password");
    }
    
    /**
     * prepare query for updating user last visit
     * @uses Database::getInstance() for access to DB
     * @return PDOStatement 
     */
    private function queryUpdLastVisit()
    {
        $db = Database::getInstance();
        return $db->prepare('UPDATE users SET last_visit = NOW()'
                    .' WHERE id = :user_id');
    }
    
    /**
     * get user password by email
     * @param string $email user email 
     * @uses Database::getInstance() for access to DB
     * @return array
     */
    private function getUser($email)
    {
        $db = Database::getInstance();
        $q = $db->prepare("SELECT id, password, last_visit FROM users "
                    . "WHERE email = ?");
        $q->execute(array($email));
        if ($q->rowCount()){
            return $q->fetch(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }
    
    /**
     * create table 'users' and add test user if table 'users' not exist
     * @uses Database::getInstance() for access to DB
     * @return void
     */
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
            $pass = '1111';
            $randString = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,32);
            $passwordsr = md5($pass.$randString);
            $password = $passwordsr.":".$randString;
            $db->query("INSERT INTO users (email, password, last_visit) "
                        . "VALUES ('mail@mail.ua', '$password', "
                        . "'0000-00-00 00:00:00')");
        }        
    }
    
    /**
     * check login and password
     * 
     * @param string $login user email
     * @param string $pass user password
     * @param string $code code from captha
     * @return json
     */
    public function checkUser($login, $pass, $code)
    {
        $rez = array();  // rezult
        
        if (($_SESSION['wrong_pass'] >= AUTH_ATTEMPTS) && ($code != $_SESSION["simple-php-captcha"]['code'])){
        
            $rez['answer'] = 'NO';
            $rez['msg'] = 'Captcha is not correct';
        
        } else {
            
            $user = $this->getUser($login);
            
            if ($user){
                $randStr = substr($user['password'], -32);
                $passClear =md5($pass.$randStr);
                $password = $passClear.":".$randStr;
                $passOK = ($password == $user['password']) ? true : false;
            }
            
            // if login and pass correct
            if ($user && $passOK){
                
                // update last login
                $this->qUpdLastVisit->execute(array('user_id' => $user['id']));
                
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
    
    /**
     * logout event
     * @return void
     */
    public function logout()
    {
        $_SESSION['user_id'] = 0;
    }
    
    /**
     * get logined status at start
     * @return json
     */
    public function getAuthInfo()
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