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

        // 커뮤니티 공지 조회
        case "getCommunityNotice":
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
            $res->result = getCommunityNotice($class_idx);
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        // 커뮤니티 게시글 조회(댓글도 같이 -> getPostComment)
        case "getCommunityPost":
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

            $post = getCommunityPost($class_idx);
            $post_cnt = count($post);

            for($i = 0; $i<$post_cnt; $i++){
                $post_idx = $post[$i]['post_idx'];
                $comments = getPostComment($post_idx);
                $post[$i]['comments'] = $comments;
            }
            $res->result->post = $post;
            // post 개수만큼 for문 돌려서
            // post_idx별 comment 개수만큼 이어붙이기
           $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        // 커뮤니티 특정 게시글 조회
        case "getCommunityPostDetail":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $post_idx = $vars['post_idx'];
            if(!isValidPostIdx($post_idx)){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $res->result->post = getCommunityPostDetail($post_idx);
            $res->result->comment = getPostComment($post_idx);
            $res->code = 100;
            $res->message = "조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        // 커뮤니티 게시글 작성
        case "writeCommunityPost":
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
            $post_contents = $req->post_contents;
            if($post_contents==null){
                $res->code = 210;
                $res->message = "내용을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $post_photo = $req->post_photo;


            writeCommunityPost($user_idx, $class_idx, $post_contents, $post_photo);
            $res->code = 100;
            $res->message = "게시글 작성 성공";


            $creator_user_idx = getUserIdxOfCreatorByClassIdx($class_idx);
            if($user_idx == $creator_user_idx)
                updateClassInfo($class_idx, "커뮤니티 글 작성");
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        // 커뮤니티 게시글 댓글 작성
        case "writePostComment":
            http_response_code(200);
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->code = 220;
                $res->message = "로그인이 필요한 서비스입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $post_idx = $vars['post_idx'];
            if(!isValidPostIdx($post_idx)){
                $res->code = 201;
                $res->message = "유효한 인덱스가 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $user_idx = getUserIdByEmail($data->email);
            $comment_contents = $req->comment_contents;
            if($comment_contents==null){
                $res->code = 210;
                $res->message = "내용을 입력하세요.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            $comment_photo = $req->comment_photo;
            writePostComment($user_idx, $post_idx, $comment_contents, $comment_photo);
            $res->code = 100;
            $res->message = "댓글 작성 성공";

            $class_idx = getClassIdxByPostIdx($post_idx);
            $creator_user_idx = getUserIdxOfCreatorByClassIdx($class_idx);
            if($user_idx == $creator_user_idx)
                updateClassInfo($class_idx, "커뮤니티 답글 작성");

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
