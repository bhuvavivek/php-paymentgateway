<?php

function md5_sign($data,$key,$unset=[]){

    // $data=array_filter($data);
    ksort($data);
    foreach ($unset as $value){
        unset($data[$value]);
    }

    $string = http_build_query($data);
    $string = urldecode($string); //签名的时候不需要做encode

    $string=trim($string) . "&key=" . $key;

    // dd($string); 
    return strtoupper(md5($string));

}

$key = "VDna1dsFeuF4EgA6";

$sign = md5_sign($_POST,$key,['sign']);
if($sign == $_POST['sign']){
    // ====== DO YOUR BESSINESS HERE ======
    $order_sn = $_POST['order_sn'];
    $amount   = $_POST['money'];

    file_put_contents("lgpay-success.log", json_encode($_POST) . "\n", FILE_APPEND);
    echo "success";
    // ====== DO YOUR BESSINESS HERE ======
    // ====== DO YOUR BESSINESS HERE ======
}else {
    file_put_contents("lgpay-failed.log", json_encode($_POST) . "\n", FILE_APPEND);
    echo "fail";

}