<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_dang_nhap' => 'required|string',
            'mat_khau' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $nguoiDung = NguoiDung::where('ten_dang_nhap', $request->ten_dang_nhap)->first();

        if (!$nguoiDung || !Hash::check($request->mat_khau, $nguoiDung->mat_khau)) {
            return response()->json([
                'status' => false,
                'message' => 'Tên đăng nhập hoặc mật khẩu không đúng'
            ], 401);
        }

        $token = JWTAuth::fromUser($nguoiDung);

        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'token' => $token,
            'data' => [
                'id' => $nguoiDung->id,
                'ten_dang_nhap' => $nguoiDung->ten_dang_nhap,
                'role' => $nguoiDung->phanQuyen,
                'employee' => $nguoiDung->employee
            ]
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'status' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    public function me()
    {
        $nguoiDung = JWTAuth::parseToken()->authenticate();

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $nguoiDung->id,
                'ten_dang_nhap' => $nguoiDung->ten_dang_nhap,
                'role' => $nguoiDung->phanQuyen,
                'employee' => $nguoiDung->employee
            ]
        ]);
    }

    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return response()->json([
            'status' => true,
            'token' => $token
        ]);
    }
}
