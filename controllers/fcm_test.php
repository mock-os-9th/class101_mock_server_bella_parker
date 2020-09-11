<?php
//
//function send_notification ($tokens, $message)
//{
//    $url = 'https://fcm.googleapis.com/fcm/send';
//    $fields = array(
//        'registration_ids' => $tokens,
//        'data' => $message
//    );
//    $key = "AAAAdGnrbEs:APA91bGS2ciXLIe19S6n5Rg98zSPhrU6aq18Ye-XKkr2TKo0NzPC0qV_uQCqdX69bTVnrA_K7Uaw06KI7B596yVE9S74CYZvgGsRGDIXDvtr6kx5Yhx52b9MrAg2O7r2p6ctIIDqjSQJ";
//    $headers = array(
//        'Authorization:key =' . $key,
//        'Content-Type: application/json'
//    );
//
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url);?
//    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//    $result = curl_exec($ch);
//    if ($result === FALSE) {
//        die('Curl failed: ' . curl_error($ch));
//    }
//    curl_close($ch);
//    return $result;
//}
//
//
//
//
//
//$tokens = array();
//$tokens[0] = "cdLwVIhI-G0:APA91bHSMqwyMmUJYjRzktXhycx_TVCWr5iQPedy9z7NP-ZhkhdbFk7C8k0jhC56ve5_cXfwBtIf7tfvkF38G2eosxJ0CdipE9CgnWd812u-vjT3Z5Jcfzd4-WKfQOW_juUxiZcvYudT";
//
//
//$myMessage = "Message Test";
//if ($myMessage == ""){
//    $myMessage = "Newly registered.";
//}
//
//$message = array("message" => $myMessage);
//$message_status = send_notification($tokens, $message);
//echo $message_status;
//
//
//?>