<?php
//READ
// 커뮤니티 공지 조회
function getCommunityNotice($class_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select post_idx, nickname, profile_img, date_format(Community_post.created_at, '%Y. %m. %d') as created_at, post_contents, reply_cnt
from (Community_post left outer join User using (user_idx)) left outer join (select post_idx, count(post_idx) as reply_cnt
from Community_comment
group by post_idx) as t using (post_idx)
where class_idx = ?
and   is_notice = 'Y'
order by Community_post.created_at desc
limit 5;";

    $st = $pdo->prepare($query);
    $st->execute([$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 커뮤니티 게시글 조회
function getCommunityPost($class_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select post_idx, nickname, profile_img, date_format(Community_post.created_at, '%Y. %m. %d') as created_at, post_contents
from Community_post left outer join User using (user_idx)
where class_idx = ?
order by Community_post.created_at desc
;";

    $st = $pdo->prepare($query);
    $st->execute([$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 커뮤니티 특정 게시글 조회
function getCommunityPostDetail($post_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select post_idx,
       nickname,
       profile_img,
       date_format(Community_post.created_at, '%Y. %m. %d') as created_at,
       post_contents,
       reply_cnt,
       Class.class_idx,
       class_ctg,
       user_name,
       class_thumb,
       class_name
from ((Community_post left outer join User using (user_idx))
    left outer join (select post_idx, count(post_idx) as reply_cnt
                     from Community_comment
                     group by post_idx) as t using (post_idx))
         left outer join Class using (class_idx)
where post_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 커뮤니티 게시글 작성
function writeCommunityPost($user_idx, $class_idx, $post_contents, $post_photo)
{
    $pdo = pdoSqlConnect();

    $query = "insert into Community_post (user_idx, class_idx, post_contents, post_photo) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $class_idx, $post_contents, $post_photo]);

    $st = null;
    $pdo = null;
}

// 커뮤니티 게시글 댓글 조회
function getPostComment($post_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select comment_idx, nickname, profile_img,  date_format(Community_comment.created_at, '%Y. %m. %d') as created_at, comment_contents
from Community_comment left outer join User using (user_idx)
where post_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 커뮤니티 게시글 댓글 작성
function writePostComment($user_idx, $post_idx, $comment_contents, $comment_photo)
{
    $pdo = pdoSqlConnect();

    $query = "insert into Community_comment (user_idx, post_idx, comment_contents, comment_photo) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $post_idx, $comment_contents, $comment_photo]);

    $st = null;
    $pdo = null;
}
// 댓글 작성한 user_idx
function getCommentWriterIdx($comment_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select user_idx
from Community_comment
where comment_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$comment_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['user_idx'];
}

// 댓글 작성한 user_idx
function getPostWriterIdx($post_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select user_idx
from Community_post
where post_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['user_idx'];
}

// 커뮤니티 게시글 삭제
function deletePost($post_idx)
{
    $pdo = pdoSqlConnect();

    $query = "delete from Community_post
where post_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$post_idx]);

    $st = null;
    $pdo = null;
}

// 커뮤니티 댓글 삭제
function deleteComment($comment_idx)
{
    $pdo = pdoSqlConnect();

    $query = "delete from Community_comment
where comment_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$comment_idx]);

    $st = null;
    $pdo = null;
}



// post_idx 유효성 검사
function isValidPostIdx($post_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Community_post WHERE post_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    $st->execute([$post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}
function isValidCommentIdx($comment_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Community_comment WHERE comment_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    $st->execute([$comment_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}
function getClassIdxByPostIdx($post_idx){
    $pdo = pdoSqlConnect();

    $query = "select class_idx
                from test.Community_post
                where post_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['class_idx'];
}
function getUserIdxOfCreatorByClassIdx($class_idx){
    $pdo = pdoSqlConnect();

    $query = "select user_idx
from test.Class left outer join Creator using (creator_idx)
where class_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['user_idx'];
}
function updateClassInfo($class_idx, $update_type){
    $pdo = pdoSqlConnect();

    $query = "update test.Class
            set updated_at = now()
            where class_idx = ?;";
    $st = $pdo->prepare($query);
    $st->execute([$class_idx]);

    $query = "update test.Class
            set update_type = ?
            where class_idx = ?;";
    $st = $pdo->prepare($query);
    $st->execute([$update_type,$class_idx]);


    $st = null;
    $pdo = null;
}