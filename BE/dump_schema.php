<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schema = \Illuminate\Support\Facades\DB::select('DESCRIBE members');
file_put_contents('schema_output.txt', print_r($schema, true));
