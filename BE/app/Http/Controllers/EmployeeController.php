<?php

namespace App\Http\Controllers;

use App\Models\employee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function getEmployee()
    {
        $employee = employee::all();
        return response()->json([
            'message' => 'Lấy dữ liệu employee thành công',
            'status' => true,
            'data' => $employee,
        ]);
    }
}
