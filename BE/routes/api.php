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






Route::post('admin/login', [AdminController::class, 'loginAdmin']);

Route::group(['prefix' => 'admin','middleware' => 'AdminMiddleware'], function () {
    Route::get('/check-token', [AdminController::class, 'checkTokenAdmin']);
    Route::get('/member', [MemberController::class, 'getMember']);
    Route::get('/products', [ProductController::class, 'getProducts']);
    Route::get('/employees', [EmployeeController::class, 'getEmployee']);
    Route::get('/services', [ServiceController::class, 'getServices']);
    Route::get('/equipments', [EquipmentsController::class, 'getEquipments']);
    Route::post('/member/create', [MemberController::class, 'storeMember']);







});


