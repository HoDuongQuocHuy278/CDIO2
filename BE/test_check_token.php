<?php
// Step 1: Login to get token
$login_url = 'http://127.0.0.1:8000/api/admin/login';
$login_data = ['so_dien_thoai' => '0813559551', 'password' => '123456'];

$login_options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($login_data),
        'ignore_errors' => true
    ],
];
$login_context  = stream_context_create($login_options);
$login_result = file_get_contents($login_url, false, $login_context);
$login_response = json_decode($login_result, true);

if (!isset($login_response['token'])) {
    die("Login failed: " . $login_result);
}

$token = $login_response['token'];
echo "Token obtained: " . $token . "\n";

// Step 2: Check token
$check_url = 'http://127.0.0.1:8000/api/admin/check-token';
$check_options = [
    'http' => [
        'header'  => "Authorization: Bearer $token\r\nAccept: application/json\r\n",
        'method'  => 'GET',
        'ignore_errors' => true
    ],
];
$check_context  = stream_context_create($check_options);
$check_result = file_get_contents($check_url, false, $check_context);

echo "Check Token Response: " . $check_result . "\n";
