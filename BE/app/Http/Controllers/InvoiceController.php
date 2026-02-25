<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function getData()
    {
        $data = Invoice::orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = Invoice::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Tạo hóa đơn thành công',
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {
        $data = Invoice::find($request->id);
        if ($data) {
            $data->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật hóa đơn thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy hóa đơn'
        ]);
    }

    public function delete(Request $request)
    {
        $data = Invoice::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa hóa đơn thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy hóa đơn'
        ]);
    }
}
