<?php

namespace App\Http\Controllers;

use App\Models\equipments;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EquipmentsController extends Controller
{
    public function getEquipments()
    {
        $equipments = equipments::join('equipment_types', 'equipments.type_id', '=', 'equipment_types.id')
                                ->join('suppliers', 'equipments.supplier_id', '=', 'suppliers.id')
                                ->join('rooms','equipments.room_id', '=', 'rooms.id' )
                                ->get();
        $tongThietBi    = equipments::count();
        $HoatDongTot    = equipments::where('tinh_trang', equipments::TINH_TRANG_TOT)->count();
        $DangBaoTri     = equipments::where('tinh_trang', equipments::TINH_TRANG_BAO_TRI)->count();
        $Hong           = equipments::where('tinh_trang', equipments::TINH_TRANG_HONG)->count();
        
        if($equipments){
            return response()->json([
                'message' => 'Lấy dữ liệu equipments thành công',
                'status' => true,
                'tong_thiet_bi'     => $tongThietBi,
                'dang_hoat_dong'    => $HoatDongTot,
                'dang_bao_tri'      => $DangBaoTri,
                'hong'              => $Hong,
                'data'              => $equipments,
            ]);
        }
    }
    
}
