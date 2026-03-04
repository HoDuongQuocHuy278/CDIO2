<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Models\Schedule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\EmployeeRequest;

class EmployeeController extends Controller
{
    public function getData(Request $request)
    {
        $query = employee::orderBy('id', 'desc');

        if ($request->has('query') && $request->query('query') != '') {
            $search = $request->query('query');
            $query->where('ho_ten', 'like', '%' . $search . '%')
                  ->orWhere('sdt', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }

        if ($request->has('paginate') && $request->query('paginate') == 'false') {
            $data = $query->get();
        } else {
            $data = $query->paginate(12);
        }

        $schedules = Schedule::all();
        $items = $request->has('paginate') && $request->query('paginate') == 'false' ? $data : $data->items();

        foreach ($items as $emp) {
            $count = 0;
            $searchString = $emp->ho_ten . ' (' . $emp->chuc_vu . ')';
            foreach ($schedules as $schedule) {
                if (is_array($schedule->pt) && in_array($searchString, $schedule->pt)) {
                    $count++;
                }
            }
            $emp->so_buoi_lam = $count * 4; // Assuming 4 weeks per month
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function addData(EmployeeRequest $request)
    {
        $data = employee::create($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Thêm nhân viên ' . $data->full_name . ' thành công',
            'data' => $data
        ]);
    }

    public function update(EmployeeRequest $request)
    {
        $data = employee::find($request->id);
        if ($data) {
            $data->update($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật nhân viên ' . $data->full_name . ' thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy nhân viên'
        ]);
    }

    public function destroy(Request $request)
    {
        $data = employee::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa nhân viên ' . $data->full_name . ' thành công'
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
