<?php
use server_php\Bootpay\Rest\BootpayApi;
//use server_php\BootpayRestBootpayApi;
//use BootpayRestBootpayApi;
require '/var/www/html/test/server_php/Bootpay/Rest/BootpayApi.php';
require 'function.php';


const JWT_SECRET_KEY = "2d4nj21b9r20werioclrn023iowernlnv480o2n";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;

        case "getBootPayAC":
            //echo "hi";
           // break;

            $bootpay = BootpayApi::setConfig(
                '5f59076e0627a8001e56525f',
                'sAecIDF6xtUBXSMWw+TTi/rjuxL/Uu1j8CIhOGcNf6k='
            );

            $response = $bootpay->requestAccessToken();
            if ($response->status === 200) {
                /*print $response->data->token;
                print '
                ';
                print $response->data->server_time;
                print '
                ';
                print $response->data->expired_at;*/
                echo json_encode($response->data, JSON_NUMERIC_CHECK);
            }
            break;

        case 'getFeedback':
            $res="OK";
            //echo $res;
            break;
        case 'verification':

            require_once('/var/www/html/test/server_php/autoload.php');
            spl_autoload_register('BootpayAutoload');

           // use Bootpay\Rest\BootpayApi;
            //receiptId:주문번호
            $receiptId = 'class101_1';

            $bootpay = BootpayApi::setConfig(
                "59a4d32b396fa607c2e75e00",
                "t3UENPWvsUort5WG0BFVk2+yBzmlt3UDvhDH2Uwp0oA="
            );

            $response = $bootpay->requestAccessToken();

        // Token이 발행되면 그 이후에 verify 처리 한다.
            if ($response->status === 200) {
                $result = $bootpay->verify($receiptId);
                echo json_encode($result, JSON_NUMERIC_CHECK);
                //var_dump($result);
            }
            break;
        case 'cancel':

            require_once('/var/www/html/test/server_php/autoload.php');
            spl_autoload_register('BootpayAutoload');

            $receiptId = 'class101_1';

            $bootpay = BootpayApi::setConfig(
                "59a4d32b396fa607c2e75e00",
                "t3UENPWvsUort5WG0BFVk2+yBzmlt3UDvhDH2Uwp0oA="
            );

            $response = $bootpay->requestAccessToken();

            if ($response->status === 200) {
                // 1: 주문번호, 2:취소할 금액, 3: 취소자,4: 취소사유
                $result = $bootpay->cancel($receiptId, 0, 'test', 'test');
                // 결제 취소가 되었다면
                if ($result->status === 200) {
                    // TODO: 결제 취소에 관련된 로직을 수행하시면 됩니다.
                    $result->isCancel='결제 취소';
                    echo json_encode($result);
                }else{
                    echo json_encode($result);
                }
            }
            break;
   }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
