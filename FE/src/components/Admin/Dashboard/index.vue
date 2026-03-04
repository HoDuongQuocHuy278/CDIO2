<template>
    <div class="row g-4">
        <!-- Stats Cards -->
        <div class="col-xl-3 col-md-6" v-for="(item, index) in stats" :key="index">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper rounded-circle p-3 me-3" :class="item.colorClass">
                            <i :class="item.icon" class="fs-4 text-white"></i>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-bold">{{ item.label }}</div>
                            <h3 class="fw-bold mb-0 mt-1">{{ formatValue(item) }}</h3>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span :class="item.trend > 0 ? 'text-success' : 'text-danger'" class="fw-bold me-2">
                            <i :class="item.trend > 0 ? 'fa-solid fa-arrow-trend-up' : 'fa-solid fa-arrow-trend-down'"></i>
                            {{ Math.abs(item.trend) }}%
                        </span>
                        <span class="text-muted small">so với tháng trước</span>
                    </div>
                </div>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" :class="item.colorClass" role="progressbar" :style="{ width: '70%' }"></div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Phân Tích Doanh Thu</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            6 Tháng Gần Nhất
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 350px;">
                        <Bar v-if="loaded" :data="revenueChartData" :options="barChartOptions" />
                        <div v-else class="h-100 d-flex align-items-center justify-content-center">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Lượt Check-in Tuần Này</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 350px;">
                        <Line v-if="loaded" :data="checkinChartData" :options="lineChartOptions" />
                        <div v-else class="h-100 d-flex align-items-center justify-content-center">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Hoạt Động Gần Đây</h5>
                    <button class="btn btn-link btn-sm text-decoration-none p-0">Xem tất cả</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Thành Viên</th>
                                    <th>Hành Động</th>
                                    <th>Thời Gian</th>
                                    <th class="text-end pe-4">Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(act, i) in recentActivities" :key="i">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img :src="`https://ui-avatars.com/api/?name=${act.name}&background=random`" class="rounded-circle me-3" style="width: 32px; height: 32px;">
                                            <span class="fw-medium text-dark">{{ act.name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ act.action }}</td>
                                    <td><span class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ act.time }}</span></td>
                                    <td class="text-end pe-4">
                                        <span class="badge rounded-pill bg-success-soft text-success px-3">Hoàn tất</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '@/axios';
import { Bar, Line } from 'vue-chartjs';
import {
    Chart as ChartJS, CategoryScale, LinearScale, PointElement,
    LineElement, BarElement, Title, Tooltip, Legend, Filler
} from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend, Filler);

export default {
    components: { Bar, Line },
    data() {
        return {
            loaded: false,
            stats: [],
            recentActivities: [],
            revenueChartData: { labels: [], datasets: [] },
            checkinChartData: { labels: [], datasets: [] },
            barChartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { padding: 12, cornerRadius: 8 } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { font: { size: 11 } } },
                    x: { grid: { display: false } }
                }
            },
            lineChartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false },
                    x: { grid: { display: false } }
                },
                interaction: { intersect: false, mode: 'index' },
            }
        };
    },
    mounted() {
        this.fetchDashboardData();
    },
    methods: {
        async fetchDashboardData() {
            try {
                const res = await axios.get('admin/dashboard/stats');
                const data = res.data.data;
                this.stats = data.stats;
                this.recentActivities = data.recentActivity;

                this.revenueChartData = {
                    labels: data.revenueChart.labels,
                    datasets: [{
                        label: 'Doanh thu (k VNĐ)',
                        backgroundColor: '#0d6efd',
                        data: data.revenueChart.data,
                        borderRadius: 6
                    }]
                };

                this.checkinChartData = {
                    labels: data.checkinChart.labels,
                    datasets: [{
                        label: 'Lượt khách',
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        fill: true,
                        tension: 0.4,
                        data: data.checkinChart.data
                    }]
                };
                this.loaded = true;
            } catch (error) {
                this.$toast.error("Không thể tải dữ liệu thống kê!");
            }
        },
        formatValue(item) {
            if (item.label.toLowerCase().includes('doanh thu')) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.value * 1000);
            }
            return item.value;
        }
    }
};
</script>

<style scoped>
.stat-icon-wrapper { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; }
.bg-primary { background-color: #0d6efd !important; }
.bg-success { background-color: #198754 !important; }
.bg-info { background-color: #0dcaf0 !important; }
.bg-warning { background-color: #ffc107 !important; }
.bg-success-soft { background-color: #e6f4ea; }
</style>
