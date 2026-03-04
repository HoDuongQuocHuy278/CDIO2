<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\RevenueRequest;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function getData()
    {
        $data = Revenue::orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function addData(RevenueRequest $request)
    {
        $data = Revenue::create($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Lưu doanh thu thành công',
            'data' => $data
        ]);
    }

    public function thongKeDichVu(Request $request)
    {
        $begin = $request->begin ? Carbon::parse($request->begin)->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->end ? Carbon::parse($request->end)->endOfDay() : Carbon::now()->endOfMonth();

        $invoices = Invoice::where('status', 1)
            ->whereBetween('date', [$begin->format('Y-m-d'), $end->format('Y-m-d')])
            ->orderBy('date', 'asc')
            ->get();

        $grouped = $invoices->groupBy('date');

        $dataList = [];
        $labels = [];
        $revenueData = [];

        foreach ($grouped as $date => $invs) {
            $formattedDate = Carbon::parse($date)->format('d/m/Y');
            
            $item = [
                'ngay' => $formattedDate,
                'tong_dich_vu_ban_ra' => $invs->count(),
                'dich_vu_da_thanh_toan' => $invs->sum('amount'),
            ];
            
            $dataList[] = $item;
            $labels[] = $formattedDate;
            $revenueData[] = $invs->sum('amount');
        }

        return response()->json([
            'status' => true,
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $revenueData
                ]
            ],
            'data' => reset($dataList) === false ? [] : array_reverse($dataList), // Reverse so newest is top in table
        ]);
    }
}
