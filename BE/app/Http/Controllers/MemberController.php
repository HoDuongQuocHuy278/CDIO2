<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with(['membershipCards', 'registrations'])->get();

        return response()->json([
            'status' => true,
            'data' => $members
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ho_ten' => 'required|string|max:255',
            'sdt' => 'required|string|max:20',
            'ngay_sinh' => 'required|date',
            'dia_chi' => 'required|string',
            'email' => 'required|email|unique:members,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $member = Member::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Thêm thành viên thành công',
            'data' => $member
        ], 201);
    }

    public function show($id)
    {
        $member = Member::with(['membershipCards', 'registrations.package.service', 'checkins', 'sessions'])->find($id);

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy thành viên'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $member
        ]);
    }

    public function update(Request $request, $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy thành viên'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'ho_ten' => 'string|max:255',
            'sdt' => 'string|max:20',
            'ngay_sinh' => 'date',
            'dia_chi' => 'string',
            'email' => 'email|unique:members,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $member->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành viên thành công',
            'data' => $member
        ]);
    }

    public function destroy($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy thành viên'
            ], 404);
        }

        $member->delete();

        return response()->json([
            'status' => true,
            'message' => 'Xóa thành viên thành công'
        ]);
    }
}
