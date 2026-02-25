<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServicePackage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function getData()
    {
        $data = Service::orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = Service::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Thêm dịch vụ thành công',
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {
        $data = Service::find($request->id);
        if ($data) {
            $data->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật dịch vụ thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy dịch vụ'
        ]);
    }

    public function delete(Request $request)
    {
        $data = Service::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa dịch vụ thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy dịch vụ'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $data = Service::find($request->id);
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
            'message' => 'Không tìm thấy dịch vụ'
        ]);
    }
}
