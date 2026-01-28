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
        $check = Auth::guard('admin')->attempt([
            'so_dien_thoai' => $request->so_dien_thoai,
            'password' => $request->password
        ]);
        if($check){
            $admin = Auth::guard('admin')->user();
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $admin->createToken('token_admin')->plainTextToken
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
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
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Bạn cần đăng nhập hệ thống!',
            ]);
        }
    }

}
