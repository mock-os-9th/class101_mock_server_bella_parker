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

        case "getClasses":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $ctg_type = $_GET['type'];
            if(!($ctg_type == "전체" || $ctg_type == "크리에이티브" || $ctg_type == "커리어" || $ctg_type == "머니")){
                $res->code = 201;
                $res->message = "잘못된 카테고리입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $result['top10_class'] = getTopClass($user_idx,$ctg_type);
            $result['new_class'] = getNewClass($user_idx,$ctg_type);
            $result['not_opened_class'] = getNotOpenedClass($user_idx,$ctg_type);

            $res->result = $result;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getUpdatedClasses":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $ctg_type = $_GET['type'];
            if(!($ctg_type == "전체" || $ctg_type == "크리에이티브" || $ctg_type == "커리어" || $ctg_type == "머니")){
                $res->code = 201;
                $res->message = "잘못된 카테고리입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $page_num = $vars['page_num'];


            $res->result = getUpdatedClass($user_idx, $ctg_type, ($page_num-1)*2);
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        case "getClassByClassIdx":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $class_idx = $vars['class_idx'];
            if(!isValidClassIdx($class_idx)){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $res->result = getClassByClassIdx($user_idx, $class_idx);
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "updateClassLike":
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $class_idx = $vars['class_idx'];
            if(!isValidClassIdx($class_idx)){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $message = "";
            // 1) 목록에 존재하는가?
            // 존재안하면 새로 추가
            if(!isClassLikeExist($user_idx,$class_idx)){
                addClassLike($user_idx,$class_idx);
                $code = 100;
                $message = "좋아요 추가";
            }
            // 존재하면 토글
            else{
                $like_status = getClassLikeStatus($user_idx,$class_idx);
                if($like_status == 'N'){
                    updateClassLike($user_idx,$class_idx, 'Y');
                    $code = 100;
                    $message = "좋아요 추가";
                }
                else{
                    updateClassLike($user_idx,$class_idx, 'N');
                    $code = 101;
                    $message = "좋아요 취소";
                }
            }
            $res->result['like_cnt'] = getClassLikeCnt($class_idx);
            $res->result['like_status'] = getClassLikeStatus($user_idx, $class_idx);
            $res->code = $code;
            $res->message = $message;
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
