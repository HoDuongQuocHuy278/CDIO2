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

                <div class="pagination-container">
                    <div class="pagination-info">
                        Hiển thị <b>{{ filteredInvoices.length }}</b> kết quả
                    </div>
                    <div class="pagination-group">
                        <button class="page-btn prev disabled">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">...</button>

                        <button class="page-btn next">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
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

</template>

<script>
import './index.css'
export default {
    data() {
        return {
            stats: {
                totalInvoices: 128,
                totalRevenue: 84500000,
                paidCount: 115,
                pendingCount: 13
            },
            filters: {
                keyword: '',
                status: ''
            },
            selectedInvoice: null,
            invoices: [
                { id: 1, code: '#INV-001', customer: 'Nguyễn Văn An', staff: 'Phạm Thị Mai', date: '14/01/2026', amount: 1650000, method: 'Chuyển khoản', status: 'paid' },
                { id: 2, code: '#INV-002', customer: 'Trần Thị Bình', staff: 'Phạm Thị Mai', date: '14/01/2026', amount: 1400000, method: 'Tiền mặt', status: 'paid' },
                { id: 3, code: '#INV-003', customer: 'Lê Hoàng Cường', staff: 'Lê Văn Tú', date: '14/01/2026', amount: 380000, method: 'Thẻ tín dụng', status: 'paid' },
                { id: 4, code: '#INV-004', customer: 'Phạm Thu Dung', staff: 'Phạm Thị Mai', date: '14/01/2026', amount: 500000, method: 'Chuyển khoản', status: 'pending' },
                { id: 5, code: '#INV-005', customer: 'Võ Minh Em', staff: 'Lê Văn Tú', date: '13/01/2026', amount: 3000000, method: 'Chuyển khoản', status: 'paid' },
                { id: 6, code: '#INV-006', customer: 'Đặng Ngọc Nhi', staff: 'Phạm Thị Mai', date: '12/01/2026', amount: 7500000, method: 'Tiền mặt', status: 'cancel' },
            ]
        }
    },
    computed: {
        filteredInvoices() {
            return this.invoices.filter(inv => {
                const matchKeyword = inv.customer.toLowerCase().includes(this.filters.keyword.toLowerCase()) ||
                    inv.code.toLowerCase().includes(this.filters.keyword.toLowerCase());
                const matchStatus = this.filters.status === '' || inv.status === this.filters.status;
                return matchKeyword && matchStatus;
            });
        }
    },
    methods: {
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        },
        getInitials(name) { return name.split(' ').pop().charAt(0).toUpperCase(); },
        getRandomColor(index) {
            const colors = ['bg-blue', 'bg-purple', 'bg-green', 'bg-orange'];
            return colors[index % colors.length];
        },
        getPaymentIcon(method) {
            if (method.includes('Tiền mặt')) return 'fa-solid fa-money-bill-wave text-success';
            if (method.includes('Chuyển khoản')) return 'fa-solid fa-qrcode text-primary';
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
