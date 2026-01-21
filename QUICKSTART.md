# Quick Start Guide - Gym Management System

## 🚀 Khởi động nhanh (5 phút)

### Bước 1: Chuẩn bị Database

1. Mở XAMPP và start Apache + MySQL
2. Tạo database mới tên `gym_management`:
   - Vào phpMyAdmin: http://localhost/phpmyadmin
   - Click "New" → Nhập tên: `gym_management` → Create

### Bước 2: Setup Backend

```bash
# Di chuyển vào thư mục BE
cd "c:\xampp\htdocs\CDIO 2\CDIO2\BE"

# Cài đặt dependencies (nếu chưa cài)
composer install

# Copy .env file (nếu chưa có)
copy .env.example .env

# Generate app key
php artisan key:generate

# Cài đặt JWT
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret

# Chạy migrations và seeders
php artisan migrate:fresh --seed

# Start server
php artisan serve
```

Backend sẽ chạy tại: **http://127.0.0.1:8000**

### Bước 3: Setup Frontend

Mở terminal mới:

```bash
# Di chuyển vào thư mục FE
cd "c:\xampp\htdocs\CDIO 2\CDIO2\FE"

# Cài đặt dependencies (nếu chưa cài)
npm install

# Start dev server
npm run dev
```

Frontend sẽ chạy tại: **http://localhost:5173**

### Bước 4: Đăng nhập

1. Mở browser: http://localhost:5173
2. Trang login sẽ hiển thị
3. Đăng nhập với:
   - **Tên đăng nhập**: `admin`
   - **Mật khẩu**: `admin123`

### Bước 5: Test chức năng

1. Sau khi đăng nhập, bạn sẽ thấy Dashboard
2. Click "Quản lý thành viên" để test CRUD operations
3. Thử thêm, sửa, xóa thành viên

---

## 🔧 Troubleshooting

### Lỗi: "SQLSTATE[HY000] [1049] Unknown database"
**Giải pháp**: Chưa tạo database
```bash
# Tạo database trong MySQL
mysql -u root -p
CREATE DATABASE gym_management;
exit;
```

### Lỗi: "Class 'Tymon\JWTAuth\...' not found"
**Giải pháp**: Chưa cài JWT
```bash
cd BE
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

### Lỗi: "npm: command not found"
**Giải pháp**: Chưa cài Node.js
- Download và cài đặt Node.js từ: https://nodejs.org/
- Version yêu cầu: >= 20.19.0

### Lỗi: Frontend không kết nối được Backend
**Giải pháp**: Kiểm tra:
1. Backend đang chạy tại http://127.0.0.1:8000
2. File `FE/src/config/axios.js` có đúng baseURL
3. CORS đã được config trong Laravel

### Lỗi: "Access denied for user 'root'@'localhost'"
**Giải pháp**: Sai password MySQL
- Mở file `BE/.env`
- Sửa `DB_PASSWORD=` (để trống nếu không có password)

---

## 📱 Test API với Postman

### 1. Login
```
POST http://127.0.0.1:8000/api/login
Content-Type: application/json

{
    "ten_dang_nhap": "admin",
    "mat_khau": "admin123"
}
```

Response sẽ có `token` - copy token này

### 2. Get Members (cần token)
```
GET http://127.0.0.1:8000/api/members
Authorization: Bearer {paste-token-here}
```

### 3. Create Member
```
POST http://127.0.0.1:8000/api/members
Authorization: Bearer {your-token}
Content-Type: application/json

{
    "ho_ten": "Nguyễn Văn A",
    "sdt": "0123456789",
    "ngay_sinh": "1990-01-01",
    "dia_chi": "Hà Nội",
    "email": "nguyenvana@gmail.com"
}
```

---

## 🎯 Các tính năng đã hoàn thành

✅ Đăng nhập/Đăng xuất với JWT  
✅ Dashboard với thống kê  
✅ Quản lý thành viên (CRUD đầy đủ)  
✅ Search/Filter thành viên  
✅ Check-in/Check-out API  

## 🚧 Đang phát triển

🔨 Quản lý dịch vụ & gói tập  
🔨 Giao diện check-in  
🔨 Quản lý thiết bị  
🔨 Quản lý kho hàng  
🔨 Hóa đơn & thanh toán  

---

## 📞 Support

Nếu gặp vấn đề, kiểm tra:
1. [README.md](file:///c:/xampp/htdocs/CDIO%202/CDIO2/README.md) - Hướng dẫn chi tiết
2. [JWT_SETUP.md](file:///c:/xampp/htdocs/CDIO%202/CDIO2/BE/JWT_SETUP.md) - Cấu hình JWT
3. [walkthrough.md](file:///C:/Users/LENOVO/.gemini/antigravity/brain/dcf8efbe-22e0-4823-b907-460087c79c8c/walkthrough.md) - Chi tiết implementation

**Happy Coding! 🏋️‍♂️**
