<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold text-primary mb-0">
                        <i class="fa-solid fa-chart-line me-2"></i>Thống Kê Dịch Vụ Đã Bán
                    </h5>
                </div>
                <div class="card-body bg-light-soft p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-5 col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Từ ngày</label>
                            <input v-model="search.begin" type="date" class="form-control border-0 shadow-sm">
                        </div>
                        <div class="col-lg-5 col-md-6">
                            <label class="form-label fw-bold small text-uppercase">Đến ngày</label>
                            <input v-model="search.end" type="date" class="form-control border-0 shadow-sm">
                        </div>
                        <div class="col-lg-2 col-md-12">
                            <button @click="thongKe()" class="btn btn-primary w-100 shadow-sm py-2">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> THỐNG KÊ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Dữ Liệu Chi Tiết</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Ngày</th>
                                    <th class="text-center">Dịch Vụ Bán Ra</th>
                                    <th class="text-center">Doanh Thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(v, i) in list_data" :key="i">
                                    <td class="text-center"><span class="text-muted">{{ v.ngay }}</span></td>
                                    <td class="text-center fw-bold">{{ v.tong_dich_vu_ban_ra }}</td>
                                    <td class="text-center"><span class="badge bg-success-soft text-success px-3">{{ formatCurrency(v.dich_vu_da_thanh_toan) }}</span></td>
                                </tr>
                                <tr v-if="list_data.length === 0">
                                    <td colspan="3" class="text-center py-4 text-muted small">Không có dữ liệu thống kê</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Biểu Đồ Tăng Trưởng</h6>
                </div>
                <div class="card-body">
                    <div style="height: 350px; position: relative;">
                        <Bar v-if="is_view" id="my-chart-id" :options="chartOptions" :data="chartData" />
                        <div v-else class="h-100 d-flex align-items-center justify-content-center text-muted">
                            <i class="fa-solid fa-chart-simple fa-3x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import axios from '@/axios';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
export default {
    name: 'BarChart',
    components: { Bar },
    data() {
        return {
            search: {
                begin: '',
                end: ''
            },
            list_data: [],
            is_view: false,
            chartData: {
                labels: [],
                datasets: []
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        }
    },
    methods: {
        thongKe() {
            axios
                .post('admin/thong-ke/dich-vu', this.search)
                .then((res) => {
                    if (res.data.status) {
                        this.is_view = true;
                        this.chartData = {
                            labels: res.data.labels,
                            datasets: [{
                                label: 'Doanh Thu',
                                backgroundColor: '#0d6efd',
                                data: res.data.datasets[0].data,
                                borderRadius: 4
                            }]
                        };
                        this.list_data = res.data.data;
                    }
                })
                .catch((err) => {
                    this.$toast.error("Lỗi khi thống kê dữ liệu!");
                    console.error(err);
                });
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        }
    },
};
</script>
<style scoped>
.bg-light-soft { background-color: #f8fbff; }
.bg-success-soft { background-color: #e6f4ea; }
</style>
