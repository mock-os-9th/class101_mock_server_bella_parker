<?php

//DB 정보
function pdoSqlConnect()
{
    try {
        $DB_HOST = "class101.c66ai5gyrco2.ap-northeast-2.rds.amazonaws.com";
        $DB_NAME = "test";
        $DB_USER = "admin";
        $DB_PW = "class101password";
        $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PW);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
}
