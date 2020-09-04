<?php
//READ
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

function prodByCategory($ctg_type){
    $pdo = pdoSqlConnect();
    $query = "select * from prod_total_info where product_ctg=?";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$ctg_type]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function reviewsByProd($product_idx){
    $pdo = pdoSqlConnect();
    $query = "select p_info.product_idx,p_info.names, r_info.nickname,r_info.profile_img,r_info.star,r_info.post_date,r_info.p_contents,r_info.photos,r_info.help_count from (select Product_purchase.prod_purchase_idx,Product_option.product_idx, group_concat(Product_option.option_name separator ',') as names from Product_purchase left outer join Product_option using(option_idx) group by prod_purchase_idx,product_idx) as p_info
    left outer join (select review_info.*, User.profile_img,User.nickname from (select Product_review.p_review_idx, Product_review.prod_purchase_idx, Product_review.user_idx, Product_review.p_contents, Product_review.star, (date_format(Product_review.created_at, '%Y.%m.%d')) as post_date, Product_review.help_count, (if(photo is null,0,group_concat(photo separator ','))) as photos from Product_review left outer join Review_photos using(p_review_idx) group by p_review_idx) as review_info
left outer join User using(user_idx)) as r_info using(prod_purchase_idx) where product_idx=?;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$product_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function sumInfo($product_idx){
    $pdo = pdoSqlConnect();
    $query = "select product_idx,round(avg(star),1) as avg_star,group_concat(photos)as photos from (select distinct Product_purchase.prod_purchase_idx,Product_option.product_idx from Product_purchase left outer join Product_option using(option_idx)) as r
    left outer join (select Product_review.prod_purchase_idx, Product_review.star ,(if(photo is null,0,group_concat(photo separator ','))) as photos from Product_review left outer join Review_photos using(p_review_idx) group by p_review_idx) as p
using(prod_purchase_idx) where product_idx=? group by product_idx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$product_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function isValidProdIdx($product_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Product WHERE product_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$product_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function hasReview($product_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select product_idx from Product_review left outer join (select Product_purchase.prod_purchase_idx,Product_option.product_idx from Product_purchase left outer join Product_option using(option_idx)) as prod
using(prod_purchase_idx) where product_idx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$product_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function getCouponCount($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select concat(count(*),'개') as coupon_cnt from Coupon where user_idx=? group by user_idx ;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getCouponDetail($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select coupon_name, concat(format(coupon_price,0),'원 할인 쿠폰') as price, concat(date_format(due_date,'%Y. %c. %e'),' 23:59까지') as due_date ,if(coupon_ctg is null,concat('본 쿠폰은 [',class_name,']에만 사용 가능한 쿠폰입니다.'),concat('본 쿠폰은 \"',coupon_ctg,'\" 카테고리 클래스에서만 적용이 가능합니다.')) as comment from Coupon left outer join Class using (class_idx) where  user_idx=?;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function isValidUserIdx($user_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM User WHERE user_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function getOrderCount($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select count(*) as purchase_cnt from (select distinct prod_purchase_idx,user_idx from Product_purchase) as p  where user_idx=? group by user_idx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getOrderDetail($user_idx){
    $pdo = pdoSqlConnect();
    $query = "";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getMypage($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select ucl.user_idx, nickname, user_email, profile_img, point, coupon_cnt, like_cnt, if(orders.purchase_cnt is null,'주문내역 0개',orders.purchase_cnt) as order_cnt from (select uc.user_idx, nickname, user_email, profile_img, point, coupon_cnt, if(likes.like_cnt is null,'찜 0개',likes.like_cnt) as like_cnt from(select user.user_idx, nickname, user_email, profile_img, point,if(coupon.coupon_cnt is null,'쿠폰 0개',coupon.coupon_cnt) as coupon_cnt from (select user_idx,nickname,user_email, profile_img,point from User)as user left outer join (select user_idx,concat('쿠폰 ',count(*),'개') as coupon_cnt from Coupon group by user_idx) as coupon using(user_idx)) as uc left outer join (select user_idx,concat('찜 ',count(*),'개') as like_cnt from Likes group by user_idx) as likes using(user_idx)) as ucl
    left outer join (select user_idx,concat('주문내역 ',count(*),'개') as purchase_cnt from (select distinct prod_purchase_idx,user_idx from Product_purchase) as p group by user_idx) as orders using(user_idx) where user_idx=?";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getMyclass($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select info.class_idx, user_idx, if(enrollment=0,'지금 수강을 시작하세요!',concat('수강 ',enrollment,'달성')) as enrollment, concat(due_date,' 수강만료') as due_date,Class.class_name,Class.class_thumb from (select Class_enrollment.*, Package.class_idx from Class_enrollment left outer join Package using(package_idx)) info left outer join Class using(class_idx) where user_idx=?";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}