<?php
//READ
// 휴대폰번호 가져오기
function getPhoneByUserIdx($user_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select user_phone
                from User
                where user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['user_phone'];
}

// 원가 가져오기
function getOriginPriceByPackageIdx($package_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select sum(origin_price) as origin_price
                from Component
                where package_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$package_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['origin_price'];
}

// package_purchase 테이블에 추가
function addPackagePurchase($package_idx, $user_idx, $user_phone, $coupon_idx, $payment_type, $discount, $delivery_price, $origin_price, $created_at)
{
    $pdo = pdoSqlConnect();

    $query = "insert into Package_purchase (package_idx, user_idx, user_phone, coupon_idx, 
payment_type, discount, delivery_price, origin_price, created_at) values (?,?,?,?,?,?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$package_idx, $user_idx, $user_phone, $coupon_idx, $payment_type, $discount, $delivery_price, $origin_price, $created_at]);

    $st = null;
    $pdo = null;
}

// package_delivery 테이블 추가
function addPackageDelivery($pkg_purchase_idx, $component_idx, $address, $user_request)
{
    $pdo = pdoSqlConnect();

    $query = "insert into Package_delivery (pkg_purchase_idx, component_idx, address, user_request) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$pkg_purchase_idx, $component_idx, $address, $user_request]);

    $st = null;
    $pdo = null;
}


// 패키지 구매 인덱스 가져오기
function getPackagePurchaseIdx($user_idx, $created_at)
{
    $pdo = pdoSqlConnect();
    $query = "select pkg_purchase_idx
            from Package_purchase
            where user_idx = ? and Package_purchase.created_at = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $created_at]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['pkg_purchase_idx'];
}

// 구성품 idx 가져오기
function getComponentIdxByPackageIdx($package_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select component_idx
                from Component
                where package_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$package_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res[0]['component_idx'];
}

// 패키지 정보 조회
function getPackageInfo($class_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select package_idx,
       package_name,
       sum(Component.origin_price)          as origin_price,
       installment_month                    as installment,
       stock
from Package
         left outer join Component using (package_idx)
where class_idx = ?
group by package_idx;";

    $st = $pdo->prepare($query);
    $st->execute([$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}


// 구성품 정보 조회
function getComponentInfo($package_idx)
{
    $pdo = pdoSqlConnect();
    $query = "select component_idx,
       component_thumb,
       component_name,
       concat(round(discount_rate * 100, 0), '%')     as discount_rate,
       round((origin_price * (1 - discount_rate)), 0) as discount_price
 
from Component
where package_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$package_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    return $res;
}

function getPackagePurchaseInfo($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select pkg_purchase_idx, package_idx, package_thumb, package_name, date_format(created_at, '%Y. %m. %d') as order_date, payment_status
from test.Package_purchase left outer join Package using (package_idx)
where user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function getComponentDeliveryInfo($pkg_purchase_idx){
    $pdo = pdoSqlConnect();
    $query = "select delivery_idx, Package_delivery.component_idx, Component.component_name, delivery_status
from (Package_purchase left outer join test.Package_delivery using (pkg_purchase_idx))
         left outer join test.Component using (component_idx)
where Package_purchase.pkg_purchase_idx =?;";

    $st = $pdo->prepare($query);
    $st->execute([$pkg_purchase_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function isValidPackageIdx($package_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Package WHERE package_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    $st->execute([$package_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;
    $pdo = null;

    return $res[0]["exist"];
}

function isValidCouponIdx($coupon_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Coupon WHERE coupon_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    $st->execute([$coupon_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;
    $pdo = null;

    return $res[0]["exist"];
}
