# Gym Management System

Hệ thống quản lý phòng gym toàn diện với Laravel Backend và Vue.js Frontend.

## 🎯 Tính năng chính

### Quản lý thành viên
- ✅ Thêm, sửa, xóa thành viên
- ✅ Quản lý thẻ thành viên
- ✅ Theo dõi lịch sử tập luyện
- ✅ Check-in/Check-out

### Quản lý nhân viên
- ✅ Quản lý thông tin nhân viên
- ✅ Phân ca làm việc
- ✅ Phân quyền theo vai trò

### Quản lý dịch vụ
- ✅ Quản lý các dịch vụ gym
- ✅ Quản lý gói tập (packages)
- ✅ Đăng ký gói tập cho thành viên
- ✅ Theo dõi số buổi đã tập

### Quản lý thiết bị
- ✅ Quản lý thiết bị tập luyện
- ✅ Phân loại thiết bị
- ✅ Ghi nhận bảo trì thiết bị
- ✅ Quản lý phòng tập và thiết bị

### Quản lý kho hàng
- ✅ Quản lý sản phẩm
- ✅ Nhập hàng từ nhà cung cấp
- ✅ Theo dõi tồn kho

### Quản lý hóa đơn & thanh toán
- ✅ Tạo hóa đơn bán hàng
- ✅ Xử lý thanh toán
- ✅ Áp dụng khuyến mãi

## 🛠️ Công nghệ sử dụng

### Backend
- **Laravel 10+** - PHP Framework
- **MySQL** - Database
- **JWT Authentication** - tymon/jwt-auth
- **RESTful API**

### Frontend
- **Vue.js 3** - Progressive JavaScript Framework
- **Vue Router 4** - Routing
- **Axios** - HTTP Client
- **Vite** - Build Tool

## 📦 Cài đặt

### Yêu cầu hệ thống
- PHP >= 8.1
- Composer
- Node.js >= 20.19.0
- MySQL >= 5.7
- XAMPP hoặc Laravel Valet

### Backend Setup

1. Di chuyển vào thư mục backend:
```bash
cd BE
```

2. Cài đặt dependencies:
```bash
composer install
```

3. Copy file `.env.example` thành `.env`:
```bash
copy .env.example .env
```

4. Cấu hình database trong file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gym_management
DB_USERNAME=root
DB_PASSWORD=
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Cài đặt JWT:
```bash
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

7. Chạy migrations và seeders:
```bash
php artisan migrate:fresh --seed
```

8. Khởi động server:
```bash
php artisan serve
```

Backend sẽ chạy tại: `http://127.0.0.1:8000`

### Frontend Setup

1. Di chuyển vào thư mục frontend:
```bash
cd FE
```

2. Cài đặt dependencies:
```bash
npm install
```

3. Khởi động development server:
```bash
npm run dev
```

Frontend sẽ chạy tại: `http://localhost:5173`

## 👤 Tài khoản mặc định

Sau khi chạy seeder, bạn có thể đăng nhập với:

**Admin:**
- Tên đăng nhập: `admin`
- Mật khẩu: `admin123`

**Nhân viên:**
- Tên đăng nhập: `nhanvien1`
- Mật khẩu: `123456`

## 📊 Database Schema

Hệ thống bao gồm 25 bảng chính:

### Quản lý người dùng
- `phan_quyen` - Vai trò/Quyền
- `nguoi_dung` - Người dùng
- `employees` - Nhân viên
- `work_shifts` - Ca làm việc
- `employee_shifts` - Phân ca

### Quản lý thành viên
- `members` - Thành viên
- `membership_cards` - Thẻ thành viên
- `checkins` - Check-in/out
- `member_sessions` - Phiên tập

### Dịch vụ
- `services` - Dịch vụ
- `service_packages` - Gói tập
- `registrations` - Đăng ký gói tập
- `promotions` - Khuyến mãi

### Thiết bị
- `equipment_types` - Loại thiết bị
- `equipments` - Thiết bị
- `rooms` - Phòng tập
- `room_equipments` - Thiết bị trong phòng
- `equipment_maintenance` - Bảo trì
- `suppliers` - Nhà cung cấp

### Kho hàng & Bán hàng
- `products` - Sản phẩm
- `inventory_imports` - Phiếu nhập
- `inventory_items` - Chi tiết nhập
- `invoices` - Hóa đơn
- `invoice_items` - Chi tiết hóa đơn
- `payments` - Thanh toán

## 🔌 API Endpoints

### Authentication
```
POST   /api/login          - Đăng nhập
POST   /api/logout         - Đăng xuất
GET    /api/me             - Thông tin user
POST   /api/refresh        - Refresh token
```

### Members (Yêu cầu authentication)
```
GET    /api/members        - Danh sách thành viên
POST   /api/members        - Thêm thành viên
GET    /api/members/{id}   - Chi tiết thành viên
PUT    /api/members/{id}   - Cập nhật thành viên
DELETE /api/members/{id}   - Xóa thành viên
```

### Check-in
```
POST   /api/checkin        - Check-in
POST   /api/checkout       - Check-out
GET    /api/checkins/today - Check-in hôm nay
GET    /api/checkins/active - Đang tập
```

## 🎨 Giao diện

### Login Page
- Giao diện đăng nhập hiện đại với gradient background
- Validation form
- Error handling

### Dashboard
- Thống kê tổng quan
- Quick actions
- User info và logout

### Members Management
- Danh sách thành viên với search
- CRUD operations
- Modal form
- Responsive table

## 📝 Hướng dẫn phát triển tiếp

### Thêm controller mới

1. Tạo controller:
```bash
php artisan make:controller ServiceController
```

2. Implement CRUD methods
3. Thêm routes vào `routes/api.php`
4. Tạo Vue component tương ứng

### Thêm migration mới

```bash
php artisan make:migration create_table_name
php artisan migrate
```

### Thêm model mới

```bash
php artisan make:model ModelName
```

## 🔒 Bảo mật

- JWT Authentication cho API
- Password hashing với bcrypt
- CORS configuration
- Input validation
- SQL injection protection (Eloquent ORM)

## 📱 Responsive Design

- Mobile-friendly interface
- Flexible grid layouts
- Touch-friendly buttons
- Adaptive tables

## 🚀 Production Deployment

### Backend
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Frontend
```bash
npm run build
```

## 📄 License

MIT License

## 👨‍💻 Developer

Developed for CDIO 2 Project

---

**Happy Coding! 🏋️‍♂️💪**
