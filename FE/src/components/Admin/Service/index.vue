<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mt-2 bg-transparent border-bottom-0">
                    <h4 class="fw-bold text-primary mb-0">QUẢN LÝ DỊCH VỤ</h4>
                    <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Dịch Vụ
                    </button>
                </div>
                <div class="card-body">
                    <!-- Stat Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light-primary border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Tổng Dịch Vụ</h6>
                                    <h3 class="fw-bold mb-0 text-primary">{{ list_dich_vu.length }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-success border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Đang Hoạt Động</h6>
                                    <h3 class="fw-bold mb-0 text-success">{{ activeCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-warning border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Ngừng Bán</h6>
                                    <h3 class="fw-bold mb-0 text-warning">{{ inactiveCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-info border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Gói Bán Chạy</h6>
                                    <h3 class="fw-bold mb-0 text-info">PT Cá Nhân</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="noi_dung_tim_kiem" @keyup.enter="loadData()" class="form-control" placeholder="Tìm kiếm dịch vụ...">
                                <button class="btn btn-outline-secondary" @click="loadData()">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select v-model="filterType" class="form-select" @change="loadData()">
                                <option value="">Tất cả loại dịch vụ</option>
                                <option value="Gói tập">Gói tập</option>
                                <option value="PT">PT</option>
                                <option value="Yoga">Yoga</option>
                                <option value="Spa">Spa</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="table-primary text-center">
                                    <th style="width: 50px;">#</th>
                                    <th>Tên Dịch Vụ</th>
                                    <th>Loại Dịch Vụ</th>
                                    <th>Giá Tiền</th>
                                    <th>Thời Hạn</th>
                                    <th>Số Buổi</th>
                                    <th>Trạng Thái</th>
                                    <th style="width: 150px;">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody v-if="!isLoading">
                                <tr v-for="(v, i) in list_dich_vu" :key="i">
                                    <td class="text-center">{{ i + 1 }}</td>
                                    <td class="fw-bold text-dark">{{ v.ten_dich_vu }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">
                                            <i :class="getServiceIcon(v.loai_dich_vu)" class="me-1"></i>
                                            {{ v.loai_dich_vu }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-success">{{ formatCurrency(v.gia_tien) }}</td>
                                    <td class="text-center">{{ v.thoi_han }}</td>
                                    <td class="text-center">{{ v.so_buoi }}</td>
                                    <td class="text-center">
                                        <span :class="v.status == 1 ? 'badge bg-success' : 'badge bg-danger'">
                                            {{ v.status == 1 ? 'Đang Bán' : 'Ngừng Bán' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm me-1" @click="openEdit(v)" data-bs-toggle="modal" data-bs-target="#editModal">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" @click="dich_vu_delete = v" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <div class="mt-2 text-muted">Đang tải dữ liệu dịch vụ...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Dịch Vụ Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên Dịch Vụ</label>
                        <input v-model="create_dich_vu.ten_dich_vu" type="text" class="form-control" placeholder="Ví dụ: Gói VIP 6 tháng">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại Dịch Vụ</label>
                        <select v-model="create_dich_vu.loai_dich_vu" class="form-select">
                            <option value="Gói tập">Gói tập</option>
                            <option value="PT">PT Cá nhân</option>
                            <option value="Yoga">Yoga</option>
                            <option value="Spa">Spa</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Giá Tiền (VNĐ)</label>
                            <input v-model="create_dich_vu.gia_tien" type="number" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thời Hạn</label>
                            <input v-model="create_dich_vu.thoi_han" type="text" class="form-control" placeholder="30 ngày">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Buổi</label>
                            <input v-model="create_dich_vu.so_buoi" type="text" class="form-control" placeholder="Không giới hạn">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" @click="themMoi()" class="btn btn-primary" :disabled="isSaving">Lưu Lại</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập Nhật Dịch Vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên Dịch Vụ</label>
                        <input v-model="dich_vu_update.ten_dich_vu" type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại Dịch Vụ</label>
                        <select v-model="dich_vu_update.loai_dich_vu" class="form-select">
                            <option value="Gói tập">Gói tập</option>
                            <option value="PT">PT Cá nhân</option>
                            <option value="Yoga">Yoga</option>
                            <option value="Spa">Spa</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Giá Tiền (VNĐ)</label>
                            <input v-model="dich_vu_update.gia_tien" type="number" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thời Hạn</label>
                            <input v-model="dich_vu_update.thoi_han" type="text" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Buổi</label>
                            <input v-model="dich_vu_update.so_buoi" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select v-model="dich_vu_update.status" class="form-select">
                            <option :value="1">Đang Bán</option>
                            <option :value="0">Ngừng Bán</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" @click="capNhat()" class="btn btn-warning" :disabled="isSaving">Cập Nhật</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác Nhận Xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Bạn có chắc chắn muốn xóa dịch vụ <b>{{ dich_vu_delete.ten_dich_vu }}</b> không?<br>
                    <small class="text-danger">Thao tác này không thể hoàn tác.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" @click="xoa()" class="btn btn-danger" :disabled="isSaving">Xác Nhận Xóa</button>
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
            list_dich_vu: [],
            create_dich_vu: {
                ten_dich_vu: "",
                loai_dich_vu: "Gói tập",
                gia_tien: 0,
                thoi_han: "",
                so_buoi: "",
                status: 1
            },
            dich_vu_update: {},
            dich_vu_delete: {},
            noi_dung_tim_kiem: '',
            filterType: '',
            isLoading: false,
            isSaving: false,
            currentPage: 1,
            totalPages: 1,
            totalRecords: 0
        }
    },
    computed: {
        activeCount() {
            return this.list_dich_vu.filter(s => s.status == 1).length;
        },
        inactiveCount() {
            return this.list_dich_vu.filter(s => s.status == 0).length;
        }
    },
    mounted() {
        this.loadData();
    },
    methods: {
        loadData(page = 1) {
            this.isLoading = true;
            axios.get('admin/dich-vu/get-data', {
                params: { 
                    page: page,
                    query: this.noi_dung_tim_kiem,
                    type: this.filterType
                }
            })
                .then((res) => {
                    this.list_dich_vu = res.data.data.data || res.data.data;
                    this.currentPage = res.data.data.current_page || 1;
                    this.totalPages = res.data.data.last_page || 1;
                    this.totalRecords = res.data.data.total || this.list_dich_vu.length;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        themMoi() {
            this.isSaving = true;
            axios.post('admin/dich-vu/add-data', this.create_dich_vu)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData();
                        this.create_dich_vu = { ten_dich_vu: "", loai_dich_vu: "Gói tập", gia_tien: 0, thoi_han: "", so_buoi: "", status: 1 };
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
                        if (modal) modal.hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        openEdit(v) {
            this.dich_vu_update = { ...v };
        },
        capNhat() {
            this.isSaving = true;
            axios.post('admin/dich-vu/update', this.dich_vu_update)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                        if (modal) modal.hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        xoa() {
            this.isSaving = true;
            axios.post('admin/dich-vu/destroy', this.dich_vu_delete)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        if (modal) modal.hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        formatCurrency(value) {
            if (!value) return "0 đ";
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        },
        getServiceIcon(type) {
            const map = {
                'Gói tập': 'fa-solid fa-dumbbell',
                'PT': 'fa-solid fa-user-ninja',
                'Yoga': 'fa-solid fa-spa',
                'Spa': 'fa-solid fa-heart-pulse'
            };
            return map[type] || 'fa-solid fa-gem';
        }
    }
}
</script>
<style>
.bg-light-primary { background-color: #e7f1ff; }
.bg-light-success { background-color: #d1e7dd; }
.bg-light-warning { background-color: #fff3cd; }
.bg-light-info { background-color: #cff4fc; }
</style>

