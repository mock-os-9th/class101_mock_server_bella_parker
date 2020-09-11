<?php
require 'function.php';

const JWT_SECRET_KEY = "2d4nj21b9r20werioclrn023iowernlnv480o2n";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        /*
         * API No. 0
         * API Name : JWT 유효성 검사 테스트 API
         * 마지막 수정 날짜 : 19.04.25
         */
        case "validateJwt":
            // jwt 유효성 검사
            //$jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            $jwt = $req->jwt;
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);

            if (!(isValidHeader($jwt, JWT_SECRET_KEY) && $data->date == getCurrentLogin($data->email))) {
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            if (isJWTExpired($data->email))
            {
                $res->code = 202;
                $res->message = "로그인이 만료됐습니다. 다시 로그인해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            http_response_code(200);
            $res->code = 100;
            $res->message = "유효한 토큰입니다.";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 1
         * API Name : JWT 생성 테스트 API (로그인)
         * 마지막 수정 날짜 : 19.04.25
         */
        case "createJwt":
            // jwt 유효성 검사
            http_response_code(200);

            if(!isValidUser($req->email, $req->pw)){
                $res->code = 201;
                $res->message = "이메일 또는 비밀번호를 확인해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }

            //페이로드에 맞게 다시 설정 요함
            $jwt = getJWToken($req->email, $req->pw, JWT_SECRET_KEY);
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->email;
            $date = $data->date;
            updateCurrentLogin($email, $date);

            $res->result = new \stdClass();
            $res->result->jwt = $jwt;
            $res->code = 100;
            $res->message = "로그인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "addUser":
            http_response_code(200);

            $name = $req->name;
            $email = $req->email;
            $password = $req->password;
            $pw_chk = $req->pw_chk;
            $phone = $req->phone;

            if(isEmailExist($email)){
                $res->code = 210;
                $res->message = "이미 등록된 이메일입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL) ){
                $res->code = 211;
                $res->message = "잘못된 이메일 형식.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!is_numeric($phone) || strlen($phone) != 11){
                $res->code = 220;
                $res->message = "전화번호 확인 필요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(isPhoneExist($phone)){
                $res->code = 221;
                $res->message = "이미 등록된 전화번호입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $num = preg_match('/[0-9]/u', $password);
            $eng = preg_match('/[a-z]/u', $password);
            $spe = preg_match("/[\!\@\#\$\%\^\&\*]/u",$password);

            if(strlen($password)<9 ||strlen($password)>20 || $num == 0 || $eng == 0 || $spe == 0){
                $res->code = 230;
                $res->message = "비밀번호를 확인해주세요(영문, 숫자, 특수문자 혼합 9~20자리).";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if($password != $pw_chk){
                $res->code = 231;
                $res->message = "비밀번호가 일치하지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            addUser($name, $email, $password, $phone); // body(request) 안에 있는 name 받아오기
            $jwt = getJWToken($email, $password, JWT_SECRET_KEY);
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $email = $data->email;
            $date = $data->date;
            updateCurrentLogin($email, $date);
            $res->jwt = new \stdClass();
            $res->jwt = $jwt;
            $res->code = 100;
            $res->message = "회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "sendFCM":
            // jwt 유효성 검사
            http_response_code(200);
            $key = "AAAAW-KJdQQ:APA91bHVR7eggLnuFU9_wHa-BxnWTv1VXzFVuYZ2TYyC8Y2eb6ZRArPgqQLQJK2Nlid9EIfdPqq1budDKpORxf6UfKZYGzk7GULqau88ycaI448EBQNdytdh1ly3MOx36MJeIgi4uy6K";
            $fcmToken = getFcmFromUser();
            $data = $req->data;
            $notification = $req->notification;
            for($i = 0; $i<count($fcmToken); $i++){
                sendFcm($fcmToken[$i]['fcm_token'], $data, $notification, $key);
            }

            $res->code = 100;
            $res->message = "push 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
