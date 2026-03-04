<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateMemberRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function getAllMembers()
    {
        $data = Member::select('id', 'full_name', 'phone')->get();
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getData()
    {
        $currentMonth = date('m');
        $currentYear = date('Y');

        $data = Member::withCount(['checkIns as monthly_checkins' => function ($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('created_at', $currentMonth)
                  ->whereYear('created_at', $currentYear);
        }])
        ->addSelect(['last_checkin' => \App\Models\CheckIn::select('created_at')
            ->whereColumn('member_id', 'members.id')
            ->orderByDesc('created_at')
            ->limit(1)
        ])
        ->withSum(['invoices as monthly_spending' => function ($query) use ($currentMonth, $currentYear) {
            $query->whereMonth('created_at', $currentMonth)
                  ->whereYear('created_at', $currentYear);
        }], 'amount')
        ->orderBy('id', 'desc')
        ->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getServicePackages()
    {
        $packages = \App\Models\Service::where('status', 1)->get();
        return response()->json([
            'status' => true,
            'data' => $packages,
        ]);
    }

    public function addData(CreateMemberRequest $request)
    {
        $input = $request->validated();

        // Mapping frontend 'thoi_han' to 'package_duration'
        if (isset($input['thoi_han'])) {
            $input['package_duration'] = $input['thoi_han'];
        }

        // Calculate end_date if not provided
        if (empty($input['end_date']) && !empty($input['start_date']) && !empty($input['package_duration'])) {
            $start_date = new \DateTime($input['start_date']);
            $duration = (int)$input['package_duration'];
            $input['end_date'] = $start_date->modify("+$duration month")->format('Y-m-d');
        }

        // Handle service information and package price
        if (!empty($input['package_id'])) {
            $package = \App\Models\Service::find($input['package_id']);
            if ($package) {
                $input['package_name'] = $package->ten_dich_vu;
                $input['service_id'] = $package->id;
                
                if (empty($input['package_price'])) {
                    $input['package_price'] = $package->gia_tien * ($input['package_duration'] ?? 1);
                }
            } else {
                \Log::warning("DEBUG_MB: Package NOT found for ID: " . $input['package_id']);
            }
        }

        // Calculate end_date if not provided or if start_date/package_duration changed
        if (empty($input['end_date']) && !empty($input['start_date']) && !empty($input['package_duration'])) {
            $start_date = new \DateTime($input['start_date']);
            $duration = (int)$input['package_duration'];
            $input['end_date'] = $start_date->modify("+$duration month")->format('Y-m-d');
        }

        \Log::info("DEBUG_MB: Final input before create: ", $input);
        
        try {
            $data = Member::create($input);
            \Log::info("DEBUG_MB: Member created successfully with ID: " . $data->id);
        } catch (\Exception $e) {
            \Log::error("DEBUG_MB: Failed to create member: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Lỗi tạo thành viên: ' . $e->getMessage(),
            ]);
        }
        
        $invoice = null;

        // Auto Create Invoice
        try {
            \Log::info("DEBUG_MB: Attempting to create invoice for member " . $data->id);
            $admin = Auth::guard('admin')->user();
            $invoice = \App\Models\Invoice::create([
                'code'       => 'HD' . time(),
                'customer'   => $data->full_name,
                'staff'      => $admin ? $admin->ho_ten : 'Hệ thống',
                'date'       => date('Y-m-d'),
                'amount'     => $input['package_price'] ?? 0,
                'method'     => 'Tiền mặt',
                'status'     => 'paid',
                'service_id' => $input['service_id'] ?? null,
                'package_id' => $input['package_id'] ?? null,
            ]);
            \Log::info("DEBUG_MB: Invoice created successfully: " . $invoice->id);
        } catch (\Exception $e) {
            \Log::error("DEBUG_MB: Failed to auto-create invoice: " . $e->getMessage());
        }

        // Handle Face Image if provided
        if ($request->hasFile('face_image') || $request->has('face_image')) {
            \Log::info("DEBUG_MB: Processing face image for ID: " . $data->id);
            try {
                $this->saveMemberFace($data->id, $request);
                \Log::info("DEBUG_MB: Face image saved.");
            } catch (\Exception $e) {
                \Log::error("DEBUG_MB: Failed to save face image: " . $e->getMessage());
            }

            // Notify AI service to reload (with short timeout to avoid blocking)
            try {
                \Log::info("DEBUG_MB: Notifying AI service to reload");
                \Illuminate\Support\Facades\Http::timeout(1)->get('http://localhost:5000/reload');
            } catch (\Exception $e) {
                \Log::warning("DEBUG_MB: AI service notification failed: " . $e->getMessage());
            }
        }

        \Log::info("DEBUG_MB: [END] Sending successful response.");
        return response()->json([
            'status' => true,
            'message' => 'Thêm thành viên thành công!',
            'invoice' => $invoice,
        ]);
    }

    private function saveMemberFace($id, Request $request)
    {
        $dir = storage_path('app/public/member_faces');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        \Log::info("Attempting to save face for member $id");

        if ($request->hasFile('face_image')) {
            \Log::info("Face image received as file");
            $request->file('face_image')->move($dir, $id . '.jpg');
        } elseif ($request->has('face_image') && is_string($request->face_image)) {
            \Log::info("Face image received as string (base64)");
            $data = $request->face_image;
            if (strpos($data, ',') !== false) {
                $data = explode(',', $data)[1];
            }
            try {
                file_put_contents($dir . '/' . $id . '.jpg', base64_decode($data));
                \Log::info("Face image saved successfully to " . $dir . '/' . $id . '.jpg');
            } catch (\Exception $e) {
                \Log::error("Failed to save face image: " . $e->getMessage());
            }
        } else {
            \Log::warning("No face_image found in request for member $id");
        }
    }

    public function update(Request $request) // Reusing Request for now as update rules might differ
    {
        $data = Member::find($request->id);
        if ($data) {
            $input = $request->all();
            
            // Sync duration
            if (isset($input['thoi_han'])) {
                $input['package_duration'] = $input['thoi_han'];
            }

            // Sync package details if package_id changed
            if (!empty($input['package_id'])) {
                $package = \App\Models\Service::find($input['package_id']);
                if ($package) {
                    $input['package_name'] = $package->ten_dich_vu;
                    $input['service_id'] = $package->id;
                    $input['service_name'] = $package->ten_dich_vu;
                }
            }

            // Calculate end_date if start_date or duration changed
            if (isset($input['start_date']) || isset($input['package_duration'])) {
                $start = new \DateTime($input['start_date'] ?? $data->start_date);
                $dur = (int)($input['package_duration'] ?? $data->package_duration ?? 1);
                $input['end_date'] = $start->modify("+$dur month")->format('Y-m-d');
            }

            $data->update($input);

            if ($request->has('face_image')) {
                $this->saveMemberFace($data->id, $request);
                try {
                    \Illuminate\Support\Facades\Http::get('http://localhost:5000/reload');
                } catch (\Exception $e) {}
            }

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật thành viên ' . $data->full_name . ' thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy thành viên'
        ]);
    }

    public function destroy(Request $request)
    {
        $data = Member::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa thành viên ' . $data->full_name . ' thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy thành viên'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $data = Member::find($request->id);
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
            'message' => 'Không tìm thấy thành viên'
        ]);
    }
}
