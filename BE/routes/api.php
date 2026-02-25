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

Route::post('admin/login', [AdminController::class, 'loginAdmin']);

Route::group(['prefix' => 'admin'], function () {
    Route::get('/check-token', [AdminController::class, 'checkTokenAdmin']);

    // Thành viên (Member)
    Route::group(['prefix' => 'thanh-vien'], function () {
        Route::get('/get-data', [MemberController::class, 'getData']);
        Route::get('/get-packages', [MemberController::class, 'getServicePackages']);
        Route::post('/add-data', [MemberController::class, 'store']);
        Route::post('/update', [MemberController::class, 'update']);
        Route::post('/delete', [MemberController::class, 'delete']);
        Route::post('/change-status', [MemberController::class, 'changeStatus']);
    });

    // Dịch vụ (Service)
    Route::group(['prefix' => 'dich-vu'], function () {
        Route::get('/get-data', [ServiceController::class, 'getData']);
        Route::post('/add-data', [ServiceController::class, 'store']);
        Route::post('/update', [ServiceController::class, 'update']);
        Route::post('/delete', [ServiceController::class, 'delete']);
        Route::post('/change-status', [ServiceController::class, 'changeStatus']);
    });

    // Nhân viên (Staff/Employee)
    Route::group(['prefix' => 'nhan-vien'], function () {
        Route::get('/get-data', [EmployeeController::class, 'getData']);
        Route::post('/add-data', [EmployeeController::class, 'store']);
        Route::post('/update', [EmployeeController::class, 'update']);
        Route::post('/delete', [EmployeeController::class, 'delete']);
        Route::post('/change-status', [EmployeeController::class, 'changeStatus']);
    });

    // Thiết bị (Devices/Equipments)
    Route::group(['prefix' => 'thiet-bi'], function () {
        Route::get('/get-data', [EquipmentsController::class, 'getData']);
        Route::post('/add-data', [EquipmentsController::class, 'store']);
        Route::post('/update', [EquipmentsController::class, 'update']);
        Route::post('/delete', [EquipmentsController::class, 'delete']);
        Route::post('/change-status', [EquipmentsController::class, 'changeStatus']);
    });

    // Sản phẩm (Product)
    Route::group(['prefix' => 'san-pham'], function () {
        Route::get('/get-data', [ProductController::class, 'getData']);
        Route::post('/add-data', [ProductController::class, 'store']);
        Route::post('/update', [ProductController::class, 'update']);
        Route::post('/delete', [ProductController::class, 'delete']);
        Route::post('/change-status', [ProductController::class, 'changeStatus']);
    });

    // Hóa đơn (Invoice)
    Route::group(['prefix' => 'hoa-don'], function () {
        Route::get('/get-data', [InvoiceController::class, 'getData']);
        Route::post('/add-data', [InvoiceController::class, 'store']);
        Route::post('/update', [InvoiceController::class, 'update']);
        Route::post('/delete', [InvoiceController::class, 'delete']);
    });

    // Doanh thu (Revenue)
    Route::group(['prefix' => 'doanh-thu'], function () {
        Route::get('/get-data', [RevenueController::class, 'getData']);
        Route::post('/add-data', [RevenueController::class, 'store']);
    });

    // Lịch làm (Schedule)
    Route::group(['prefix' => 'lich-lam'], function () {
        Route::get('/get-data', [ScheduleController::class, 'getData']);
        Route::post('/add-data', [ScheduleController::class, 'store']);
        Route::post('/update', [ScheduleController::class, 'update']);
        Route::post('/delete', [ScheduleController::class, 'delete']);
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


