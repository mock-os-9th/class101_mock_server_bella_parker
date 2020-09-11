<?php
require './pdos/DatabasePdo.php';
require './pdos/IndexPdo.php';
require './pdos/StorePdo.php';
require './pdos/ClassPdo.php';
require './pdos/ClassReviewPdo.php';
require './pdos/ClassSearchPdo.php';
require './pdos/CommunityPdo.php';
require './pdos/PackagePdo.php';
require './pdos/EventPdo.php';
require './pdos/KakaoPdo.php';
require './vendor/autoload.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

//에러출력하게 하는 코드
error_reporting(E_ALL); ini_set("display_errors", 1);

//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    /* ******************   Test   ****************** */
    //store 메인페이지
    $r->addRoute('GET', '/store', ['StoreController', 'getProducts']);//0
    $r->addRoute('GET', '/products', ['StoreController', 'prodByCtg']); //0
    $r->addRoute('GET', '/{product_idx}/reviews', ['StoreController', 'reviewByProd']); //0
    $r->addRoute('GET', '/orders', ['StoreController', 'getOrders']); // 0클래스추가
    $r->addRoute('GET', '/coupons', ['StoreController', 'getCoupons']); //0
    $r->addRoute('GET', '/mypage', ['StoreController', 'getMypage']); //0
    $r->addRoute('GET', '/products/{product_idx}', ['StoreController', 'getDetailProduct']); // 0
    $r->addRoute('GET', '/order/{prod_purchase_idx}', ['StoreController', 'getDetailOrder']);//0상세주문보기
    $r->addRoute('POST', '/product-order', ['StoreController', 'newOrder']); //0스토어 상품 결제하기
    $r->addRoute('POST', '/{product_idx}/question', ['StoreController', 'newQuestion']); //0문의하기
    $r->addRoute('POST', '/{question_idx}/comment', ['StoreController', 'newComment']); //0문의 댓글작성
    $r->addRoute('POST', '/like/product/{product_idx}', ['StoreController', 'updateProdLike']); //0스토어상품 찜하기/취소
    $r->addRoute('GET', '/likes', ['StoreController', 'getLikes']); // 찜한 상품/클래스 조회
    $r->addRoute('GET', '/works', ['StoreController', 'getWorks']); //수강생 작품 리스트 조회
    $r->addRoute('GET', '/work/{w_post_idx}', ['StoreController', 'getDetailWork']); //수강생 작품 선택 후 상세 조회
    $r->addRoute('POST', '/work', ['StoreController', 'newWork']); //수강생 작품 등록하기
    $r->addRoute('POST', '/{w_post_idx}/work-comment', ['StoreController', 'newWorkComment']); // 작품 글에 댓글달기
    $r->addRoute('PUT', '/mypage', ['StoreController', 'updateMypage']); //회원정보 수정
    $r->addRoute('POST', '/kakao-login', ['KakaoLoginController', 'getKakaoInfo']); //소셜로그인
    $r->addRoute('POST', '/help/review/{p_review_idx}', ['StoreController', 'updateReviewHelp']); //리뷰 도움됨/취소
    $r->addRoute('GET', '/bootpay-token', ['BootpayController', 'getBootPayAC']); // 부트페이에서 access token 받기
    $r->addRoute('POST', '/feedback', ['BootpayController', 'getFeedback']); //결제 feedback
    $r->addRoute('GET', '/bootpay-verification', ['BootpayController', 'verification']); //결제 검증
    $r->addRoute('GET', '/bootpay-cancel', ['BootpayController', 'cancel']);




    $r->addRoute('GET', '/test/orders', ['IndexController', 'getOrders']);

    // 로그인 API
    $r->addRoute('POST', '/login', ['MainController', 'createJwt']);

    // 자동 로그인 API
    $r->addRoute('POST', '/autoLogin', ['MainController', 'validateJwt']);

    // 회원가입
    $r->addRoute('POST', '/signUp', ['MainController', 'addUser']);

    // 인기, 신규, 오픈예정 클래스 (카테고리 별)
    $r->addRoute('GET', '/class', ['ClassController', 'getClasses']);

    // 업데이트 클래스(페이징 적용)
    $r->addRoute('GET', '/class/updated/{page_num}', ['ClassController', 'getUpdatedClasses']);

    // 클래스 선택화면
    $r->addRoute('GET', '/class/{class_idx}', ['ClassController', 'getClassByClassIdx']);

    // 클래스 좋아요 추가 / 취소
    $r->addRoute('POST', '/likes/class/{class_idx}', ['ClassController', 'updateClassLike']);

    // 클래스 리뷰 미리보기
    $r->addRoute('GET', '/class/review/preview/{class_idx}', ['ClassReviewController', 'getReviewPreview']);

    // 클래스 리뷰 전체보기
    $r->addRoute('GET', '/class/review/{class_idx}', ['ClassReviewController', 'getAllReview']);

    // 키워드로 클래스 검색
    $r->addRoute('GET', '/search/class', ['ClassSearchController', 'searchClassByKeyword']);

    // 커뮤니티 공지 조회
    $r->addRoute('GET', '/class/{class_idx}/community/notice', ['CommunityController', 'getCommunityNotice']);

    // 커뮤니티 게시글 조회
    $r->addRoute('GET', '/class/{class_idx}/community', ['CommunityController', 'getCommunityPost']);

    // 커뮤니티 게시글 작성
    $r->addRoute('POST', '/class/{class_idx}/community', ['CommunityController', 'writeCommunityPost']);

    // 커뮤니티 특정 게시글 조회
    $r->addRoute('GET', '/community/{post_idx}', ['CommunityController', 'getCommunityPostDetail']);

    // 커뮤니티 게시글 댓글 작성
    $r->addRoute('POST', '/community/{post_idx}', ['CommunityController', 'writePostComment']);


    // 커뮤니티 게시글 삭제
    $r->addRoute('DELETE', '/post/{post_idx}', ['CommunityController', 'deletePost']);

    // 커뮤니티 댓글 삭제
    $r->addRoute('DELETE', '/comment/{comment_idx}', ['CommunityController', 'deleteComment']);

    // 패키지 구매
    $r->addRoute('POST', '/package/purchase', ['PackageController', 'addPackagePurchase']);

    // 패키지 조회
    $r->addRoute('GET', '/class/{class_idx}/package', ['PackageController', 'getPackageInfo']);

    // 패키지 구매내역
    $r->addRoute('GET', '/package/purchase', ['PackageController', 'getPackagePurchaseInfo']);

    // 이벤트 조회
    $r->addRoute('GET', '/event', ['EventController', 'getEvents']);

    // 광고 조회
    $r->addRoute('GET', '/ad', ['EventController', 'getAd']);

    // fcm
    $r->addRoute('POST', '/fcm', ['MainController', 'sendFCM']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 로거 채널 생성
$accessLogs = new Logger('ACCESS_LOGS');
$errorLogs = new Logger('ERROR_LOGS');
// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$accessLogs->pushHandler(new StreamHandler('logs/access.log', Logger::INFO));
$errorLogs->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));
// add records to the log
//$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
//$log->addDebug('Debug log');
//$log->addError('Error log');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($routeInfo[1][0]) {
            case 'IndexController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/IndexController.php';
                break;
            case 'ClassController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/ClassController.php';
                break;
            case 'StoreController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/StoreController.php';
                break;
            case 'MainController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/MainController.php';
                break;
            case 'ClassReviewController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/ClassReviewController.php';
                break;
            case 'ClassSearchController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/ClassSearchController.php';
                break;
            case 'CommunityController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/CommunityController.php';
                break;
            case 'PackageController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/PackageController.php';
                break;
            case 'EventController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/EventController.php';
                break;

            case 'KakaoLoginController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/KakaoLoginController.php';
                break;

            case 'BootpayController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/BootpayController.php';
                break;
            /*case 'EventController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/EventController.php';
                break;
            case 'ProductController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ProductController.php';
                break;
            case 'SearchController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SearchController.php';
                break;
            case 'ReviewController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ReviewController.php';
                break;
            case 'ElementController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ElementController.php';
                break;
            case 'AskFAQController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/AskFAQController.php';
                break;*/
        }

        break;
}
