<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = \App\Models\Admin::first();
echo "Admin Phone: " . $admin->so_dien_thoai . "\n";
echo "Admin Name: " . $admin->ho_ten . "\n";
$admin->password = bcrypt('123456');
$admin->save();
echo "Password reset to 123456\n";
