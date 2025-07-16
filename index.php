<?php
function md5_sign($data, $key) {
    ksort($data);
    $string = http_build_query($data);
    $string = urldecode($string); 
    $string = trim($string) . "&key=" . $key;
    return strtoupper(md5($string));
}

// Request parameters for ₹100
$app_id = "YD3644";  // ask service provider"; // Your app ID, ask service provider
$secret_key = "VDna1dsFeuF4EgA6"; // Secret key provided by the gateway, ask service provider

$amount = isset($_GET['amount']) ? (float) $_GET['amount'] : 100;

// Build the data array
$data = [
    "app_id" => $app_id,
    "trade_type" => "INRUPI",  // ask service provider
    "order_sn" =>  "p".time(),
    "money" => ($amount * 100), // Amount in paise (₹100 ),
    "notify_url" => "http://69.62.70.178/notify.php", // Your notify URL
    "ip" =>$_SERVER['REMOTE_ADDR'], // if u dont have ip then use 0.0.0.0  
    "remark" => "Test order from PHP", // fill your remark
];

// Add the signature
$data["sign"] = md5_sign($data, $secret_key);

// Prepare cURL request
$url = "https://www.lg-pay.com/api/order/create";
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded"
]);

// Execute and capture the response
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'request_data' => $data,
        'response' => json_decode($response, true),
        'order_sn' => $data["order_sn"]
    ]);
}

// Close cURL
curl_close($ch);
?>
