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
from class_total_info
         left outer join (Class left outer join Class_category using (class_ctg)) using (class_idx)
where open_date > now()".$where_clause."limit 5;";

    $st = $pdo->prepare($query);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

function getPopular10(){
    $pdo = pdoSqlConnect();
    $query = "select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) order by purchase.sum desc limit 10;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getDigital5(){
    $pdo = pdoSqlConnect();
    $query = "select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='디지털기기/ACC' order by purchase.sum limit 5;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getDIY5(){
    $pdo = pdoSqlConnect();
    $query = "select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='EASY DIY' order by purchase.sum limit 5;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getArt5(){
    $pdo = pdoSqlConnect();
    $query = "select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='미술재료' order by purchase.sum limit 5;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getNewprod(){
    $pdo = pdoSqlConnect();
    $query = "select prod_total_info.* from prod_total_info left outer join Product using(product_idx) where TIMESTAMPDIFF(DAY, Product.created_at, now()) <= 7 order by Product.created_at desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
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
