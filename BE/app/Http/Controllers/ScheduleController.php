<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function getData()
    {
        $data = Schedule::all();
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = Schedule::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Thêm lịch làm thành công',
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {
        $data = Schedule::find($request->id);
        if ($data) {
            $data->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật lịch làm thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy lịch làm'
        ]);
    }

    public function delete(Request $request)
    {
        $data = Schedule::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa lịch làm thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy lịch làm'
        ]);
    }
}
