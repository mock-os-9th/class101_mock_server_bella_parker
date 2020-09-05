<?php
//READ
// 키워드로 검색(수강가능)
function searchOpenedClassByKeyword($user_idx, $keyword, $option)
{
    $pdo = pdoSqlConnect();
    $order_clause = "";
    switch ($option) {
        case 0:
            $order_clause = ";";
            break;
        case 1:
            $order_clause = " order by created_at desc;";
            break;
        case 2:
            $order_clause = " order by satisfaction desc;";
            break;
        case 3:
            $order_clause = " order by like_cnt desc";
            break;
    }

    $query = "select class_total_info.*, like_status
from ((test.class_total_info)
         left outer join (select selected_idx as class_idx, user_idx, like_status
                          from test.Likes
                          where idx_type = 'class'
                            and user_idx = ?) as t using (class_idx)) left outer join Class using (class_idx)
where Class.class_name like concat('%',?,'%')
and is_available = '바로 수강 가능'" . $order_clause;

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

// 키워드로 검색(오픈예정)
function searchNotOpenedClassByKeyword($user_idx, $keyword, $option)
{
    $pdo = pdoSqlConnect();
    $order_clause = "";
    switch ($option) {
        case 0:
            $order_clause = ";";
            break;
        case 1:
            $order_clause = " order by created_at desc;";
            break;
        case 3:
            $order_clause = " order by cheer_count desc";
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
where Class.class_name like concat('%',?,'%')" . $order_clause;

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}
