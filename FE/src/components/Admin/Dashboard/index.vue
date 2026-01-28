<template>
    <div class="dashboard">
        <header class="header">
            <div class="header-left">
                <h2 class="page-title">Tổng quan</h2>
                <p class="welcome-text">Xin chào! Đây là tình hình hoạt động hôm nay.</p>
            </div>
            <div class="header-right">
                <button class="btn-date">
                    <i class='bx bx-calendar'></i>
                    <span>Tháng 10, 2023</span>
                </button>
                <button class="btn-noti">
                    <i class='bx bxs-bell'></i>
                    <span class="badge"></span>
                </button>
            </div>
        </header>
        <section class="stats-grid">
            <div class="stat-card" v-for="(item, index) in stats" :key="index">
                <div class="stat-icon" :class="item.colorClass">
                    <i :class="item.icon"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-label">{{ item.label }}</span>
                    <h3 class="stat-value">{{ item.value }}</h3>
                </div>
                <div class="stat-trend" :class="item.trend > 0 ? 'up' : 'down'">
                    <i :class="item.trend > 0 ? 'bx bx-up-arrow-alt' : 'bx bx-down-arrow-alt'"></i>
                    {{ Math.abs(item.trend) }}%
                </div>
            </div>
        </section>

        <section class="charts-grid">
            <div class="card chart-card">
                <div class="card-header">
                    <h3>Doanh thu 6 tháng</h3>
                    <i class='bx bx-dots-horizontal-rounded action-icon'></i>
                </div>
                <div class="chart-container">
                    <Bar v-if="loaded" :data="revenueChartData" :options="barChartOptions" />
                </div>
            </div>

            <div class="card chart-card">
                <div class="card-header">
                    <h3>Lượt Check-in tuần này</h3>
                    <span class="highlight-text">Trung bình: 105</span>
                </div>
                <div class="chart-container">
                    <Line v-if="loaded" :data="checkinChartData" :options="lineChartOptions" />
                </div>
            </div>
        </section>

        <section class="recent-activity card">
            <div class="card-header">
                <h3>Hoạt động gần đây</h3>
                <a href="#" class="view-all">Xem tất cả</a>
            </div>
            <div class="activity-list">
                <div class="activity-item" v-for="(act, i) in recentActivities" :key="i">
                    <div class="user-avatar">
                        <img :src="`https://ui-avatars.com/api/?name=${act.name}&background=random`" :alt="act.name">
                    </div>
                    <div class="act-details">
                        <p class="act-user">{{ act.name }}</p>
                        <p class="act-desc">{{ act.action }}</p>
                    </div>
                    <span class="act-time">{{ act.time }}</span>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import './index.css'
import { defineComponent, ref, onMounted } from 'vue';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend,
    Filler
} from 'chart.js';
import { Bar, Line } from 'vue-chartjs';
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend, Filler);

export default defineComponent({
    name: 'DashboardOverview',
    components: {
        Bar,
        Line
    },
    setup() {
        // --- State quản lý dữ liệu ---
        const loaded = ref(false); // Biến cờ để biết khi nào API tải xong
        const stats = ref([]);
        const recentActivities = ref([]);
        const revenueChartData = ref({ labels: [], datasets: [] });
        const checkinChartData = ref({ labels: [], datasets: [] });

        // --- Cấu hình Options cho Chart (Thường là tĩnh, ít khi thay đổi từ BE) ---
        const barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', borderDash: [5, 5] },
                    ticks: { color: '#64748b', font: { size: 11 } },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 12 } },
                    border: { display: false }
                }
            }
        };

        const lineChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: { label: (context) => ` ${context.parsed.y} khách` }
                }
            },
            scales: {
                y: { display: false, beginAtZero: false },
                x: { grid: { display: false }, ticks: { color: '#94a3b8' }, border: { display: false } }
            },
            interaction: { intersect: false, mode: 'index' },
        };

        // --- Hàm giả lập gọi API (Sau này bạn thay bằng axios.get) ---
        const fetchDashboardData = async () => {
            try {
                // Ví dụ: const response = await axios.get('/api/dashboard');
                // Giả lập độ trễ mạng
                await new Promise(resolve => setTimeout(resolve, 500));

                // 1. Gán dữ liệu Stats
                stats.value = [
                    { label: 'Tổng thành viên', value: '1,234', icon: 'bx bxs-group', colorClass: 'blue', trend: 12 },
                    { label: 'Đang hoạt động', value: '892', icon: 'bx bx-run', colorClass: 'green', trend: 8 },
                    { label: 'Doanh thu tháng', value: '67tr', icon: 'bx bxs-dollar-circle', colorClass: 'purple', trend: 23 },
                    { label: 'Check-in hôm nay', value: '125', icon: 'bx bx-qr-scan', colorClass: 'orange', trend: -5 },
                ];

                // 2. Gán dữ liệu Chart Doanh Thu
                revenueChartData.value = {
                    labels: ['Thg 1', 'Thg 2', 'Thg 3', 'Thg 4', 'Thg 5', 'Thg 6'],
                    datasets: [{
                        label: 'Doanh thu (triệu VNĐ)',
                        backgroundColor: '#3b82f6',
                        hoverBackgroundColor: '#2563eb',
                        data: [40, 59, 45, 81, 56, 95], // Dữ liệu từ BE sẽ vào đây
                        borderRadius: 6,
                        barThickness: 28,
                    }]
                };

                // 3. Gán dữ liệu Chart Check-in
                checkinChartData.value = {
                    labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
                    datasets: [{
                        label: 'Lượt khách',
                        borderColor: '#10b981',
                        // Gradient function
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');
                            return gradient;
                        },
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4,
                        data: [85, 92, 78, 95, 110, 125, 98] // Dữ liệu từ BE sẽ vào đây
                    }]
                };

                // 4. Gán dữ liệu Hoạt động gần đây
                recentActivities.value = [
                    { name: 'Nguyễn Văn An', action: 'Đã check-in tại cửa chính', time: '2 phút trước' },
                    { name: 'Trần Thị Bình', action: 'Đăng ký gói Premium 6 tháng', time: '15 phút trước' },
                    { name: 'Lê Hoàng Cường', action: 'Mua nước tăng lực', time: '23 phút trước' },
                ];

                loaded.value = true; // Đánh dấu đã tải xong để hiển thị Chart
            } catch (error) {
                console.error("Lỗi tải dữ liệu:", error);
            }
        };

        // Lifecycle Hook: Chạy khi component được mount
        onMounted(() => {
            fetchDashboardData();
        });

        // Return các biến ra template
        return {
            loaded,
            stats,
            recentActivities,
            revenueChartData,
            checkinChartData,
            barChartOptions,
            lineChartOptions
        };
    }
});
</script>
