<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CheckinController extends Controller
{
    public function checkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        // Kiểm tra xem member đã check-in chưa check-out chưa
        $activeCheckin = Checkin::where('member_id', $request->member_id)
            ->whereNull('thoi_gian_ra')
            ->first();

        if ($activeCheckin) {
            return response()->json([
                'status' => false,
                'message' => 'Thành viên đã check-in, vui lòng check-out trước'
            ], 400);
        }

        $checkin = Checkin::create([
            'member_id' => $request->member_id,
            'thoi_gian_vao' => Carbon::now(),
        ]);

        $member = Member::find($request->member_id);

        return response()->json([
            'status' => true,
            'message' => 'Check-in thành công',
            'data' => [
                'checkin' => $checkin,
                'member' => $member
            ]
        ], 201);
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        $checkin = Checkin::where('member_id', $request->member_id)
            ->whereNull('thoi_gian_ra')
            ->first();

        if (!$checkin) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy check-in đang hoạt động'
            ], 404);
        }

        $checkin->update([
            'thoi_gian_ra' => Carbon::now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Check-out thành công',
            'data' => $checkin
        ]);
    }

    public function todayCheckins()
    {
        $checkins = Checkin::with('member')
            ->whereDate('thoi_gian_vao', Carbon::today())
            ->orderBy('thoi_gian_vao', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $checkins
        ]);
    }

    public function activeCheckins()
    {
        $checkins = Checkin::with('member')
            ->whereNull('thoi_gian_ra')
            ->orderBy('thoi_gian_vao', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $checkins
        ]);
    }
}
