<?php

//READ
function test()
{
    $pdo = pdoSqlConnect();
    $query = "select * from Class;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//READ
function testDetail($testNo)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT * FROM Test WHERE no = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$testNo]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}


function testPost($name)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO Test (name) VALUES (?);";

    $st = $pdo->prepare($query);
    $st->execute([$name]);

    $st = null;
    $pdo = null;

}


function isValidUser($email, $pw){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM User WHERE user_email= ? AND user_pwd = ?) AS exist;";


    $st = $pdo->prepare($query);
    $st->execute([$email,$pw]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

function addUser($user_name, $user_email, $user_pwd, $phone)
{
    $pdo = pdoSqlConnect();
    $login_type='local';
    $query = "insert into User (user_name, user_email, user_pwd, user_phone, nickname,login_type) values (?,?,?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_name, $user_email, $user_pwd, $phone, $user_name,$login_type]);

    $st = null;
    $pdo = null;

}

function isEmailExist($user_email){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS (select * FROM User WHERE user_email = ?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$user_email]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
function isPhoneExist($user_phone){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS (select * FROM User WHERE user_phone = ?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$user_phone]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}



