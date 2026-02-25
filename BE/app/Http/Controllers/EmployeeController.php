<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function getData()
    {
        $data = employee::orderBy('id', 'desc')->paginate(12);
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = employee::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Thêm nhân viên thành công',
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {
        $data = employee::find($request->id);
        if ($data) {
            $data->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật nhân viên thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy nhân viên'
        ]);
    }

    public function delete(Request $request)
    {
        $data = employee::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa nhân viên thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy nhân viên'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $data = employee::find($request->id);
        if ($data) {
            $data->status = !$data->status;
            $data->save();
            return response()->json([
                'status' => true,
                'message' => 'Đổi trạng thái thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy nhân viên'
        ]);
    }
}
