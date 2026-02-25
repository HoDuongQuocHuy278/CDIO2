<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Revenue;

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

    public function store(Request $request)
    {
        $data = Revenue::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Lưu doanh thu thành công',
            'data' => $data
        ]);
    }
}
