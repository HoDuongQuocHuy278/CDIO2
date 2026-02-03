<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        $products = Product::all();
        return response()->json([
            'message' => 'Lấy dữ liệu sản phẩm thành công',
            'status' => true,
            'data' => $products,
        ]);
    }
}
