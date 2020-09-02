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
        case "reviewByProd":
            http_response_code(200);
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

        case "getProducts":
            http_response_code(200);
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

        case "prodByCtg":
            http_response_code(200);
            $ctg_type = $_GET['type'];
            if(!($ctg_type == "EASY DIY" || $ctg_type == "굿즈" || $ctg_type == "미술재료" || $ctg_type == "공예재료" || $ctg_type == "디지털기기/ACC" || $ctg_type == "악기/음악" || $ctg_type == "헬스/뷰티/ACC" || $ctg_type == "인테리어/소품" || $ctg_type == "푸드/키친" || $ctg_type == "문구/도서" )){
                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "잘못된 카테고리입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                //addErrorLogs($errorLogs, $res, $req);
                return;
            }
            if(!hasProduct($ctg_type)){
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "아직 등록된 상품이 없습니다.";
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
            $result['top10_class'] = getTopClass($ctg_type);
            $result['new_class'] = getNewClass($ctg_type);
            $result['not_opened_class'] = getNotOpenedClass($ctg_type);
            $res->result = $result;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "updateClassLike":
            http_response_code(200);
            $class_idx = $vars['class_idx'];
            $message = "";
            // 1) 목록에 존재하는가?
            // 존재안하면 새로 추가
            if(!isClassLikeExist($class_idx)){
                addClassLike($class_idx);
                $code = 100;
                $message = "좋아요 추가";
            }
            // 존재하면 토글
            else{
                $is_deleted = getClassLikeStatus($class_idx);
                if($is_deleted == 'N'){
                    updateClassLike($class_idx, 'Y');
                    $code = 101;
                    $message = "좋아요 취소";
                }
                else{
                    updateClassLike($class_idx, 'N');
                    $code = 100;
                    $message = "좋아요 추가";
                }
            }
            $res->isSuccess = TRUE;
            $res->code = $code;
            $res->message = $message;
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
