<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Revenue;
use App\Models\CheckIn;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getStats()
    {
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 1)->count();
        
        $currentMonth = Carbon::now()->format('Y-m');
        $monthlyRevenue = Revenue::where('date', 'like', "$currentMonth%")->sum('amount');
        
        $today = Carbon::today()->toDateString();
        $todayCheckins = CheckIn::whereDate('created_at', $today)->count();

        // Revenue Chart (Last 6 months)
        $revenueChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStr = $month->format('Y-m');
            $label = "Thg " . $month->format('m');
            $amount = Revenue::where('date', 'like', "$monthStr%")->sum('amount');
            $revenueChart['labels'][] = $label;
            $revenueChart['data'][] = (int)$amount;
        }

        // Checkin Chart (Last 7 days)
        $checkinChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $dayStr = $day->toDateString();
            $label = $day->format('d/m');
            $count = CheckIn::whereDate('created_at', $dayStr)->count();
            $checkinChart['labels'][] = $label;
            $checkinChart['data'][] = $count;
        }

        // Recent Activity
        $recentActivity = CheckIn::with('member')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->member->full_name,
                    'action' => 'Đã check-in (' . $item->check_in_type . ')',
                    'time' => Carbon::parse($item->created_at)->diffForHumans()
                ];
            });

        return response()->json([
            'status' => true,
            'data' => [
                'stats' => [
                    ['label' => 'Tổng thành viên', 'value' => $totalMembers, 'icon' => 'bx bxs-group', 'colorClass' => 'blue', 'trend' => 0],
                    ['label' => 'Đang hoạt động', 'value' => $activeMembers, 'icon' => 'bx bx-run', 'colorClass' => 'green', 'trend' => 0],
                    ['label' => 'Doanh thu tháng', 'value' => (int)$monthlyRevenue, 'icon' => 'bx bxs-dollar-circle', 'colorClass' => 'purple', 'trend' => 0, 'isCurrency' => true],
                    ['label' => 'Check-in hôm nay', 'value' => $todayCheckins, 'icon' => 'bx bx-qr-scan', 'colorClass' => 'orange', 'trend' => 0],
                ],
                'revenueChart' => $revenueChart,
                'checkinChart' => $checkinChart,
                'recentActivity' => $recentActivity
            ]
        ]);
    }
}
