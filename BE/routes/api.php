<?php



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


Route::post('admin/login', [AdminController::class, 'loginAdmin']);

Route::group(['prefix' => 'admin','middleware' => 'AdminMiddleware'], function () {

    Route::get('/check-token', [AdminController::class, 'checkTokenAdmin']);


});


