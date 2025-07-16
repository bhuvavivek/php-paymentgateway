<?php
$order_sn = $_GET['order_sn'] ?? '';
$order_sn = basename($order_sn); // Sanitize to prevent path traversal

$filepath = __DIR__ . "/payments/$order_sn.json";

header('Content-Type: application/json');

if (file_exists($filepath)) {
    $data = json_decode(file_get_contents($filepath), true);
    echo json_encode([
        'status' => $data['status'],
        'amount' => $data['amount'],
        'timestamp' => $data['timestamp']
    ]);
} else {
    echo json_encode(['status' => 'not_found']);
}
