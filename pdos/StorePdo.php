<?php
//READ
function getPopular10($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select prod.*, if(likes.like_status is null,'N',likes.like_status) as like_status from (select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) order by purchase.sum desc limit 10) as prod left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes on product_idx=selected_idx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getDigital5($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select digital.*,if(likes.like_status is null,'N',likes.like_status) as like_status from (select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='디지털기기/ACC' order by purchase.sum limit 5) as digital
    left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes on product_idx=selected_idx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getDIY5($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select diy.*, if(likes.like_status is null,'N',likes.like_status) as like_status from (select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='EASY DIY' order by purchase.sum limit 5) as diy
    left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes on product_idx=selected_idx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getArt5($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select art.*, if(likes.like_status is null,'N',likes.like_status) as like_status from (select prod_total_info.* from prod_total_info left outer join (select Product_option.product_idx , sum(Product_purchase.count)as sum from Product_purchase left outer join Product_option using(option_idx) group by Product_option.product_idx) as purchase using(product_idx) where prod_total_info.product_ctg='미술재료' order by purchase.sum limit 5) as art
    left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes on product_idx=selected_idx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getNewprod($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select news.product_idx, product_name, product_thumb, product_ctg, seller, origin_price, dis, dis_price, installment, d_charge, likes_cnt, if(likes.like_status is null,'N',likes.like_status) as like_status from (select Product.created_at,prod_total_info.* from prod_total_info left outer join Product using(product_idx) where TIMESTAMPDIFF(DAY, Product.created_at, now()) <= 7 ) as news
left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes on product_idx=selected_idx order by created_at desc ;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function prodByCategory($ctg_type,$user_idx,$ctg_option){
    $pdo = pdoSqlConnect();
    if($ctg_option==0){
        $query = "select prod_total_info.*, if(like_status is null,'N',like_status) as like_status from prod_total_info left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes
on product_idx=selected_idx where product_ctg=? order by likes_cnt desc";
    }
    else if($ctg_option==1){
        $query = "select info.*,created_at from (select prod_total_info.*, if(like_status is null,'N',like_status) as like_status from prod_total_info left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes
on product_idx=selected_idx where product_ctg=? ) as info left outer join Product using(product_idx) order by created_at desc;";
    }
    else if($ctg_option==2){
        $query = "select no_star.product_idx, product_name, product_thumb, product_ctg, seller, origin_price, dis, dis_price, installment, d_charge, likes_cnt, like_status from (select prod.*, if(star is null,0,star) as avg_star from (select prod_total_info.*, if(like_status is null,'N',like_status) as like_status from prod_total_info left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes
on product_idx=selected_idx) as prod left outer join (select product_idx, round(avg(star),1) as star from (select distinct Product_purchase.prod_purchase_idx,Product_option.product_idx from Product_purchase left outer join Product_option using(option_idx)) as r
    left outer join (select Product_review.prod_purchase_idx, Product_review.star ,(if(photo is null,0,group_concat(photo separator ','))) as photos from Product_review left outer join Review_photos using(p_review_idx) group by p_review_idx) as p
using(prod_purchase_idx)group by product_idx) as stars using(product_idx) where product_ctg=? order by avg_star desc) as no_star;";
    }


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx,$ctg_type]);
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

function reviewsByProd3($product_idx){
    $pdo = pdoSqlConnect();
    $query = "select p_info.product_idx,p_info.names, r_info.nickname,r_info.profile_img,r_info.star,r_info.post_date,r_info.p_contents,r_info.photos,r_info.help_count from (select Product_purchase.prod_purchase_idx,Product_option.product_idx, group_concat(Product_option.option_name separator ',') as names from Product_purchase left outer join Product_option using(option_idx) group by prod_purchase_idx,product_idx) as p_info
    left outer join (select review_info.*, User.profile_img,User.nickname from (select Product_review.p_review_idx, Product_review.prod_purchase_idx, Product_review.user_idx, Product_review.p_contents, Product_review.star, (date_format(Product_review.created_at, '%Y.%m.%d')) as post_date, Product_review.help_count, (if(photo is null,0,group_concat(photo separator ','))) as photos from Product_review left outer join Review_photos using(p_review_idx) group by p_review_idx) as review_info
left outer join User using(user_idx)) as r_info using(prod_purchase_idx) where product_idx=? limit 3;";

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
    $query = "select coupon_name, concat(format(coupon_price,0),'원 할인 쿠폰') as price, concat(date_format(due_date,'%Y. %c. %e'),' 23:59까지') as due_date ,if(coupon_ctg is null,concat('본 쿠폰은 [',class_name,']에만 사용 가능한 쿠폰입니다.'),concat('본 쿠폰은 [',coupon_ctg, '] 카테고리 클래스에서만 적용이 가능합니다.')) as comment from Coupon left outer join Class using (class_idx) where  user_idx=?;";

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

function getOrderInfo($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select distinct prod_purchase_idx, date_format(created_at, '%Y. %m. %d') as order_date, pay_status
from (test.Product_purchase)
         left outer join test.Product_purchase_detail using (prod_purchase_idx)
where user_idx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function orderDetail($prod_purchase_idx){
    $pdo = pdoSqlConnect();
    $query = "select prod_purchase_idx, option_idx, option_name, count, p_status
from (test.Product_purchase) left outer join Product_option using (option_idx)
where prod_purchase_idx = ?";

    $st = $pdo->prepare($query);
    $st->execute([$prod_purchase_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

/*function getOrderCount($user_idx){
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
    $query = "select Product_purchase_detail.pay_status,purchase.* from (select date_format(created_at,'%Y. %m. %d') as order_date,prod_purchase_idx, option_name, option_thumb,count,p_status,user_idx from Product_purchase left outer join Product_option using(option_idx)) as purchase left outer join Product_purchase_detail using(prod_purchase_idx) where user_idx=?;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res;
}*/

function getMypage($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select ucl.user_idx, nickname, user_email, profile_img, point,user_name,user_phone , coupon_cnt, like_cnt, if(orders.purchase_cnt is null,0,orders.purchase_cnt) as order_cnt from (select uc.user_idx, nickname, user_email, profile_img, point,user_name,user_phone, coupon_cnt, if(likes.like_cnt is null,'찜 0개',likes.like_cnt) as like_cnt from(select user.user_idx, nickname, user_email, profile_img, point,user_name,user_phone,if(coupon.coupon_cnt is null,'쿠폰 0개',coupon.coupon_cnt) as coupon_cnt from (select user_idx,nickname,user_email, profile_img,point, user_name, user_phone from User)as user left outer join (select user_idx,concat('쿠폰 ',count(*),'개') as coupon_cnt from Coupon group by user_idx) as coupon using(user_idx)) as uc left outer join (select user_idx,concat('찜 ',count(*),'개') as like_cnt from Likes where like_status='Y' group by user_idx) as likes using(user_idx)) as ucl
    left outer join (select user_idx,count(*) as purchase_cnt from (select distinct prod_purchase_idx,user_idx from Product_purchase) as p group by user_idx) as orders using(user_idx) where user_idx=?";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getClassCount($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select user_idx,count(*) as order_cnt from Package_purchase where user_idx=? group by user_idx";
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

function getProductInfo($product_idx,$user_idx){
    $pdo = pdoSqlConnect();
    $query = "select prod.*,if(like_status is null,'N',like_status) as like_status from (select prod_total_info.*, Product.share_url from prod_total_info left outer join Product using(product_idx) where product_idx=?) as prod
    left outer join (select selected_idx,like_status from Likes where idx_type='product' and user_idx=?) as likes on product_idx=selected_idx;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$product_idx,$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getPurchaseDetail($purchase_idx){
    $pdo = pdoSqlConnect();
    $query = "select payment_type, concat(format(total_origin_price,0),'원') as total_origin_price, concat(format(total_discount,0),'원')as total_discount, if(coupon_idx=0,'0원',concat(format(coupon_price,0),'원')) as coupon_price, concat(format(total_d_charge,0),'원')as total_delivery, concat(format(total_price,0),'원') as total_price from Product_purchase_detail left outer join Coupon using(coupon_idx) where prod_purchase_idx=?;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$purchase_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getDeliveryInfo($purchase_idx){
    $pdo = pdoSqlConnect();
    $query = "select recipient,r_phone,r_address,r_memo from Product_delivery where prod_purchase_idx=?";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$purchase_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getOptions($purchase_idx){
    $pdo = pdoSqlConnect();
    $query = "select count,p_status, option_name,option_thumb from Product_purchase left outer join Product_option using(option_idx) where prod_purchase_idx=?;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$purchase_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getDateStatus($purchase_idx){
    $pdo = pdoSqlConnect();
    $query = "select concat(date_format(Product_purchase.created_at,'%Y.%m.%d'),' 주문 상세 내역')as purchase_date,pay_status  from Product_purchase_detail left outer join Product_purchase using(prod_purchase_idx) where prod_purchase_idx=? group by prod_purchase_idx;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$purchase_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getQuestions($product_idx){
    $pdo = pdoSqlConnect();
    $query = "select Product_question.question_idx, User.nickname,if(User.profile_img is null,'.',User.profile_img)as profile_img, q_photos, q_contents, date_format(Product_question.created_at,'%Y. %c. %e') as q_date, product_idx from Product_question left outer join User using(user_idx) where product_idx=?";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$product_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getComments($q_idx){
    $pdo = pdoSqlConnect();
    $query = "select  User.nickname,if(User.profile_img is null,'.',User.profile_img) as profile_img, comment,  date_format(Question_comment.created_at,'%Y. %c. %e') as q_date from Question_comment left outer join User using(user_idx) where question_idx=?";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$q_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function newQuestion($user_idx, $product_idx, $contents, $photo)
{
    $pdo = pdoSqlConnect();

    $query = "insert into Product_question (user_idx, product_idx, q_contents, q_photos) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $product_idx, $contents, $photo]);

    $st = null;
    $pdo = null;
}

function newComment($user_idx, $question_idx, $contents, $photo){
    $pdo = pdoSqlConnect();

    $query = "insert into Question_comment (user_idx, question_idx, comment, c_photo) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx, $question_idx, $contents, $photo]);

    $st = null;
    $pdo = null;
}

function isValidQIdx($question_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Product_question WHERE question_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$question_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}
function hasProdLike($user_idx,$prod_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Likes where selected_idx=? and user_idx=? and idx_type='product') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$prod_idx,$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function addProdLike($user_idx,$prod_idx){
    $pdo = pdoSqlConnect();
    $like_status='Y';
    $idx_type='product';
    $query = "insert into Likes (selected_idx, idx_type, like_status, user_idx) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$prod_idx,$idx_type,$like_status,$user_idx]);

    $st = null;
    $pdo = null;
}

function getProdLikeStatus($user_idx,$prod_idx){
    $pdo = pdoSqlConnect();
    $query = "select like_status from Likes where selected_idx=? and user_idx=? and idx_type='product'";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$prod_idx,$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['like_status'];
}

function updateProdLike($user_idx,$prod_idx,$like_status){
    $pdo = pdoSqlConnect();
    $query = "update Likes set like_status=? where selected_idx=? and user_idx=? and idx_type='product';";

    $st = $pdo->prepare($query);
    $st->execute([$like_status,$prod_idx,$user_idx]);

    $st = null;
    $pdo = null;
}

function getMaxProdOrderIdx(){

    $pdo = pdoSqlConnect();
    $query = "select ifnull(max(prod_purchase_idx), 0)+1 idx from Product_purchase;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function newProdcutPurchase($orderIdx, $user_idx, $option_idx, $count){
    $pdo = pdoSqlConnect();
    $query = "insert into Product_purchase (prod_purchase_idx, option_idx, user_idx, count) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$orderIdx,$option_idx ,$user_idx, $count]);

    $st = null;
    $pdo = null;
}

function getSumPrice($option_idx){
    $pdo = pdoSqlConnect();
    $query = "select option_price,if(discount_rate=0,0,round(option_price*(discount_rate*0.01))) as discount from Product_option where option_idx=?";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$option_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function getDeliveryCharge($option_idx){
    $pdo = pdoSqlConnect();
    $query = "select delivery_charge from Product left outer join Product_option using(product_idx) where option_idx=?;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$option_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function getCouponPrice($coupon_idx){
    $pdo = pdoSqlConnect();
    $query = "select coupon_price from Coupon where coupon_idx=?;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$coupon_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function newProductDelivery($orderIdx,$recipient,$r_phone,$r_address,$memo){
    $pdo = pdoSqlConnect();
    $query = "insert into Product_delivery (prod_purchase_idx, recipient, r_phone, r_address, r_memo) values (?,?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$orderIdx,$recipient,$r_phone,$r_address,$memo]);

    $st = null;
    $pdo = null;
}

function newPurchaseDetail($orderIdx,$payment_type,$coupon_idx,$total_price,$total_discount,$total_delivery,$total_origin_price){
    $pdo = pdoSqlConnect();
    $query = "insert into Product_purchase_detail (prod_purchase_idx,payment_type,coupon_idx,total_price,total_discount,total_d_charge,total_origin_price) values (?,?,?,?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$orderIdx,$payment_type,$coupon_idx,$total_price,$total_discount,$total_delivery,$total_origin_price]);

    $st = null;
    $pdo = null;
}

function isValidPurchaseIdx($purchase_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM Product_purchase WHERE prod_purchase_idx= ?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$purchase_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function getLikeCount($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select count(*) as like_cnt from Likes where user_idx=? and like_status='Y';";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getLikeInfo($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select distinct class_idx as selected_idx,class_name,user_name,class_thumb,like_cnt,like_status,satisfaction, is_available from Likes left outer join class_total_info on class_idx=selected_idx where idx_type='class' and like_status='Y' and user_idx=?
union (select distinct product_idx, product_name,seller,product_thumb,likes_cnt,like_status,'','' from Likes as prod left outer join prod_total_info on product_idx=selected_idx where idx_type='product' and like_status='Y' and user_idx=?)";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx,$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function isTakingClass($user_idx,$class_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select distinct Package_purchase.package_idx, Package.class_idx, user_idx from Package_purchase left outer join Package using(package_idx) where user_idx=? and class_idx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx,$class_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function newWork($user_idx,$class_idx,$w_content,$w_photo){
    $pdo = pdoSqlConnect();

    $query = "insert into Work_post (user_idx,class_idx,w_content,w_photo) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx,$class_idx,$w_content,$w_photo]);

    $st = null;
    $pdo = null;
}

function isValidWorkPostIdx($w_post_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Work_post where w_post_idx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$w_post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}
function newWorkComment($user_idx,$w_post_idx,$w_comment,$wc_photo){
    $pdo = pdoSqlConnect();

    $query = "insert into Work_comment (user_idx,w_post_idx,w_comment,wc_photo) values (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$user_idx,$w_post_idx,$w_comment,$wc_photo]);

    $st = null;
    $pdo = null;
}

function getWorks(){
    $pdo = pdoSqlConnect();
    $query = "select class_idx,nickname,profile_img ,w_post_idx, w_photo, w_content, date_format(Work_post.created_at,'%Y. %c. %e') as w_date from Work_post left outer join User using(user_idx);";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getFirstComment($w_post_idx){
    $pdo = pdoSqlConnect();
    $query = "select nickname,if(length(w_comment)>50,concat(left(w_comment,50),'...'),w_comment) as preview from Work_comment left outer join User using(user_idx) where w_post_idx=? limit 1;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$w_post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getDetailComments($w_post_idx){
    $pdo = pdoSqlConnect();
    $query = "select comment.profile_img, nickname, w_comment, c_date,if(Creator.creator_idx is null,'N',Creator.creator_idx) as isCreator from (select Work_comment.user_idx,profile_img,nickname,w_comment, date_format(Work_comment.created_at,'%Y. %c. %e') as c_date from Work_comment left outer join User using(user_idx) where w_post_idx=?) as comment
left outer join Creator using(user_idx);";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$w_post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getDetailWork($w_post_idx){
    $pdo = pdoSqlConnect();
    $query = "select * from (select post.w_post_idx, nickname, profile_img, w_photo, w_content, w_date,recommend,class_idx,class_total_info.class_thumb,class_name,class_ctg,user_name from (select Work_post.w_post_idx, nickname,profile_img, class_idx, w_photo, w_content, date_format(Work_post.created_at,'%Y. %c. %e')as w_date,recommend from Work_post
    left outer join User using (user_idx) where w_post_idx=?)as post left outer join class_total_info using(class_idx))as posting
left outer join (select w_post_idx,count(*)as c_count from Work_comment group by w_post_idx) as c_count using(w_post_idx);";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$w_post_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function isValidUserCoupon($coupon_idx,$user_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Coupon where coupon_idx=? and user_idx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$coupon_idx,$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function getMyInfo($user_idx){
    $pdo = pdoSqlConnect();
    $query = "select profile_img,user_name,nickname,user_phone from User where user_idx=?";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;

}

function updateMypage($user_idx,$new_img,$new_name,$new_nickname,$new_phone){
    $pdo = pdoSqlConnect();

    $query = "update User set profile_img=?, user_name=?,nickname=?, user_phone=? where user_idx=? limit 1;";

    $st = $pdo->prepare($query);
    $st->execute([$new_img,$new_name,$new_nickname,$new_phone,$user_idx]);

    $st = null;
    $pdo = null;
}

function isProdReviewIdx($review_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Product_review where p_review_idx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$review_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function hasReviewHelp($user_idx,$review_idx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Review_help where user_idx=? and p_review_idx=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx,$review_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return $res[0]["exist"];
}

function addReviewHelp($user_idx, $review_idx){
    $pdo = pdoSqlConnect();
    $help_status='Y';
    $query = "insert into Review_help (p_review_idx, user_idx,help_status) values (?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$review_idx,$user_idx,$help_status]);

    $st = null;
    $pdo = null;
}

function getReviewHelpStatus($user_idx,$review_idx){
    $pdo = pdoSqlConnect();
    $query = "select help_status from Review_help where user_idx=? and p_review_idx=?";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$user_idx,$review_idx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['help_status'];
}

function updateReviewHelp($user_idx,$review_idx,$help_status){
    $pdo = pdoSqlConnect();
    $query = "update Review_help set help_status=? where user_idx=? and p_review_idx=?;";

    $st = $pdo->prepare($query);
    $st->execute([$help_status,$user_idx,$review_idx]);

    $st = null;
    $pdo = null;
}

function plusHelpCount($review_idx){
    $pdo = pdoSqlConnect();
    $query = "update Product_review set help_count=(help_count+1) where p_review_idx=?";

    $st = $pdo->prepare($query);
    $st->execute([$review_idx]);

    $st = null;
    $pdo = null;
}

function minusHelpCount($review_idx){
    $pdo = pdoSqlConnect();
    $query = "update Product_review set help_count=(help_count-1) where p_review_idx=?";

    $st = $pdo->prepare($query);
    $st->execute([$review_idx]);

    $st = null;
    $pdo = null;
}