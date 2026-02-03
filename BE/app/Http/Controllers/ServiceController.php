<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServicePackage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
   public function getServices()  
   {
        $service = Service::join('service_packages', 'services.id', '=', 'service_packages.service_id')
            ->select('services.ten_dich_vu', 'service_packages.ten_goi','service_packages.gia_tien', 'service_packages.so_buoi_tap', 'service_packages.thoi_han_ngay')
            ->where('services.status', Service::DANG_HOAT_DONG)
            ->get();
        $tongDichVu   = Service::count();
        $dangHoatDong = Service::where('status', Service::DANG_HOAT_DONG)->count();
        $ngungBan     = Service::where('status', Service::NGUNG_BAN)->count();
        if($service){
            return response()->json([
                'message' => 'Lấy dữ liệu service thành công',
                'status' => true,
                'tong_dich_vu'   => $tongDichVu,
                'dang_hoat_dong' => $dangHoatDong,
                'ngung_ban'      => $ngungBan,
                'data' => $service,
            ]);
        } else{
            return response()->json([
                'message' => 'service không tồn tại',
                'status' => false,
            ]);
        }

   }
}
