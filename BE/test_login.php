<?php
$url = 'http://127.0.0.1:8000/api/admin/login';
$data = ['so_dien_thoai' => '0813559551', 'password' => '123456'];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true
    ],
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo "HTTP Status: " . $http_response_header[0] . "\n";
echo "Response body: " . $result . "\n";
