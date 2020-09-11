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

        // 패키지 구매
        case "addPackagePurchase":
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
            $package_idx = $req->package_idx;
            $user_idx = getUserIdByEmail($data->email);
            $user_phone = getPhoneByUserIdx($user_idx);
            $coupon_idx = $req->coupon_idx;
            $payment_type = $req->payment_type;
            $discount = $req->discount;
            $delivery_price = $req->delivery_price;
            $origin_price = getOriginPriceByPackageIdx($package_idx);
            $address = $req->address;
            $user_request = $req->user_request;
            $now = date("Y-m-d H:i:s");
            if($discount == null)
                $discount = 0;

            if($delivery_price == null)
                $delivery_price = 0;

            if(!isValidPackageIdx($package_idx)){
                $res->code = 201;
                $res->message = "유효한 패키지가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            if(!($payment_type == "카드" || $payment_type == "무통장입금" || $payment_type == "토스")){
                $res->code = 202;
                $res->message = "결제 방식이 잘못됐습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            if($coupon_idx != null && !isValidCouponIdx($coupon_idx)){
                $res->code = 203;
                $res->message = "유효한 쿠폰이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            if($address == null){
                $res->code = 204;
                $res->message = "배달 주소를 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }


            // package_purchase 테이블에 추가
            addPackagePurchase($package_idx, $user_idx, $user_phone, $coupon_idx, $payment_type, $discount, $delivery_price, $origin_price, $now);

            $pkg_purchase_idx = getPackagePurchaseIdx($user_idx, $now);
            $components = getComponentIdxByPackageIdx($package_idx);
            $components_cnt = count($components);

            // component 개수만큼 반복
            for($i = 0; $i<$components_cnt; $i++){
                $component_idx = $components[$i]['component_idx'];
                // package_delivery 테이블에 추가
                addPackageDelivery($pkg_purchase_idx, $component_idx, $address, $user_request);
            }
            $res->code = 100;
            $res->message = "패키지 구매 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        // 패키지 조회
        case "getPackageInfo":
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

            // class_idx에 해당하는 패키지 정보 조회
            $package = getPackageInfo($class_idx);
            $package_cnt = count($package);


            // package 개수만큼 반복
            for($i = 0; $i<$package_cnt; $i++){
                $package_idx = $package[$i]['package_idx'];

                // i번째 패키지의 원가 조회
                $origin_price = $package[$i]['origin_price'];

                // package_idx에 해당하는 구성품 조회
                $component = getComponentInfo($package_idx);
                $component_cnt = count($component);


                // i번째 패키지 구성품의 할인가격 합계 조회
                $total_discount_price = 0;
                for($j = 0; $j<$component_cnt; $j++)
                    $total_discount_price += $component[$j]['discount_price'];


                $installment_month = $package[$i]['installment'];
                $package[$i]['components'] = $component;
                $package[$i]['total_discount_price'] = $total_discount_price;
                $package[$i]['total_discount_rate'] = round((1-($total_discount_price / $origin_price))*100)."%";
                $package[$i]['installment_price'] = round($total_discount_price / $installment_month,0);

            }

            $res->result->package = $package;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        // 패키지 구매 내역
        case "getPackagePurchaseInfo":
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

            $purchase_info = getPackagePurchaseInfo($user_idx);
            $purchase_cnt = count($purchase_info);

            //
            for($i = 0; $i<$purchase_cnt; $i++){
                $pkg_purchase_idx = $purchase_info[$i]['pkg_purchase_idx'];
                $component_delivery_info = getComponentDeliveryInfo($pkg_purchase_idx);
                $purchase_info[$i]['component_delivery_info'] = $component_delivery_info;
            }

            $res->result->purchase_info = $purchase_info;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
