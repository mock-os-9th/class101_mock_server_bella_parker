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
                //$res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            if($vars['product_idx']==null){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidProdIdx($vars['product_idx'])){
                // $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!hasReview($vars['product_idx'])){
                // $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "첫번째 리뷰를 작성하세요!";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result->general = sumInfo($vars['product_idx']);
            $res->result->detail = reviewsByProd($vars['product_idx']);
            // $res->isSuccess = TRUE;
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
            if(empty($order_info)){
                $res->result->order_info ="주문 목록이 없습니다.";
                $res->code = 100;
                $res->message = "조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
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

        case "getDetailOrder":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $purchase_idx=$vars['prod_purchase_idx'];
            if($purchase_idx==null){
                $res->code = 200;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(!isValidPurchaseIdx($purchase_idx)){
                $res->code = 200;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result->date=getDateStatus($purchase_idx);
            $res->result->purchase = getPurchaseDetail($purchase_idx);
            $res->result->delivery = getDeliveryInfo($purchase_idx);
            $res->result->options = getOptions($purchase_idx);

            //$res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getProducts":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
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
            // $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res,JSON_NUMERIC_CHECK);
            break;

        case "getCoupons":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result->total_cnt = getCouponCount($user_idx);
            $res->result->coupons = getCouponDetail($user_idx);
            // $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "prodByCtg":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $ctg_type = $_GET['type'];
            $ctg_option=$_GET['option'];
            if(!($ctg_type == "EASY DIY" || $ctg_type == "굿즈" || $ctg_type == "미술재료" || $ctg_type == "공예재료" || $ctg_type == "디지털기기/ACC" || $ctg_type == "악기/음악" || $ctg_type == "헬스/뷰티/ACC" || $ctg_type == "인테리어/소품" || $ctg_type == "푸드/키친" || $ctg_type == "문구/도서" )){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "잘못된 카테고리입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                //addErrorLogs($errorLogs, $res, $req);
                return;
            }
            if($ctg_option==0 ||$ctg_option==1 ||$ctg_option==2){
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $res->result = prodByCategory($ctg_type,$user_idx,$ctg_option);
            // $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res,JSON_NUMERIC_CHECK);
            break;
            }
            else{
                $res->code = 201;
                $res->message = "잘못된 옵션입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                //addErrorLogs($errorLogs, $res, $req);
                return;
            }

        case "getMypage":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result->mypage = getMypage($user_idx);
            $classcnt=getClassCount($user_idx);
            $res->result->mypage[0]['order_cnt']="주문내역 ".($res->result->mypage[0]['order_cnt']+$classcnt[0]['order_cnt'])."개";

            $res->result->myclass= getMyclass($user_idx);
            // $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getDetailProduct":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            if (!isValidProdIdx($vars['product_idx'])) {
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효한 인덱스가 아닙니다.";
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
            if(empty($res->result->total)){
                $res->result->total='첫번째 리뷰를 작성해보세요!';
                $res->result->details='';
            }else {
                $res->result->total['avg_star'] = $res->result->total['avg_star'] . '점';
            }

            if(empty($res->result->question)){
                $res->result->question='문의 내역이 없습니다.';
                // $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "조회 성공";
                echo json_encode($res,JSON_NUMERIC_CHECK);
                break;
            }
            $cnt=count($res->result->question);
            for($i=0;$i<$cnt;$i++){
                $q_idx=$res->result->question[$i]['question_idx'];
                $res->result->question[$i]['comments']=getComments($q_idx);
                if(empty($res->result->question[$i]['comments'])){
                    $res->result->question[$i]['comments']='';
                }
            }

            // $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res,JSON_NUMERIC_CHECK);
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



        case "updateProdLike":
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
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $prod_idx = $vars['product_idx'];

            if(!isValidProdIdx($prod_idx)){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            if(!hasProdLike($user_idx,$prod_idx)) {
                addProdLike($user_idx, $prod_idx);
                $code = 100;
                $message = "찜 추가";
            }
            else{
                $like_status = getProdLikeStatus($user_idx,$prod_idx);
                if($like_status == 'N'){
                    $like_status='Y';
                    updateProdLike($user_idx,$prod_idx,$like_status);
                    $code = 100;
                    $message = "찜 추가";
                }
                else if($like_status == 'Y'){
                    $like_status='N';
                    updateProdLike($user_idx,$prod_idx,$like_status);
                    $code = 101;
                    $message = "찜 취소";
                }
            }
            $res->code = $code;
            $res->message = $message;
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "newOrder":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $orderIdx=getMaxProdOrderIdx()['idx'];
            //echo $orderIdx;
            $orders=$req->orders;
            //echo json_encode($orders[0], JSON_NUMERIC_CHECK);
            $option_count=count($orders);

            //echo $option_count;
            for($i=0;$i<$option_count;$i++){
                $option_idx = $orders[$i]->option_idx;
                $count = $orders[$i]->count;

                newProdcutPurchase($orderIdx, $user_idx, $option_idx, $count);
            }

            $total_price="";
            $total_discount="";
            $total_delivery="";
            $total_origin_price="";
            for($i=0;$i<$option_count;$i++){
                $option_idx=$orders[$i]->option_idx;
                $total_origin_price+=getSumPrice($option_idx)['option_price']*$orders[$i]->count;
                $total_discount+=getSumPrice($option_idx)['discount']*$orders[$i]->count;
                $total_delivery+=getDeliveryCharge($option_idx)['delivery_charge'];


            }

            $total_price=$total_origin_price-$total_discount+$total_delivery;







            //배송정보
            $recipient=$req->recipient;
            $r_phone=$req->r_phone;
            $r_address=$req->r_address;
            $memo=$req->memo;

            if($recipient==null ||$r_phone==null || $r_address==null){
                $res->code = 202;
                $res->message = "올바른 배송 정보를 입력해주세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            newProductDelivery($orderIdx,$recipient,$r_phone,$r_address,$memo);

            //detail 결제정보
            $payment_type=$req->payment_type;
            $coupon_idx=$req->coupon_idx;
            if($coupon_idx!=null){
                if(!isValidUserCoupon($coupon_idx,$user_idx)){
                    $res->code = 201;
                    $res->message = "사용가능한 쿠폰이 아닙니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                $coupon_discount=getCouponPrice($coupon_idx)['coupon_price'];
                $total_price=$total_price-$coupon_discount;
            }
            else{
                $coupon_idx=0;
            }




            newPurchaseDetail($orderIdx,$payment_type,$coupon_idx,$total_price,$total_discount,$total_delivery,$total_origin_price);

            // $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "결제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getLikes":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result->total_cnt = getLikeCount($user_idx);
            $res->result->likes = getLikeInfo($user_idx);
            // $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "newWork":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $class_idx=$req->class_idx;
            if(!isTakingClass($user_idx,$class_idx)){
                $res->code = 200;
                $res->message = "수강 중인 클래스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $w_photo=$req->w_photo;
            $w_content=$req->w_content;
            newWork($user_idx,$class_idx,$w_content,$w_photo);
            // $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "newWorkComment":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $w_post_idx=$vars['w_post_idx'];
            if(!isValidWorkPostIdx($w_post_idx)){
                $res->code = 200;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $w_comment=$req->w_comment;
            $wc_photo=$req->wc_photo;
            newWorkComment($user_idx,$w_post_idx,$w_comment,$wc_photo);
           //  $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getWorks":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $res->result=getWorks();
            $count=count($res->result);

            for($i=0;$i<$count;$i++){
                $w_post_idx=$res->result[$i]['w_post_idx'];
                if(empty(getFirstComment($w_post_idx))){
                    $res->result[$i]['comment']='곧 크리에이터의 답변이 작성될 예정입니다:)';
                }
                $res->result[$i]['comment']=getFirstComment($w_post_idx);
            }
            //  $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getDetailWork":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $w_post_idx=$vars['w_post_idx'];
            if(!isValidWorkPostIdx($w_post_idx)){
                $res->code = 200;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result->posting=getDetailWork($w_post_idx);
            $res->result->comments=getDetailComments($w_post_idx);

            //  $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "updateMypage":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                // $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->result=getMyInfo($user_idx);
            //echo json_encode($res);
            $new_img=$req->profile_img;
            $new_name=$req->name;
            $new_nickname=$req->nickname;
            $new_phone=$req->phone;

            if($new_img==""){
                $new_img=$res->result[0]['profile_img'];
            }
            if($new_name==""){
                $new_name=$res->result[0]['user_name'];
            }
            if($new_nickname==""){
                $new_nickname=$res->result[0]['nickname'];
            }
            if($new_phone==""){
                $new_phone=$res->result[0]['user_phone'];
            }
            if($new_phone!="" &&(!is_numeric($new_phone) || strlen($new_phone) != 11)){
                $res->code = 220;
                $res->message = "휴대폰 번호를 입력해주세요.";
                $res->result="";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            updateMypage($user_idx,$new_img,$new_name,$new_nickname,$new_phone);

            //  $res->isSuccess = TRUE;
            $res->result="";
            $res->code = 100;
            $res->message = "수정 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "updateReviewHelp":
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
            if(!isValidUserIdx($user_idx)){
                // $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 사용자입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $review_idx=$vars['p_review_idx'];

            if(!isProdReviewIdx($review_idx)){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            if(!hasReviewHelp($user_idx,$review_idx)) {
                addReviewHelp($user_idx, $review_idx);
                plusHelpCount($review_idx);
                $code = 100;
                $message = "도움됨";
            }
            else{
                $help_status = getReviewHelpStatus($user_idx,$review_idx);
                if($help_status == 'N'){
                    $help_status='Y';
                    updateReviewHelp($user_idx,$review_idx,$help_status);
                    plusHelpCount($review_idx);
                    $code = 100;
                    $message = "도움됨";
                }
                else if($help_status == 'Y'){
                    $help_status='N';
                    updateReviewHelp($user_idx,$review_idx,$help_status);
                    minusHelpCount($review_idx);
                    $code = 101;
                    $message = "도움 취소";
                }
            }
            $res->code = $code;
            $res->message = $message;
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}