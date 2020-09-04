<?php
//READ
function getTopClass($ctg_type)
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

    $query = "select class_total_info.*
from (class_total_info
    left outer join (select class_idx, count(package_idx) as sales
                     from Package_purchase
                              left outer join Package using (package_idx)
                     group by class_idx
                     order by sales desc) as t using (class_idx))
         left outer join Class_category using (class_ctg)".$where_clause." limit 5";

    $st = $pdo->prepare($query);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

function getNewClass($ctg_type)
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

    $query = "select class_total_info.*
from (class_total_info
         left outer join (Class left outer join Class_category using (class_ctg)) using (class_idx))
where TIMESTAMPDIFF(DAY, created_at, now()) <= 7".$where_clause."order by created_at desc limit 5;";

    $st = $pdo->prepare($query);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

function getNotOpenedClass($ctg_type)
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

    $query = "select not_open.class_idx, not_open.class_name, not_open.class_ctg, not_open.user_name, not_open.class_thumb, not_open.cheer_count, not_open.arrival, if(TIMESTAMPDIFF(DAY,now(),Class.open_date)<3,'응원 마감 임박 집계 진행 중',concat('응원 마감까지',TIMESTAMPDIFF(DAY,now(),Class.open_date),'일 남음')) as due from (select no_type.*,Class_category.ctg_type from (select class_total_info.class_idx, class_name, class_ctg, user_name, class_thumb, creator_idx, Not_opened_class.cheer_count, concat(format((Not_opened_class.cheer_count/Not_opened_class.cheer_goal)*100,0),'% 달성') as arrival from class_total_info join Not_opened_class using(class_idx)
) as no_type left outer join Class_category using(class_ctg)) as not_open left outer join Class using(class_idx)
".$where_clause."limit 5;";

    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

//function getPopular10(){
//    $pdo = pdoSqlConnect();
//    $query = "select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) order by purchase.sum desc limit 10;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//function getDigital5(){
//    $pdo = pdoSqlConnect();
//    $query = "select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='디지털기기/ACC' order by purchase.sum limit 5;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//function getDIY5(){
//    $pdo = pdoSqlConnect();
//    $query = "select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='EASY DIY' order by purchase.sum limit 5;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//function getArt5(){
//    $pdo = pdoSqlConnect();
//    $query = "select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='미술재료' order by purchase.sum limit 5;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//function getNewprod(){
//    $pdo = pdoSqlConnect();
//    $query = "select prod_total_info.* from prod_total_info left outer join Product using(product_idx) where TIMESTAMPDIFF(DAY, Product.created_at, now()) <= 7 order by Product.created_at desc;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//function prodByCategory($ctg_type){
//    $pdo = pdoSqlConnect();
//    $query = "select * from prod_total_info where product_ctg=?";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$ctg_type]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//function reviewsByProd($product_idx){
//    $pdo = pdoSqlConnect();
//    $query = "select p_info.product_idx,p_info.names, r_info.nickname,r_info.profile_img,r_info.star,r_info.post_date,r_info.p_contents,r_info.photos,r_info.help_count from (select Product_purchase.prod_purchase_idx,Product_option.product_idx, group_concat(Product_option.option_name separator ',') as names from Product_purchase left outer join Product_option using(option_idx) group by prod_purchase_idx,product_idx) as p_info
//    left outer join (select review_info.*, User.profile_img,User.nickname from (select Product_review.p_review_idx, Product_review.prod_purchase_idx, Product_review.user_idx, Product_review.p_contents, Product_review.star, (date_format(Product_review.created_at, '%Y.%m.%d')) as post_date, Product_review.help_count, (if(photo is null,0,group_concat(photo separator ','))) as photos from Product_review left outer join Review_photos using(p_review_idx) group by p_review_idx) as review_info
//left outer join User using(user_idx)) as r_info using(prod_purchase_idx) where product_idx=?;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$product_idx]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}

// 좋아요한 클래스 존재 여부
function isClassLikeExist($selected_idx)
{
    $pdo = pdoSqlConnect();
    // user_id, rest_id 존재하는지 확인
    $query = "SELECT EXISTS(select * FROM Likes WHERE user_idx = 1 and selected_idx = ? and idx_type = 'class') as exist;";
    $st = $pdo->prepare($query);
    $st->execute([$selected_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

// 좋아요한 클래스 추가
function addClassLike($selected_idx)
{
    $pdo = pdoSqlConnect();
    $query="insert into Likes (user_idx, selected_idx, idx_type) values (1, ?, 'class');";
    $st = $pdo->prepare($query);
    $st->execute([$selected_idx]);

    $st = null;
    $pdo = null;
}

// 클래스 좋아요 상태
function getClassLikeStatus($selected_idx)
{
    $pdo = pdoSqlConnect();
    $query="select is_deleted
from Likes
where user_idx=1 and selected_idx=? and idx_type = 'class';";
    $st = $pdo->prepare($query);
    $st->execute([$selected_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['is_deleted'];
}

// 클래스 좋아요 변경
function updateClassLike($selected_idx, $is_deleted)
{
    $pdo = pdoSqlConnect();
    $query="update Likes
            set is_deleted=?
            where user_idx = 1
              and selected_idx = ?
              and idx_type = 'class';";

    $st = $pdo->prepare($query);
    $st->execute([$is_deleted,$selected_idx]);

    $st = null;
    $pdo = null;
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
//
//function sumInfo($product_idx){
//    $pdo = pdoSqlConnect();
//    $query = "select product_idx,round(avg(star),1) as avg_star,group_concat(photos)as photos from (select distinct Product_purchase.prod_purchase_idx,Product_option.product_idx from Product_purchase left outer join Product_option using(option_idx)) as r
//    left outer join (select Product_review.prod_purchase_idx, Product_review.star ,(if(photo is null,0,group_concat(photo separator ','))) as photos from Product_review left outer join Review_photos using(p_review_idx) group by p_review_idx) as p
//using(prod_purchase_idx) where product_idx=? group by product_idx;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$product_idx]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}

//function isValidProdIdx($product_idx){
//    $pdo = pdoSqlConnect();
//    $query = "SELECT EXISTS(SELECT * FROM Product WHERE product_idx= ?) AS exist;";
//
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$product_idx]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st=null;$pdo = null;
//
//    return $res[0]["exist"];
//}
//
//function hasReview($product_idx){
//    $pdo = pdoSqlConnect();
//    $query = "SELECT EXISTS(select product_idx from Product_review left outer join (select Product_purchase.prod_purchase_idx,Product_option.product_idx from Product_purchase left outer join Product_option using(option_idx)) as prod
//using(prod_purchase_idx) where product_idx=?) AS exist;";
//
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$product_idx]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st=null;$pdo = null;
//
//    return $res[0]["exist"];
//}

// CREATE
//    function addMaintenance($message){
//        $pdo = pdoSqlConnect();
//        $query = "INSERT INTO MAINTENANCE (MESSAGE) VALUES (?);";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message]);
//
//        $st = null;
//        $pdo = null;
//
//    }


// UPDATE
//    function updateMaintenanceStatus($message, $status, $no){
//        $pdo = pdoSqlConnect();
//        $query = "UPDATE MAINTENANCE
//                        SET MESSAGE = ?,
//                            STATUS  = ?
//                        WHERE NO = ?";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message, $status, $no]);
//        $st = null;
//        $pdo = null;
//    }

// RETURN BOOLEAN
//    function isRedundantEmail($email){
//        $pdo = pdoSqlConnect();
//        $query = "SELECT EXISTS(SELECT * FROM USER_TB WHERE EMAIL= ?) AS exist;";
//
//
//        $st = $pdo->prepare($query);
//        //    $st->execute([$param,$param]);
//        $st->execute([$email]);
//        $st->setFetchMode(PDO::FETCH_ASSOC);
//        $res = $st->fetchAll();
//
//        $st=null;$pdo = null;
//
//        return intval($res[0]["exist"]);
//
//    }
