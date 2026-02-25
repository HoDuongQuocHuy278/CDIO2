<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateMemberRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function getData()
    {
        $data = Member::orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getServicePackages()
    {
        $packages = \App\Models\ServicePackage::all();
        return response()->json([
            'status' => true,
            'data' => $packages,
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

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
            $package = \App\Models\ServicePackage::find($input['package_id']);
            if ($package) {
                $input['package_name'] = $package->ten_goi;
                $input['service_id'] = $package->service_id;
                
                // If package_price not provided by FE (e.g. legacy), calculate it
                if (empty($input['package_price'])) {
                    $input['package_price'] = $package->gia_tien * ($input['package_duration'] ?? 1);
                }

                $service = \App\Models\Service::find($package->service_id);
                if ($service) {
                    $input['service_name'] = $service->ten_dich_vu;
                }
            }
        }

        // Ensure status is set
        if (!isset($input['status'])) {
            $input['status'] = 1;
        }

        $data = Member::create($input);

        // Handle Face Image if provided
        if ($request->hasFile('face_image') || $request->has('face_image')) {
            $this->saveMemberFace($data->id, $request);
            // Notify AI service to reload
            try {
                \Illuminate\Support\Facades\Http::get('http://localhost:5000/reload');
            } catch (\Exception $e) {}
        }

        return response()->json([
            'status' => true,
            'message' => 'Thêm thành viên thành công',
            'data' => $data
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

    public function update(Request $request)
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
                $package = \App\Models\ServicePackage::find($input['package_id']);
                if ($package) {
                    $input['package_name'] = $package->ten_goi;
                    $input['service_id'] = $package->service_id;
                    
                    $service = \App\Models\Service::find($package->service_id);
                    if ($service) {
                        $input['service_name'] = $service->ten_dich_vu;
                    }
                }
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
                'message' => 'Cập nhật thành viên thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy thành viên'
        ]);
    }

    public function delete(Request $request)
    {
        $data = Member::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa thành viên thành công'
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
