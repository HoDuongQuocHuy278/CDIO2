<template>
    <div class="dashboard-page">
        <div class="row">
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0 text-uppercase font-weight-bold">🏋️ Gym Management Dashboard</h5>
                            </div>
                            <div class="user-info d-flex align-items-center">
                                <span class="me-3">Xin chào, <strong>{{ userInfo.ho_ten || userInfo.ten_dang_nhap }}</strong></span>
                                <button @click="dangXuat" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-right-from-bracket me-1"></i> Đăng xuất
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mt-4">
            <div class="col">
                <div class="card radius-10 bg-primary bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Tổng thành viên</p>
                                <h4 class="my-1 text-white">{{ stats.totalMembers }}</h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class="fa-solid fa-users"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 bg-success bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Check-in hôm nay</p>
                                <h4 class="my-1 text-white">{{ stats.todayCheckins }}</h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class="fa-solid fa-calendar-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 bg-info bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Đang tập</p>
                                <h4 class="my-1 text-white">{{ stats.activeMembers }}</h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class="fa-solid fa-person-running"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 bg-warning bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Doanh thu tháng</p>
                                <h4 class="my-1 text-white">{{ formatCurrency(stats.monthlyRevenue) }}</h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class="fa-solid fa-money-bill-trend-up"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-header bg-transparent border-bottom-0">
                        <h5 class="mb-0 text-uppercase font-weight-bold">Quản lý nhanh</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-4 col-lg-2">
                                <button @click="goToMembers" class="btn btn-outline-primary w-100 py-3">
                                    <i class="fa-solid fa-users d-block mb-2 font-24"></i>
                                    Thành viên
                                </button>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2">
                                <button @click="goToCheckin" class="btn btn-outline-success w-100 py-3">
                                    <i class="fa-solid fa-calendar-check d-block mb-2 font-24"></i>
                                    Check-in
                                </button>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2">
                                <button @click="goToServices" class="btn btn-outline-info w-100 py-3">
                                    <i class="fa-solid fa-dumbbell d-block mb-2 font-24"></i>
                                    Dịch vụ
                                </button>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2">
                                <button @click="goToEquipment" class="btn btn-outline-warning w-100 py-3">
                                    <i class="fa-solid fa-screwdriver-wrench d-block mb-2 font-24"></i>
                                    Thiết bị
                                </button>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2">
                                <button @click="goToInventory" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fa-solid fa-boxes-stacked d-block mb-2 font-24"></i>
                                    Kho hàng
                                </button>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2">
                                <button @click="goToInvoices" class="btn btn-outline-dark w-100 py-3">
                                    <i class="fa-solid fa-file-invoice-dollar d-block mb-2 font-24"></i>
                                    Hóa đơn
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '../../config/axios';

export default {
    name: 'Dashboard',
    data() {
        return {
            userInfo: {},
            stats: {
                totalMembers: 0,
                todayCheckins: 0,
                activeMembers: 0,
                monthlyRevenue: 0
            }
        }
    },
    methods: {
        dangXuat() {
            if (confirm('Bạn có chắc muốn đăng xuất?')) {
                axios.post('/admin/dang-xuat')
                    .then((res) => {
                        if (res.data.status) {
                            this.$toast.success(res.data.message);
                        }
                        localStorage.removeItem('key_admin');
                        localStorage.removeItem('user_info');
                        this.$router.push('/login');
                    })
                    .catch((error) => {
                        console.error('Lỗi đăng xuất:', error);
                        localStorage.removeItem('key_admin');
                        localStorage.removeItem('user_info');
                        this.$router.push('/login');
                    });
            }
        },
        loadUserInfo() {
            const userInfoStr = localStorage.getItem('user_info');
            if (userInfoStr) {
                this.userInfo = JSON.parse(userInfoStr);
            }
        },
        loadStats() {
            // TODO: Load actual stats from API
            this.stats = {
                totalMembers: 150,
                todayCheckins: 45,
                activeMembers: 23,
                monthlyRevenue: 125000000
            };
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        },
        goToMembers() {
            this.$router.push('/members');
        },
        goToCheckin() {
            this.$toast.info('Chức năng check-in đang được phát triển');
        },
        goToServices() {
            this.$toast.info('Chức năng quản lý dịch vụ đang được phát triển');
        },
        goToEquipment() {
            this.$toast.info('Chức năng quản lý thiết bị đang được phát triển');
        },
        goToInventory() {
            this.$toast.info('Chức năng quản lý kho đang được phát triển');
        },
        goToInvoices() {
            this.$toast.info('Chức năng quản lý hóa đơn đang được phát triển');
        }
    },
    mounted() {
        const token = localStorage.getItem('key_admin');
        if (!token) {
            this.$router.push('/login');
            return;
        }

        this.loadUserInfo();
        this.loadStats();
    }
}
</script>

<style scoped>
.dashboard-page {
    padding: 20px;
    background-color: #f8f9fa;
    min-height: 100vh;
}
.radius-10 {
    border-radius: 10px;
}
.font-35 {
    font-size: 35px;
}
.font-24 {
    font-size: 24px;
}
.bg-gradient {
    background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}
</style>
