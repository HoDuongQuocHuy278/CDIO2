<?php
$ch = curl_init('http://127.0.0.1:8000/api/admin/check-token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer 26|0Oyb4SYpeGmaDUyQw89E4YGmuBkNPRDgaOpDyt8ebbfe7ced',
    'Accept: application/json'
]);
$response = curl_exec($ch);
echo "Response: " . $response . "\n";
$info = curl_getinfo($ch);
echo "HTTP Code: " . $info['http_code'] . "\n";
curl_close($ch);
