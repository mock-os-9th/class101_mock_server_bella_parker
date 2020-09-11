<?php
require 'function.php';

const JWT_SECRET_KEY = "2d4nj21b9r20werioclrn023iowernlnv480o2n";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;

        case "getKakaoInfo":
            http_response_code(200);
            $access_token=$req->access_token;
            $profile_img=$req->profile_img;
            if(!isValidKakaoUser($req->id, $req->email)){

                addKakaoUser($profile_img,$req->id,$req->email);
                $res->code = 201;
                $res->message = "등록된 유저가 아니므로 회원가입 합니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            //카카오유저들.
            $jwt = getKakaoJWToken($req->email, $req->id, JWT_SECRET_KEY);
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
           // $id=$data->id;
            $email = $data->email;
            $date = $data->date;
            updateCurrentLogin($email, $date);

            $res->result = new \stdClass();
            $res->result->jwt = $jwt;
            $res->code = 100;
            $res->message = "로그인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
