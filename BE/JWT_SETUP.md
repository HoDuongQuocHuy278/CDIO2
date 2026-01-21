# JWT Authentication Setup Guide

## Bước 1: Cài đặt JWT package

```bash
composer require tymon/jwt-auth
```

## Bước 2: Publish config

```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

## Bước 3: Generate JWT secret

```bash
php artisan jwt:secret
```

## Bước 4: Cấu hình auth.php

Mở file `config/auth.php` và cập nhật:

```php
'defaults' => [
    'guard' => 'api',
    'passwords' => 'users',
],

'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\NguoiDung::class,
    ],
],
```

## Bước 5: Update Model

Model `NguoiDung` đã implement `JWTSubject` interface với các methods:
- `getJWTIdentifier()`
- `getJWTCustomClaims()`

## Bước 6: Test JWT

Sau khi setup xong, test bằng cách:

1. Login:
```bash
POST http://127.0.0.1:8000/api/login
Content-Type: application/json

{
    "ten_dang_nhap": "admin",
    "mat_khau": "admin123"
}
```

2. Sử dụng token nhận được trong header:
```bash
Authorization: Bearer {your-token-here}
```

## Troubleshooting

### Lỗi "Class 'Tymon\JWTAuth\Providers\LaravelServiceProvider' not found"
- Chạy: `composer dump-autoload`
- Kiểm tra `composer.json` đã có package `tymon/jwt-auth`

### Lỗi "JWT secret not set"
- Chạy: `php artisan jwt:secret`
- Kiểm tra file `.env` có `JWT_SECRET`

### Token không hoạt động
- Clear cache: `php artisan config:clear`
- Kiểm tra model đã implement `JWTSubject`
- Kiểm tra guard trong `config/auth.php`
