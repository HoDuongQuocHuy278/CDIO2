<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EquipmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckInController;

Route::post('admin/login', [AdminController::class, 'loginAdmin'])->middleware('throttle:login');
Route::group(['prefix' => 'admin', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/check-token', [AdminController::class, 'checkTokenAdmin']);

    // Thành viên (Member)
    Route::group(['prefix' => 'thanh-vien'], function () {
        Route::get('/get-data', [MemberController::class, 'getData']);
        Route::get('/get-all', [MemberController::class, 'getAllMembers']);
        Route::get('/get-packages', [MemberController::class, 'getServicePackages']);
        Route::post('/add-data', [MemberController::class, 'addData']);
        Route::post('/update', [MemberController::class, 'update']);
        Route::post('/destroy', [MemberController::class, 'destroy']);
        Route::post('/change-status', [MemberController::class, 'changeStatus']);
    });

    // Dịch vụ (Service)
    Route::group(['prefix' => 'dich-vu'], function () {
        Route::get('/get-data', [ServiceController::class, 'getData']);
        Route::post('/add-data', [ServiceController::class, 'addData']);
        Route::post('/update', [ServiceController::class, 'update']);
        Route::post('/destroy', [ServiceController::class, 'destroy']);
        Route::post('/change-status', [ServiceController::class, 'changeStatus']);
    });

    // Nhân viên (Staff/Employee)
    Route::group(['prefix' => 'nhan-vien'], function () {
        Route::get('/get-data', [EmployeeController::class, 'getData']);
        Route::post('/add-data', [EmployeeController::class, 'addData']);
        Route::post('/update', [EmployeeController::class, 'update']);
        Route::post('/destroy', [EmployeeController::class, 'destroy']);
        Route::post('/change-status', [EmployeeController::class, 'changeStatus']);
    });

    // Thiết bị (Devices/Equipments)
    Route::group(['prefix' => 'thiet-bi'], function () {
        Route::get('/get-data', [EquipmentsController::class, 'getData']);
        Route::post('/add-data', [EquipmentsController::class, 'addData']);
        Route::post('/update', [EquipmentsController::class, 'update']);
        Route::post('/destroy', [EquipmentsController::class, 'destroy']);
        Route::post('/change-status', [EquipmentsController::class, 'changeStatus']);
    });

    // Sản phẩm (Product)
    Route::group(['prefix' => 'san-pham'], function () {
        Route::get('/get-data', [ProductController::class, 'getData']);
        Route::post('/add-data', [ProductController::class, 'addData']);
        Route::post('/update', [ProductController::class, 'update']);
        Route::post('/destroy', [ProductController::class, 'destroy']);
        Route::post('/change-status', [ProductController::class, 'changeStatus']);
    });

    // Hóa đơn (Invoice)
    Route::prefix('hoa-don')->group(function () {
        Route::get('get-data', [InvoiceController::class, 'getData']);
        Route::post('add-data', [InvoiceController::class, 'addData']);
        Route::post('update', [InvoiceController::class, 'update']);
        Route::post('destroy', [InvoiceController::class, 'destroy']);
    });

    // Doanh thu (Revenue)
    Route::prefix('doanh-thu')->group(function () {
        Route::get('get-data', [RevenueController::class, 'getData']);
        Route::post('add-data', [RevenueController::class, 'addData']);
    });
    
    // Thống kê Doanh Thu
    Route::prefix('thong-ke')->group(function () {
        Route::post('dich-vu', [RevenueController::class, 'thongKeDichVu']);
    });

    // Lịch làm (Schedule)
    Route::prefix('lich-lam')->group(function () {
        Route::get('get-data', [ScheduleController::class, 'getData']);
        Route::post('add-data', [ScheduleController::class, 'addData']);
        Route::post('update', [ScheduleController::class, 'update']);
        Route::post('destroy', [ScheduleController::class, 'destroy']);
    });

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);

    // Check-in
    Route::group(['prefix' => 'check-in'], function () {
        Route::get('/get-data', [CheckInController::class, 'getData']);
        Route::get('/search-member', [CheckInController::class, 'searchMember']);
        Route::post('/add-data', [CheckInController::class, 'store']);
        Route::post('/recognize', [CheckInController::class, 'recognizeFace']);
    });
});


