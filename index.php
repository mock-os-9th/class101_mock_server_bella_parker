<?php
require './pdos/DatabasePdo.php';
require './pdos/IndexPdo.php';
require './pdos/StorePdo.php';
require './pdos/ClassPdo.php';
require './pdos/ClassReviewPdo.php';
require './pdos/ClassSearchPdo.php';
require './pdos/CommunityPdo.php';
require './vendor/autoload.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

//에러출력하게 하는 코드
//error_reporting(E_ALL); ini_set("display_errors", 1);

//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    /* ******************   Test   ****************** */
    //store 메인페이지
    $r->addRoute('GET', '/store', ['StoreController', 'getProducts']);//0
    $r->addRoute('GET', '/products', ['StoreController', 'prodByCtg']); //0
    $r->addRoute('GET', '/{product_idx}/reviews', ['StoreController', 'reviewByProd']); //0
    $r->addRoute('GET', '/orders', ['StoreController', 'getOrders']); //0
    $r->addRoute('GET', '/coupons', ['StoreController', 'getCoupons']); //0
    $r->addRoute('GET', '/mypage', ['StoreController', 'getMypage']); //0
    $r->addRoute('GET', '/products/{product_idx}', ['StoreController', 'getDetailProduct']); // 문의, 추천상품
    $r->addRoute('GET', '/orders/{purchase_idx}', ['StoreController', 'getDetailOrder']);//상세주문보기
    $r->addRoute('POST', '/order', ['StoreController', 'newOrder']); //주문하기
    //$r->addRoute('GET', '/orders/{prod_purchase_idx}', ['StoreController', 'getDetailOrder']); 문의하기
    //$r->addRoute('GET', '/orders/{prod_purchase_idx}', ['StoreController', 'getDetailOrder']); 문의 댓글작성



    $r->addRoute('GET', '/test/orders', ['IndexController', 'getOrders']);

    // 로그인 API
    $r->addRoute('POST', '/login', ['MainController', 'createJwt']);

    // 회원가입
    $r->addRoute('POST', '/signUp', ['MainController', 'addUser']);

    // 인기, 신규, 오픈예정 클래스 (카테고리 별)
    $r->addRoute('GET', '/class', ['ClassController', 'getClasses']);

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

    // 커뮤니티 게시글 댓글 조회
    $r->addRoute('GET', '/community/{post_idx}', ['CommunityController', 'getPostComment']);

    // 커뮤니티 게시글 댓글 작성
    $r->addRoute('POST', '/community/{post_idx}', ['CommunityController', 'writePostComment']);


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
