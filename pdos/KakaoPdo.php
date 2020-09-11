<?php
function isValidKakaoUser($id, $email){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM User WHERE user_id=? and user_email=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$id, $email]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function addKakaoUser($profile_img,$id,$email){
    $pdo = pdoSqlConnect();
    $login_type='kakao';
    $query = "insert into User (user_email,user_id,profile_img,login_type) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$email,$id,$profile_img,$login_type]);

    $st = null;
    $pdo = null;
}