<?php

function call($method, $arParam, $DOMAIN, $ACCESS) {
    $queryUrl = $DOMAIN."/rest/".$method.".json?auth=".$ACCESS;
    $queryData = http_build_query($arParam);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($result, 1);

    return $result;
}


function callUpdate($method, $arParam, $DOMAIN, $ACCESS) {

    $queryUrl = $DOMAIN.'/rest/'.$method.'.json';

    $queryData = http_build_query(array(
        "auth" => $ACCESS,
        "id" => 4
    ));

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));

    $result = json_decode(curl_exec($curl), true);
    curl_close($curl);

    return $result;
}


function writeToLog($data, $title = '') {
    $log = "\n------------------------\n";
    $log .= date("Y.m.d G:i:s") . "\n";
    $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
    $log .= print_r($data, 1);
    $log .= "\n------------------------\n";
    file_put_contents(getcwd() . '/hook.log', $log, FILE_APPEND);
    return true;
}


require __DIR__.'/classes/Currency.php';
require __DIR__.'/classes/Auth.php';
require __DIR__.'/classes/Request.php';
require __DIR__.'/classes/Contact.php';
require __DIR__.'/classes/Notification.php';