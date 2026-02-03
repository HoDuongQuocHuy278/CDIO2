<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
   public function getMember(Request $request)
   {
        $member = Member::all();
        return response()->json([
            'message' => 'Lấy dữ liệu Member thành công',
            'status' => true,
            'data' => $member,
        ]);

   }
}
