<template>
    <div class="row">
        <div class="col-lg-12">
            <!-- Stats Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 bg-primary-soft p-3 rounded-circle me-3">
                                <i class="fa-solid fa-file-invoice text-primary fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block pb-1">Tổng Hóa Đơn</small>
                                <h4 class="fw-bold mb-0">{{ stats.totalInvoices }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 bg-success-soft p-3 rounded-circle me-3">
                                <i class="fa-solid fa-sack-dollar text-success fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block pb-1">Tổng Doanh Thu</small>
                                <h4 class="fw-bold mb-0 text-success">{{ formatCurrency(stats.totalRevenue) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 bg-info-soft p-3 rounded-circle me-3">
                                <i class="fa-solid fa-circle-check text-info fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block pb-1">Đã Thanh Toán</small>
                                <h4 class="fw-bold mb-0 text-info">{{ stats.paidCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="flex-shrink-0 bg-warning-soft p-3 rounded-circle me-3">
                                <i class="fa-solid fa-clock text-warning fs-4"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block pb-1">Chờ Thanh Toán</small>
                                <h4 class="fw-bold mb-0 text-warning">{{ stats.pendingCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-primary mb-0">Quản Lý Hóa Đơn</h5>
                    <button class="btn btn-primary btn-sm" @click="openAdd()">
                        <i class="fa-solid fa-plus me-1"></i> Tạo Hóa Đơn
                    </button>
                </div>
                <div class="card-body">
                    <div v-if="isLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Đang tải hóa đơn...</p>
                    </div>
                    <div v-else class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã HĐ</th>
                                    <th>Khách Hàng</th>
                                    <th>Ngày Lập</th>
                                    <th>Tổng Tiền</th>
                                    <th>Thanh Toán</th>
                                    <th>Trạng Thái</th>
                                    <th class="text-end">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(inv, index) in filteredInvoices" :key="inv.id">
                                    <td><span class="badge bg-light text-dark border">{{ inv.code }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                {{ getInitials(inv.customer) }}
                                            </div>
                                            <span class="fw-bold">{{ inv.customer }}</span>
                                        </div>
                                    </td>
                                    <td><i class="fa-regular fa-calendar me-1 text-muted"></i> {{ inv.date }}</td>
                                    <td class="fw-bold text-primary">{{ formatCurrency(inv.amount) }}</td>
                                    <td>
                                        <div class="small">
                                            <i :class="getPaymentIcon(inv.method)" class="me-1"></i> {{ inv.method }}
                                        </div>
                                    </td>
                                    <td>
                                        <span :class="getStatusBadge(inv.status)" class="badge rounded-pill">
                                            {{ getStatusLabel(inv.status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info me-1" @click="viewInvoice(inv)">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fa-solid fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <nav v-if="totalPages > 1" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Trước</a>
                            </li>
                            <li v-for="page in totalPages" :key="page" class="page-item" :class="{ active: currentPage === page }">
                                <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
                            </li>
                            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Sau</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Receipt View -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content overflow-hidden border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-4 text-center d-block">
                    <h4 class="fw-bold mb-1">WELLFIT GYM</h4>
                    <p class="small mb-0 opacity-75 text-uppercase">Hóa Đơn Thanh Toán</p>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="text-center mb-4">
                        <div class="display-6 fw-bold text-dark mb-1">{{ formatCurrency(selectedInvoice?.amount || 0) }}</div>
                        <span class="badge bg-success-soft text-success px-3">{{ getStatusLabel(selectedInvoice?.status) }}</span>
                    </div>
                    
                    <div class="border-top border-bottom py-3 mb-4">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Mã hóa đơn</span>
                            <span class="fw-bold text-dark">{{ selectedInvoice?.code }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Ngày lập</span>
                            <span class="fw-bold text-dark">{{ selectedInvoice?.date }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Khách hàng</span>
                            <span class="fw-bold text-dark">{{ selectedInvoice?.customer }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Thanh toán</span>
                            <span class="fw-bold text-dark">{{ selectedInvoice?.method }}</span>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 py-2 px-3 small">
                        <i class="fa-solid fa-info-circle me-1"></i> Cảm ơn bạn đã tin tưởng dịch vụ của chúng tôi!
                    </div>
                </div>
                <div class="modal-footer bg-white border-0 py-3 d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4 btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary px-4 btn-sm"><i class="fa-solid fa-print me-1"></i> In HĐ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create Invoice -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo Hóa Đơn Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tên Khách Hàng</label>
                            <input v-model="form.customer" class="form-control" list="memberList" placeholder="Nhập hoặc chọn tên khách hàng">
                            <datalist id="memberList">
                                <option v-for="mem in list_members" :key="mem.id" :value="mem.full_name">
                                    {{ mem.phone }}
                                </option>
                            </datalist>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số Tiền</label>
                            <div class="input-group">
                                <input v-model.number="form.amount" type="number" class="form-control">
                                <span class="input-group-text">VNĐ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hình Thức</label>
                            <select v-model="form.method" class="form-select">
                                <option value="Chuyển khoản">Chuyển khoản</option>
                                <option value="Tiền mặt">Tiền mặt</option>
                                <option value="Thẻ tín dụng">Thẻ tín dụng</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại Dịch Vụ</label>
                            <select v-model="form.service_id" class="form-select">
                                <option value="">--- Chọn Dịch Vụ ---</option>
                                <option v-for="svc in list_services" :key="svc.id" :value="svc.id">{{ svc.ten_dich_vu }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gói Tập</label>
                            <select v-model="form.package_id" class="form-select">
                                <option value="">--- Chọn Gói Tập ---</option>
                                <option v-for="pkg in filteredPackages" :key="pkg.id" :value="pkg.id">{{ pkg.ten_goi }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" @click="addInvoice()" class="btn btn-primary" :disabled="isSaving">
                        {{ isSaving ? 'Đang tạo...' : 'Lưu Hóa Đơn' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '@/axios';

export default {
    data() {
        return {
            stats: { 
                totalInvoices: 0, 
                totalRevenue: 0, 
                paidCount: 0, 
                pendingCount: 0 
            },
            filters: {
                keyword: '',
                status: ''
            },
            invoices: [],
            currentPage: 1,
            totalPages: 1,
            totalRecords: 0,
            isLoading: false,
            isSaving: false,
            selectedInvoice: null,
            form: { 
                customer: "", 
                amount: 0, 
                method: "Chuyển khoản", 
                status: "pending",
                service_id: "",
                package_id: ""
            },
            list_services: [],
            list_packages: [],
            list_members: [],
            receiptModal: null,
            createModal: null
        };
    },
    computed: {
        filteredPackages() {
            if (!this.form.service_id) return this.list_packages;
            return this.list_packages.filter(p => p.service_id === this.form.service_id);
        },
        filteredInvoices() {

            return this.invoices.filter(inv => {
                const matchKeyword = !this.filters.keyword || 
                    inv.customer?.toLowerCase().includes(this.filters.keyword.toLowerCase()) ||
                    inv.code?.toLowerCase().includes(this.filters.keyword.toLowerCase());
                const matchStatus = !this.filters.status || inv.status === this.filters.status;
                return matchKeyword && matchStatus;
            });
        }
    },
    mounted() {
        this.getListInvoices();
        this.loadServices();
        this.loadPackages();
        this.loadMembers();
        this.receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        this.createModal = new bootstrap.Modal(document.getElementById('createInvoiceModal'));
    },
    methods: {
        loadServices() {
            axios.get('admin/dich-vu/get-data')
                .then((res) => {
                    this.list_services = res.data.data.data || res.data.data;
                });
        },
        loadPackages() {
            axios.get('admin/thanh-vien/get-packages')
                .then((res) => {
                    this.list_packages = res.data.data;
                });
        },
        loadMembers() {
            axios.get('admin/thanh-vien/get-all')
                .then((res) => {
                    this.list_members = res.data.data;
                });
        },
        getListInvoices(page = 1) {
            this.isLoading = true;
            axios.get(`admin/hoa-don/get-data?page=${page}`)
                .then((res) => {
                    this.invoices = res.data.data.data;
                    this.currentPage = res.data.data.current_page;
                    this.totalPages = res.data.data.last_page;
                    this.totalRecords = res.data.data.total;
                    this.updateStats();
                })
                .catch(() => {
                    this.$toast.error("Không thể tải danh sách hóa đơn!");
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.getListInvoices(page);
            }
        },
        updateStats() {
            // Stats should ideally come from backend for total accuracy
            this.stats.totalInvoices = this.totalRecords;
            this.stats.totalRevenue = this.invoices.reduce((sum, inv) => sum + Number(inv.amount), 0);
            this.stats.paidCount = this.invoices.filter(inv => inv.status === 'paid').length;
            this.stats.pendingCount = this.invoices.filter(inv => inv.status === 'pending').length;
        },
        openAdd() {
            this.form = { 
                customer: "", 
                amount: 0, 
                method: "Chuyển khoản", 
                status: "pending",
                service_id: "",
                package_id: ""
            };
            this.createModal.show();
        },
        addInvoice() {
            this.isSaving = true;
            const payload = { 
                ...this.form, 
                code: "#INV-" + Math.floor(Math.random() * 1000 + 1000),
                staff: "Admin",
                date: new Date().toISOString().split("T")[0]
            };
            axios.post('admin/hoa-don/add-data', payload)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.getListInvoices(this.currentPage);
                        this.createModal.hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => {
                    this.$toast.error(err.response?.data?.message || "Lỗi khi tạo hóa đơn!");
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        viewInvoice(inv) {
            this.selectedInvoice = inv;
            this.receiptModal.show();
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        },
        getInitials(name) { 
            return name ? name.split(' ').pop().charAt(0).toUpperCase() : "?"; 
        },
        getPaymentIcon(method) {
            if (method?.includes('Tiền mặt')) return 'fa-solid fa-money-bill-wave text-success';
            if (method?.includes('Chuyển khoản')) return 'fa-solid fa-qrcode text-primary';
            return 'fa-regular fa-credit-card text-warning';
        },
        getStatusBadge(status) {
            if (status === 'paid') return 'bg-success text-white';
            if (status === 'pending') return 'bg-warning text-dark';
            return 'bg-danger text-white';
        },
        getStatusLabel(status) {
            if (status === 'paid') return 'Đã thanh toán';
            if (status === 'pending') return 'Chờ thanh toán';
            return 'Đã hủy';
        }
    }
};
</script>
