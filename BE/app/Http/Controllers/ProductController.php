<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getData()
    {
        $data = Product::orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $data = Product::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Thêm sản phẩm thành công',
            'data' => $data
        ]);
    }

    public function update(Request $request)
    {
        $data = Product::find($request->id);
        if ($data) {
            $data->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật sản phẩm thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy sản phẩm'
        ]);
    }

    public function delete(Request $request)
    {
        $data = Product::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa sản phẩm thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy sản phẩm'
        ]);
    }

    public function changeStatus(Request $request)
    {
        $data = Product::find($request->id);
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
            'message' => 'Không tìm thấy sản phẩm'
        ]);
    }
}
