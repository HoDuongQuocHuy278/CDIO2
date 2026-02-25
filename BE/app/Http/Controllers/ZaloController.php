<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZaloController extends Controller
{
    /**
     * Send Zalo Template Message
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTemplateMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'customer_name' => 'required|string',
            // Add other validations if necessary
        ]);

        $accessToken = env('ZALO_ACCESS_TOKEN');
        $templateId = env('ZALO_TEMPLATE_ID', '7895417a7d3f9461cd2e');

        $days = $request->days_absent ?? 0;
        $warningMessage = "";
        if ($days > 0) {
            $warningMessage = "CẢNH BÁO: Bạn đã nghỉ tập {$days} ngày. Đừng quên ghé gym hôm nay để duy trì sức khỏe nhé!";
        } else {
            $warningMessage = "Nhắc nhở: Chúc bạn có một buổi tập luyện hăng say tại Gym Center nhé!";
        }

        // Logic Mock Mode trigger
        if (!$accessToken || $accessToken === 'your_access_token_here') {
            Log::info('Zalo Mock Mode: Tin nhắn giả lập đã được gửi', [
                'phone' => $request->phone,
                'customer' => $request->customer_name,
                'warning' => $warningMessage
            ]);

            return response()->json([
                'status' => true,
                'message' => "[Mock Mode] {$warningMessage}",
                'data' => [
                    'error' => 0,
                    'message' => 'Success',
                    'data' => [
                        'msg_id' => 'mock_' . uniqid(),
                        'sent_content' => $warningMessage
                    ]
                ]
            ]);
        }

        $payload = [
            "phone" => $request->phone,
            "template_id" => $templateId,
            "template_data" => [
                "ky" => $request->ky ?? "1",
                "thang" => $request->thang ?? date('m/Y'),
                "start_date" => $request->start_date ?? date('d/m/Y'),
                "end_date" => $request->end_date ?? date('d/m/Y', strtotime('+1 month')),
                "customer" => $request->customer_name,
                "cid" => $request->cid ?? "PE010299485",
                "address" => $warningMessage, // Map warning to address/note field
                "amount" => $request->amount ?? "0",
                "total" => $request->total ?? "0",
            ],
            "tracking_id" => $request->tracking_id ?? "tracking_" . time()
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'access_token' => $accessToken
            ])->post('https://business.openapi.zalo.me/message/template', $payload);

            if ($response->successful()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Gửi tin nhắn Zalo thành công',
                    'data' => $response->json()
                ]);
            }

            Log::error('Zalo API Error', ['response' => $response->json()]);
            return response()->json([
                'status' => false,
                'message' => 'Gửi tin nhắn Zalo thất bại',
                'error' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Zalo Integration Exception', ['message' => $e->getMessage()]);
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra khi kết nối Zalo API',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
