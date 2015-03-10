<?php
session_start();

$con = mysql_connect('localhost','test',"test")or die(mysql_error);
mysql_select_db('test');
mysql_query('SET CHARACTER SET utf8',$con);

//перевірка на існування таблиці
$q = mysql_query("SELECT * 
                FROM information_schema.tables
                WHERE table_schema = 'test' 
                        AND table_name = 'users'
                LIMIT 1;
                ");

//якщо таблиця не виявлена - створення таблиці і додання користувача
if (!($q && mysql_num_rows($q))){
    // 1. Створити таблицю
    $q2 = mysql_query("CREATE TABLE users (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    email VARCHAR(50) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    last_visit TIMESTAMP
                    )");
    // 2. Додати тестового користувача
    $q3 = mysql_query("INSERT INTO users (email, password, last_visit) VALUES ('mail@mail.ua', md5('1111'), '0000-00-00 00:00:00')");
}

require_once 'templates/index.php';