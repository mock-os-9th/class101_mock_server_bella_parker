<?php
require 'function.php';

const JWT_SECRET_KEY = "2d4nj21b9r20werioclrn023iowernlnv480o2n";

$res = (Object)Array();
$orderArr= (Object)Array();
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
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);

            $order_info = getOrderInfo($user_idx);
            $order_cnt = count($order_info);

            for($i = 0; $i<$order_cnt; $i++){
                $prod_purchase_idx = $order_info[$i]['prod_purchase_idx'];
                $order_detail = orderDetail($prod_purchase_idx);
                $order_info[$i]['order_detail'] = $order_detail;
            }

            $res->result->order_info = $order_info;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*case "getDetailOrder":
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
            $purchase_idx=$vars['purchase_idx'];
            $idx_type=$_GET['type']; //product인지 package인지
            $res->result->date=getDate($purchase_idx);
            $res->result->purchase = getPurchase($purchase_idx);
            $res->result->delivery = getDelivery($purchase_idx);
            $res->result->options = getOptions($purchase_idx);

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;*/

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
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $res->result->popularity_10 = getPopular10($user_idx);
            $res->result->digital5= getDigital5($user_idx);
            $res->result->DIY5= getDIY5($user_idx);
            $res->result->art5 = getArt5($user_idx);
            $res->result->new_prod = getNewprod($user_idx);
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
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $res->result = prodByCategory($ctg_type,$user_idx);
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

        case "getDetailProduct":
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
            $product_idx=$vars['product_idx'];
            $res->result->product = getProductInfo($product_idx,$user_idx);
            $res->result->total = sumInfo($vars['product_idx']);
            $res->result->details = reviewsByProd3($product_idx);
            $res->result->question=getQuestions($product_idx);
            $cnt=count($res->result->question);
            for($i=0;$i<$cnt;$i++){
                $q_idx=$res->result->question[$i]['question_idx'];
                $res->result->question[$i]['comments']=getComments($q_idx);
            }

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "newQuestion":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $product_idx = $vars['product_idx'];
            if(!isValidProdIdx($product_idx)){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if($req->contents==null){
                $res->code = 210;
                $res->message = "내용을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $contents=$req->contents;
            $photo=$req->photo;
            /*if($photo==null){
                $photo="null";
            }*/

            newQuestion($user_idx, $product_idx, $contents, $photo);
            $res->code = 100;
            $res->message = "문의 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "newComment":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $question_idx = $vars['question_idx'];
            if(!isValidQIdx($question_idx)){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if($req->contents==null){
                $res->code = 210;
                $res->message = "내용을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $contents=$req->contents;
            $photo=$req->photo;
            /*if($photo==null){
                $photo="null";
            }*/

            newComment($user_idx, $question_idx, $contents, $photo);
            $res->code = 100;
            $res->message = "댓글 달기 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}