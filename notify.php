<?php

function md5_sign($data, $key, $unset = []) {
    ksort($data);
    foreach ($unset as $value) {
        unset($data[$value]);
    }
    $string = urldecode(http_build_query($data));
    $string = trim($string) . "&key=" . $key;
    return strtoupper(md5($string));
}

$key = "VDna1dsFeuF4EgA6";

// Validate required fields
if (!isset($_POST['sign'], $_POST['order_sn'], $_POST['money'])) {
    http_response_code(400);
    echo "fail";
    exit;
}

// Verify signature
$sign = md5_sign($_POST, $key, ['sign']);
if ($sign === $_POST['sign']) {
    $order_sn = basename($_POST['order_sn']); // sanitize
    $data = [
        'order_sn' => $order_sn,
        'amount' => $_POST['money'],
        'status' => 'paid',
        'timestamp' => time()
    ];
    if (!is_dir("payments")) {
    mkdir("payments", 0755, true);
    }
    
    file_put_contents("payments/$order_sn.json", json_encode($data, JSON_PRETTY_PRINT));
    http_response_code(200);

    echo "success";

} else {
    file_put_contents("lgpay-failed.log", json_encode($_POST) . "\n", FILE_APPEND);
    http_response_code(400);
    echo "fail";
}
