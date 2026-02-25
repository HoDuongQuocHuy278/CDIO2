<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckIn;
use App\Models\Member;

class CheckInController extends Controller
{
    public function getData()
    {
        $data = CheckIn::with('member')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function searchMember(Request $request)
    {
        $query = $request->query('query');
        $member = Member::where('id', $query)
            ->orWhere('phone', 'like', "%$query%")
            ->orWhere('full_name', 'like', "%$query%")
            ->first();

        if ($member) {
            return response()->json([
                'status' => true,
                'data' => $member
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy thành viên'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $data = CheckIn::create([
            'member_id' => $request->member_id,
            'check_in_type' => $request->check_in_type ?? 'FaceID',
            'status' => 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Check-in thành công',
            'data' => $data
        ]);
    }

    public function recognizeFace(Request $request)
    {
        $request->validate([
            'image' => 'required', // base64 string
        ]);

        try {
            // Forward to Python AI service
            $response = \Illuminate\Support\Facades\Http::post('http://localhost:5000/recognize', [
                'image' => $request->image,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if ($result['status'] && isset($result['member_id'])) {
                    $member = Member::find($result['member_id']);
                    if ($member) {
                        return response()->json([
                            'status' => true,
                            'data' => $member,
                            'message' => 'Nhận diện thành công'
                        ]);
                    }
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Không nhận diện được khuôn mặt'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi kết nối dịch vụ AI: ' . $e->getMessage()
            ], 500);
        }
    }
}
