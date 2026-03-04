<?php

namespace App\Http\Controllers;

use App\Models\equipments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\EquipmentRequest;

class EquipmentsController extends Controller
{
    public function getData()
    {
        $data = equipments::with(['type', 'supplier', 'room'])
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function addData(EquipmentRequest $request)
    {
        $data = equipments::create($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Thêm thiết bị ' . $data->name . ' thành công',
            'data' => $data
        ]);
    }

    public function update(EquipmentRequest $request)
    {
        $data = equipments::find($request->id);
        if ($data) {
            $data->update($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật thiết bị ' . $data->name . ' thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy thiết bị'
        ]);
    }

    public function destroy(Request $request)
    {
        $data = equipments::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa thiết bị ' . $data->name . ' thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy thiết bị'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $data = equipments::find($request->id);
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
            'message' => 'Không tìm thấy thiết bị'
        ]);
    }
}
