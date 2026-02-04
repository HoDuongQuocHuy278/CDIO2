<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateMemberRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
   public function getMember()
   {
        $member = Member::join('services', 'members.service_id', '=', 'services.id')
                        ->join('service_packages', 'services.id', '=', 'service_packages.service_id')
                        ->select('members.full_name','members.phone','members.email','services.ten_dich_vu','service_packages.gia_tien','service_packages.ten_goi','members.start_date','members.end_date','members.warning_level')
                        ->get();
        return response()->json([
            'message' => 'Lấy dữ liệu Member thành công',
            'status' => true,
            'data' => $member,
        ]);
   }

   public function storeMember(CreateMemberRequest $request)
   {
        $user = Auth::guard('sanctum')->user();
        $data = Member::create([
            'full_name'        => $request->full_name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'address'          => $request->address,
            'avatar'           => $request->avatar,
            'status'           => $request->status ?? Member::HOAT_DONG,
            'service_id'       => $request->service_id,
            'service_name'     => $request->ten_dich_vu,
            'package_name'     => $request->ten_goi,
            'package_price'    => $request->gia_tien,
            'package_duration' => $request->package_duration,

            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'absent_days'      => 0,
            'warning_level'    => 0,

        ]);
        return response()->json([
            'status' => true,
            'message' => 'Thêm Member thành công',
            'data' => $data
        ]);
   }





}
