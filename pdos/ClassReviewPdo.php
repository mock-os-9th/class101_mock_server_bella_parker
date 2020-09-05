<?php
//READ
// 리뷰 개수, 만족도
function getReviewCntSatis($class_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select class_idx,
       count(*)                                                  as review_cnt,
       concat(round(sum(is_satisfied) / count(*) * 100, 0), '%') as satisfaction
from test.Class_review
where class_idx = ?
group by class_idx;";

    $st = $pdo->prepare($query);
    $st->execute([$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 미리보기
function getReviewPreview($class_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select c_review_idx,
       concat(substr(nickname, 1, 1), '**')      as nickname,
       profile_img,
       is_satisfied,
       date_format(User.created_at, '%Y. %m. %d') as created_at,
       c_contents
from test.Class_review
         left outer join User using (user_idx)
where class_idx = ?
order by created_at desc
limit 2;";

    $st = $pdo->prepare($query);
    $st->execute([$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 전체보기
function getAllReview($class_idx)
{
    $pdo = pdoSqlConnect();

    $query = "select c_review_idx, 
       concat(substr(nickname, 1, 1), '**')      as nickname,
       profile_img,
       is_satisfied,
       date_format(User.created_at, '%Y. %m. %d') as created_at,
       c_contents
from test.Class_review
         left outer join User using (user_idx)
where class_idx = 1
order by created_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}