<?php
//READ
function getTopClass($user_idx, $ctg_type)
{
    $pdo = pdoSqlConnect();
    $where_clause="";
    switch($ctg_type){
        case "전체":
            $where_clause = "";
            break;
        case "크리에이티브":
            $where_clause = " where ctg_type = '크리에이티브'";
            break;
        case "커리어":
            $where_clause = " where ctg_type = '커리어'";
            break;
        case "머니":
            $where_clause = " where ctg_type = '머니'";
            break;
    }

    $query = "select class_total_info.*, ifnull(like_status, 'N') as like_status
from ((class_total_info
    left outer join (select class_idx, count(package_idx) as sales
                     from Package_purchase
                              left outer join Package using (package_idx)
                     group by class_idx
                     order by sales desc) as t using (class_idx))
         left outer join (select selected_idx as class_idx, user_idx, like_status
                          from test.Likes
                          where idx_type = 'class'
                            and user_idx = ?) as s using (class_idx)) left outer join Class_category using (class_ctg)".$where_clause." limit 10";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

function getNewClass($user_idx,$ctg_type)
{
    $pdo = pdoSqlConnect();
    $where_clause="";
    switch($ctg_type){
        case "전체":
            $where_clause = " ";
            break;
        case "크리에이티브":
            $where_clause = " and ctg_type = '크리에이티브' ";
            break;
        case "커리어":
            $where_clause = " and ctg_type = '커리어' ";
            break;
        case "머니":
            $where_clause = " and ctg_type = '머니' ";
            break;
    }

    $query = "select class_total_info.*, created_at, ifnull(like_status, 'N') as like_status
from (class_total_info left outer join (Class left outer join Class_category using (class_ctg))using (class_idx))
         left outer join (select selected_idx as class_idx, user_idx, like_status
                          from test.Likes
                          where idx_type = 'class'
                            and user_idx = ?) as t using (class_idx)
where TIMESTAMPDIFF(DAY, created_at, now()) <= 7".$where_clause."order by created_at desc limit 5;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

function getNotOpenedClass($user_idx,$ctg_type)
{
    $pdo = pdoSqlConnect();
    $where_clause="";
    switch($ctg_type){
        case "전체":
            $where_clause = "";
            break;
        case "크리에이티브":
            $where_clause = " where ctg_type = '크리에이티브'";
            break;
        case "커리어":
            $where_clause = " where ctg_type = '커리어'";
            break;
        case "머니":
            $where_clause = " where ctg_type = '머니'";
            break;
    }

    $query = "select not_open.class_idx,
       not_open.class_name,
       not_open.class_ctg,
       not_open.user_name,
       not_open.class_thumb,
       not_open.cheer_count,
       not_open.arrival,
       if(TIMESTAMPDIFF(DAY, now(), Class.open_date) < 3, '응원 마감 임박 집계 진행 중',
          concat('응원 마감까지 ', TIMESTAMPDIFF(DAY, now(), Class.open_date), '일 남음')) as due,
       like_status
from ((select no_type.*, Class_category.ctg_type
      from (select class_total_info.class_idx,
                   class_name,
                   class_ctg,
                   user_name,
                   class_thumb,
                   creator_idx,
                   Not_opened_class.cheer_count,
                   concat(format((Not_opened_class.cheer_count / Not_opened_class.cheer_goal) * 100, 0),
                          '% 달성') as arrival
            from class_total_info
                     join Not_opened_class using (class_idx)
           ) as no_type
               left outer join Class_category using (class_ctg)) as not_open
         left outer join Class using (class_idx)) left outer join (select selected_idx as class_idx, user_idx, like_status
from test.Likes
where idx_type='class'
and user_idx = ?) as t using (class_idx)
".$where_clause."limit 5;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

function getUpdatedClass($user_idx,$ctg_type)
{
    $pdo = pdoSqlConnect();
    $where_clause="";
    switch($ctg_type){
        case "전체":
            $where_clause = " ";
            break;
        case "크리에이티브":
            $where_clause = " and ctg_type = '크리에이티브' ";
            break;
        case "커리어":
            $where_clause = " and ctg_type = '커리어' ";
            break;
        case "머니":
            $where_clause = " and ctg_type = '머니' ";
            break;
    }

    $query = "select class_idx,
       class_total_info.class_name,
       class_total_info.class_ctg,
       class_total_info.user_name,
       class_total_info.class_thumb,
       like_cnt,
       satisfaction,
       like_status,
       update_type,
       case
           when timestampdiff(hour, Class.updated_at, now()) < 1
               then concat(timestampdiff(minute, Class.updated_at, now()), '분 전')
           when timestampdiff(day, Class.updated_at, now()) < 1
               then concat(timestampdiff(hour, Class.updated_at, now()), '시간 전')
           when timestampdiff(day, Class.updated_at, now()) < 7
               then concat(timestampdiff(day, Class.updated_at, now()), '일 전')
           end as updated_at
from (test.class_total_info
         left outer join (Class left outer join Class_category using (class_ctg))using (class_idx)) left outer join (select selected_idx as class_idx, user_idx, like_status
                          from test.Likes
                          where idx_type = 'class'
                            and user_idx = ?) as t using (class_idx)
where timestampdiff(day, Class.updated_at, now()) < 7".$where_clause."order by Class.updated_at desc;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 좋아요한 클래스 존재 여부
function isClassLikeExist($user_idx, $selected_idx)
{
    $pdo = pdoSqlConnect();
    // user_id, rest_id 존재하는지 확인
    $query = "SELECT EXISTS(select * FROM Likes WHERE user_idx = ? and selected_idx = ? and idx_type = 'class') as exist;";
    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $selected_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

// 좋아요한 클래스 추가
function addClassLike($user_idx, $selected_idx)
{
    $pdo = pdoSqlConnect();
    $query="insert into Likes (user_idx, selected_idx, idx_type) values (?, ?, 'class');";
    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $selected_idx]);

    $st = null;
    $pdo = null;
}

// 클래스 좋아요 상태
function getClassLikeStatus($user_idx, $selected_idx)
{
    $pdo = pdoSqlConnect();
    $query="select like_status
from Likes
where user_idx=? and selected_idx=? and idx_type = 'class';";
    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $selected_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['like_status'];
}

// 클래스 좋아요 변경
function updateClassLike($user_idx, $selected_idx, $like_status)
{
    $pdo = pdoSqlConnect();
    $query="update Likes
            set like_status=?
            where user_idx = ?
              and selected_idx = ?
              and idx_type = 'class';";

    $st = $pdo->prepare($query);
    $st->execute([$like_status,$user_idx, $selected_idx]);

    $st = null;
    $pdo = null;
}

// 클래스 선택
function getClassByClassIdx($user_idx, $class_idx)
{
    $pdo = pdoSqlConnect();
    $query="select class_idx,
       Class.class_name,
       Class.class_ctg,
       user_name,
       Class.creator_idx,
       group_concat(Class_img.class_img separator ',')                  as class_img,
       is_available,
       concat(substr(installment, 2, 3), ' 할부')                         as installment,
       dis,
       dis_price,
       target,
       satisfaction,
       like_cnt,
       share_url,
       concat(Class.chapter_cnt, '개 챕터, ', Class.lecture_cnt, '개 세부강의') as class_quantity,
       test.Class.caption,
       format(review_cnt, 0)                                            as review_cnt,
       concat(available_weeks, '주 수강 가능')                               as available_weeks,
       concat(lecture_cnt, '개')                                         as lecture_cnt,
       ifnull(like_status,'N')                                           as like_status
from (((class_total_info
    left outer join Class_img using (class_idx)) left outer join Class using (class_idx))
         left outer join (select class_idx, count(*) as review_cnt
                          from Class_review
                          where class_idx = ?
                          group by class_idx) as t using (class_idx)) left outer join (select selected_idx as class_idx, user_idx, like_status
from test.Likes
where idx_type='class'
and user_idx = ?) as temp using (class_idx)
where class_idx = ?
group by class_idx;";
    $st = $pdo->prepare($query);
    $st->execute([$class_idx, $user_idx, $class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function isValidClassIdx($class_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Class WHERE class_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

