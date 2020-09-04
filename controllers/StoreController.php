<?php
require 'function.php';

const JWT_SECRET_KEY = "2d4nj21b9r20werioclrn023iowernlnv480o2n";

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
        case "reviewByProd":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            if(!isValidProdIdx($vars['product_idx'])){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!hasReview($vars['product_idx'])){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "첫번째 리뷰를 작성하세요!";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result->general = sumInfo($vars['product_idx']);
            $res->result->detail = reviewsByProd($vars['product_idx']);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getOrders":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result->total_cnt = getOrderCount($user_idx);
            //$res->result->orders = getOrderDetail($user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getProducts":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $res->result->popularity_10 = getPopular10();
            $res->result->digital5= getDigital5();
            $res->result->DIY5= getDIY5();
            $res->result->art5 = getArt5();
            $res->result->new_prod = getNewprod();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getCoupons":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result->total_cnt = getCouponCount($user_idx);
            $res->result->coupons = getCouponDetail($user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "prodByCtg":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $ctg_type = $_GET['type'];
            if(!($ctg_type == "EASY DIY" || $ctg_type == "굿즈" || $ctg_type == "미술재료" || $ctg_type == "공예재료" || $ctg_type == "디지털기기/ACC" || $ctg_type == "악기/음악" || $ctg_type == "헬스/뷰티/ACC" || $ctg_type == "인테리어/소품" || $ctg_type == "푸드/키친" || $ctg_type == "문구/도서" )){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "잘못된 카테고리입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                //addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $res->result = prodByCategory($ctg_type);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getMypage":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $res->result->mypage = getMypage($user_idx);
            $res->result->myclass= getMyclass($user_idx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getDetailProd":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $product_idx=$vars['product_idx'];
            $res->result->product = getProductInfo($product_idx);
            $res->result->total = sumInfo($vars['product_idx']);
            $res->result->details = reviewsByProd3($vars['product_idx']);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
