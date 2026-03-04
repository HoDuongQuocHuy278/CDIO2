<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AdminController extends Controller
{
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'so_dien_thoai' => 'required',
            'password'      => 'required',
        ], [
            'so_dien_thoai.required' => 'Số điện thoại không được để trống',
            'password.required'      => 'Mật khẩu không được để trống',
        ]);

        $check = Auth::guard('admin')->attempt([
            'so_dien_thoai' => $request->so_dien_thoai,
            'password' => $request->password
        ]);
        if($check){
            $admin = Auth::guard('admin')->user();
            return response()->json([
                'status' => true,
                'message' => 'Đăng nhập thành công',
                'token' => $admin->createToken('token_admin')->plainTextToken,
                'admin' => [
                    'ho_ten'   => $admin->ho_ten,
                    'hinh_anh' => $admin->hinh_anh,
                ]
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Số điện thoại hoặc mật khẩu không đúng'
            ]);
        }
    }

    public function checkTokenAdmin(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        if ($user && $user instanceof \App\Models\Admin) {
            return response()->json([
                'status' => true,
                'ho_ten'    => $user->ho_ten,
                'hinh_anh'  => $user->hinh_anh,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Bạn cần đăng nhập hệ thống!',
            ]);
        }
    }
    

}
