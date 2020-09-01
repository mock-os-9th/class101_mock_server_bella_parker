<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
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

        /*
         * API No. 5
         * API Name : 테스트 API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "getStore":
            http_response_code(200);
            $res->result->popuarity_10 = getPopular10();
            $res->result->digital5= getDigital5();
            $res->result->DIY5= getDIY5();
            $res->result->art5 = getArt5();
            $res->result->new_prod = getNewprod();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            //addErrorLogs($errorLogs, $res, $req);
            break;


        case "getClasses":
            http_response_code(200);
            $ctg_type = $_GET['type'];
            if(!($ctg_type == "전체" || $ctg_type == "크리에이티브" || $ctg_type == "커리어" || $ctg_type == "머니")){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "잘못된 카테고리입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $result['top_class'] = getTopClass($ctg_type);
            $result['new_class'] = getNewClass($ctg_type);
            $res->result = $result;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
         * API No. 7
         * API Name : 테스트 API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "getNotOpenedClass":
            http_response_code(200);
            $ctg_type = $_GET['type'];
            if(!($ctg_type == "전체" || $ctg_type == "크리에이티브" || $ctg_type == "커리어" || $ctg_type == "머니")){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "잘못된 카테고리입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $res->result = getNotOpenedClass($ctg_type);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
