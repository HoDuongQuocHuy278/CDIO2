<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ScheduleRequest;

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

    public function addData(ScheduleRequest $request)
    {
        $data = Schedule::create($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Thêm lịch làm thành công',
            'data' => $data
        ]);
    }

    public function update(ScheduleRequest $request)
    {
        $data = Schedule::find($request->id);
        if ($data) {
            $data->update($request->validated());
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

    public function destroy(Request $request)
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
