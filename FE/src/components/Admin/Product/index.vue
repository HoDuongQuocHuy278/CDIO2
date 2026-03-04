<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mt-2 bg-transparent border-bottom-0">
                    <h4 class="fw-bold text-primary mb-0">QUẢN LÝ SẢN PHẨM</h4>
                    <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Sản Phẩm
                    </button>
                </div>
                <div class="card-body">
                    <!-- Stat Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light-primary border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Tổng Sản Phẩm</h6>
                                    <h3 class="fw-bold mb-0 text-primary">{{ totalRecords }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-success border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Tổng Tồn Kho</h6>
                                    <h3 class="fw-bold mb-0 text-success">{{ totalStock }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-info border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Giá Trị Kho</h6>
                                    <h3 class="fw-bold mb-0 text-info">{{ totalValue }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light-danger border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-1">Sắp Hết Hàng</h6>
                                    <h3 class="fw-bold mb-0 text-danger">{{ lowStockCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="noi_dung_tim_kiem" @keyup.enter="loadData()" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                                <button class="btn btn-outline-secondary" @click="loadData()">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select v-model="filterCategory" class="form-select" @change="loadData()">
                                <option value="">Tất cả danh mục</option>
                                <option v-for="cat in list_categories" :key="cat" :value="cat">{{ cat }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="table-primary text-center">
                                    <th style="width: 50px;">#</th>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Danh Mục</th>
                                    <th>Giá Bán</th>
                                    <th>Tồn Kho</th>
                                    <th>Trạng Thái</th>
                                    <th style="width: 150px;">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody v-if="!isLoading">
                                <tr v-for="(v, i) in list_san_pham" :key="i">
                                    <td class="text-center">{{ i + 1 }}</td>
                                    <td class="fw-bold text-dark">{{ v.ten_san_pham }}</td>
                                    <td class="text-center"><span class="badge bg-secondary">{{ v.danh_muc }}</span></td>
                                    <td class="text-end fw-bold text-success">{{ formatCurrency(v.gia_ban) }}</td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span :class="v.so_luong < 20 ? 'text-danger fw-bold' : 'text-dark'">{{ v.so_luong }}</span>
                                            <div class="progress w-75" style="height: 5px;">
                                                <div class="progress-bar" role="progressbar" :style="{ width: Math.min(v.so_luong, 100) + '%' }" :class="v.so_luong < 20 ? 'bg-danger' : 'bg-success'"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span :class="v.status == 1 ? 'badge bg-success' : 'badge bg-danger'">
                                            {{ v.status == 1 ? 'Đang Bán' : 'Ngừng Bán' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm me-1" @click="openEdit(v)" data-bs-toggle="modal" data-bs-target="#editModal">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" @click="san_pham_delete = v" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <div class="mt-2 text-muted">Đang tải dữ liệu sản phẩm...</div>
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
                    <h5 class="modal-title">Thêm Sản Phẩm Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên Sản Phẩm</label>
                        <input v-model="create_san_pham.ten_san_pham" type="text" class="form-control" placeholder="Nhập tên sản phẩm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Danh Mục</label>
                        <select v-model="create_san_pham.danh_muc" class="form-select">
                            <option v-for="cat in list_categories" :key="cat" :value="cat">{{ cat }}</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá Bán (VNĐ)</label>
                            <input v-model="create_san_pham.gia_ban" type="number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tồn Kho</label>
                            <input v-model="create_san_pham.so_luong" type="number" class="form-control">
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
                    <h5 class="modal-title">Cập Nhật Sản Phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên Sản Phẩm</label>
                        <input v-model="san_pham_update.ten_san_pham" type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Danh Mục</label>
                        <select v-model="san_pham_update.danh_muc" class="form-select">
                            <option v-for="cat in list_categories" :key="cat" :value="cat">{{ cat }}</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá Bán (VNĐ)</label>
                            <input v-model="san_pham_update.gia_ban" type="number" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tồn Kho</label>
                            <input v-model="san_pham_update.so_luong" type="number" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select v-model="san_pham_update.status" class="form-select">
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
                    Bạn có chắc chắn muốn xóa sản phẩm <b>{{ san_pham_delete.ten_san_pham }}</b> không?<br>
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
            list_san_pham: [],
            list_categories: ["Thực phẩm bổ sung", "Phụ kiện", "Đồ uống", "Thời trang tập"],
            create_san_pham: { 
                ten_san_pham: "", 
                danh_muc: "Thực phẩm bổ sung", 
                gia_ban: 0, 
                so_luong: 0, 
                status: 1 
            },
            san_pham_update: {},
            san_pham_delete: {},
            noi_dung_tim_kiem: '',
            filterCategory: '',
            isLoading: false,
            isSaving: false,
            totalRecords: 0
        }
    },
    computed: {
        totalStock() {
            return this.list_san_pham.reduce((s, p) => s + Number(p.so_luong), 0);
        },
        totalValue() {
            const total = this.list_san_pham.reduce((s, p) => s + Number(p.gia_ban) * Number(p.so_luong), 0);
            return this.formatCurrency(total);
        },
        lowStockCount() {
            return this.list_san_pham.filter(p => p.so_luong < 20).length;
        }
    },
    mounted() {
        this.loadData();
    },
    methods: {
        loadData() {
            this.isLoading = true;
            axios.get('admin/san-pham/get-data', {
                params: { 
                    query: this.noi_dung_tim_kiem,
                    category: this.filterCategory
                }
            })
                .then((res) => {
                    this.list_san_pham = res.data.data.data || res.data.data;
                    this.totalRecords = res.data.data.total || this.list_san_pham.length;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        themMoi() {
            this.isSaving = true;
            axios.post('admin/san-pham/add-data', this.create_san_pham)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData();
                        this.create_san_pham = { ten_san_pham: "", danh_muc: "Thực phẩm bổ sung", gia_ban: 0, so_luong: 0, status: 1 };
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
            this.san_pham_update = { ...v };
        },
        capNhat() {
            this.isSaving = true;
            axios.post('admin/san-pham/update', this.san_pham_update)
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
            axios.post('admin/san-pham/destroy', this.san_pham_delete)
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
        }
    }
}
</script>
<style>
.bg-light-primary { background-color: #e7f1ff; }
.bg-light-success { background-color: #d1e7dd; }
.bg-light-info { background-color: #cff4fc; }
.bg-light-danger { background-color: #f8d7da; }
</style>
