<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ProductRequest;

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

    public function addData(ProductRequest $request)
    {
        $data = Product::create($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Thêm sản phẩm ' . $data->ten_san_pham . ' thành công',
            'data' => $data
        ]);
    }

    public function update(ProductRequest $request)
    {
        $data = Product::find($request->id);
        if ($data) {
            $data->update($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật sản phẩm ' . $data->ten_san_pham . ' thành công'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Không tìm thấy sản phẩm'
        ]);
    }

    public function destroy(Request $request)
    {
        $data = Product::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Xóa sản phẩm ' . $data->ten_san_pham . ' thành công'
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
