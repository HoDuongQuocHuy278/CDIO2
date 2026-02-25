<template>
    <div class="page-container">
        <div class="content-wrapper">

            <div class="header-section mb-4">
                <div class="header-content mb-4">
                    <h2 class="page-title">Quản lý hóa đơn</h2>
                    <p class="page-subtitle">Theo dõi doanh thu và lịch sử thanh toán</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon bg-blue-soft"><i class="fa-solid fa-file-invoice"></i></div>
                        <div class="stat-info">
                            <span class="stat-label">Tổng hóa đơn</span>
                            <h3 class="stat-value">{{ stats.totalInvoices }}</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-green-soft"><i class="fa-solid fa-sack-dollar"></i></div>
                        <div class="stat-info">
                            <span class="stat-label">Tổng doanh thu</span>
                            <h3 class="stat-value text-success">{{ formatCurrency(stats.totalRevenue) }}</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-purple-soft"><i class="fa-solid fa-circle-check"></i></div>
                        <div class="stat-info">
                            <span class="stat-label">Đã thanh toán</span>
                            <h3 class="stat-value">{{ stats.paidCount }}</h3>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon bg-orange-soft"><i class="fa-solid fa-clock"></i></div>
                        <div class="stat-info">
                            <span class="stat-label">Chờ thanh toán</span>
                            <h3 class="stat-value text-warning">{{ stats.pendingCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box">
                <div class="toolbar-row">
                    <div class="left-tools">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input v-model="filters.keyword" type="text" placeholder="Tìm tên khách, mã HĐ...">
                        </div>
                        <select v-model="filters.status" class="filter-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="paid">Đã thanh toán</option>
                            <option value="pending">Chờ thanh toán</option>
                            <option value="cancel">Đã hủy</option>
                        </select>
                    </div>
                    <button class="btn-action primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                        <i class="fa-solid fa-plus"></i> Tạo hóa đơn
                    </button>
                </div>

                <table class="custom-table mt-3">
                    <thead>
                        <tr>
                            <th>Mã HĐ</th>
                            <th>Khách hàng</th>
                            <th>Nhân viên lập</th>
                            <th>Ngày lập</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(inv, index) in filteredInvoices" :key="inv.id">
                            <td><span class="code-badge">{{ inv.code }}</span></td>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar-sm" :class="getRandomColor(index)">{{ getInitials(inv.customer)
                                    }}</div>
                                    <span class="fw-bold">{{ inv.customer }}</span>
                                </div>
                            </td>
                            <td>{{ inv.staff }}</td>
                            <td class="text-muted"><i class="fa-regular fa-calendar me-1"></i> {{ inv.date }}</td>
                            <td class="fw-bold text-primary">{{ formatCurrency(inv.amount) }}</td>
                            <td>
                                <div class="payment-method">
                                    <i :class="getPaymentIcon(inv.method)"></i> {{ inv.method }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="status-badge" :class="getStatusClass(inv.status)">
                                    {{ getStatusLabel(inv.status) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn-icon view" @click="viewInvoice(inv)" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                                <button class="btn-icon print" title="In hóa đơn">
                                    <i class="fa-solid fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination-container" v-if="totalPages > 1">
                    <div class="pagination-info">
                      Trang <b>{{ currentPage }}</b> / <b>{{ totalPages }}</b> (Tổng <b>{{ totalRecords }}</b> hóa đơn)
                    </div>
                    <div class="pagination-group">
                        <button class="page-btn" :disabled="currentPage === 1" @click="changePage(currentPage - 1)">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <button 
                          v-for="page in totalPages" 
                          :key="page" 
                          class="page-btn" 
                          :class="{ active: currentPage === page }"
                          @click="changePage(page)"
                        >
                          {{ page }}
                        </button>

                        <button class="page-btn" :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <!-- CREATE MODAL -->
    <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo hóa đơn mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input v-model="create_invoice.customer" class="form-control mb-2" placeholder="Khách hàng" />
                    <input v-model="create_invoice.amount" type="number" class="form-control mb-2" placeholder="Số tiền" />
                    <select v-model="create_invoice.method" class="form-control mb-2">
                        <option value="Chuyển khoản">Chuyển khoản</option>
                        <option value="Tiền mặt">Tiền mặt</option>
                        <option value="Thẻ tín dụng">Thẻ tín dụng</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" @click="addInvoice" data-bs-dismiss="modal">Lưu</button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="selectedInvoice" class="modal-overlay" @click.self="selectedInvoice = null">
        <div class="receipt-modal">
            <div class="receipt-header">
                <h3><i class="fa-brands fa-hive"></i> WELLFIT GYM</h3>
                <p>HÓA ĐƠN THANH TOÁN</p>
                <span class="receipt-code">{{ selectedInvoice.code }}</span>
            </div>
            <div class="receipt-body">
                <div class="row-info">
                    <span>Khách hàng:</span> <strong>{{ selectedInvoice.customer }}</strong>
                </div>
                <div class="row-info">
                    <span>Ngày lập:</span> <span>{{ selectedInvoice.date }}</span>
                </div>
                <div class="divider"></div>
                <div class="item-list">
                    <div class="item-row">
                        <span>Gói tập Premium (1 Tháng)</span>
                        <span>{{ formatCurrency(selectedInvoice.amount) }}</span>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="total-row">
                    <span>TỔNG CỘNG</span>
                    <span class="total-price">{{ formatCurrency(selectedInvoice.amount) }}</span>
                </div>
                <div class="status-row">
                    Trạng thái: <span :class="getStatusClass(selectedInvoice.status)">{{
                        getStatusLabel(selectedInvoice.status) }}</span>
                </div>
            </div>
            <div class="receipt-footer">
                <button class="btn-close-receipt" @click="selectedInvoice = null">Đóng</button>
                <button class="btn-print-receipt"><i class="fa-solid fa-print"></i> In ngay</button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import './index.css';
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
            selectedInvoice: null,
            invoices: [],
            currentPage: 1,
            totalPages: 1,
            totalRecords: 0,
            create_invoice: {
                code: "#INV-" + Math.floor(Math.random() * 1000),
                customer: "",
                staff: "Admin",
                date: new Date().toISOString().split("T")[0],
                amount: 0,
                method: "Chuyển khoản",
                status: "pending"
            }
        }
    },
    computed: {
        filteredInvoices() {
            return this.invoices.filter(inv => {
                const matchKeyword = (inv.customer && inv.customer.toLowerCase().includes(this.filters.keyword.toLowerCase())) ||
                    (inv.code && inv.code.toLowerCase().includes(this.filters.keyword.toLowerCase()));
                const matchStatus = this.filters.status === '' || inv.status === this.filters.status;
                return matchKeyword && matchStatus;
            });
        }
    },
    mounted() {
        this.getListInvoices();
    },
    methods: {
        getListInvoices(page = 1) {
            axios.get(`admin/hoa-don/get-data?page=${page}`)
                .then((res) => {
                    this.invoices = res.data.data.data;
                    this.currentPage = res.data.data.current_page;
                    this.totalPages = res.data.data.last_page;
                    this.totalRecords = res.data.data.total;
                    this.updateStats();
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
        addInvoice() {
            axios.post('admin/hoa-don/add-data', this.create_invoice)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.create_invoice = {
                            code: "#INV-" + Math.floor(Math.random() * 1000),
                            customer: "",
                            staff: "Admin",
                            date: new Date().toISOString().split("T")[0],
                            amount: 0,
                            method: "Chuyển khoản",
                            status: "pending"
                        };
                        this.getListInvoices(this.currentPage);
                    }
                });
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        },
        getInitials(name) { return name ? name.split(' ').pop().charAt(0).toUpperCase() : "?"; },
        getRandomColor(index) {
            const colors = ['bg-blue', 'bg-purple', 'bg-green', 'bg-orange'];
            return colors[index % colors.length];
        },
        getPaymentIcon(method) {
            if (method && method.includes('Tiền mặt')) return 'fa-solid fa-money-bill-wave text-success';
            if (method && method.includes('Chuyển khoản')) return 'fa-solid fa-qrcode text-primary';
            return 'fa-regular fa-credit-card text-warning';
        },
        getStatusClass(status) {
            if (status === 'paid') return 'badge-success';
            if (status === 'pending') return 'badge-warning';
            return 'badge-danger';
        },
        getStatusLabel(status) {
            if (status === 'paid') return 'Đã thanh toán';
            if (status === 'pending') return 'Chờ thanh toán';
            return 'Đã hủy';
        },
        viewInvoice(inv) {
            this.selectedInvoice = inv;
        }
    }
}
</script>
