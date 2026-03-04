<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mt-2 bg-transparent border-bottom-0">
                    <h4 class="fw-bold text-primary mb-0">QUẢN LÝ NHÂN VIÊN</h4>
                    <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fa-solid fa-user-plus me-1"></i> Thêm Nhân Viên
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" v-model="tim_kiem.noi_dung_tim_kiem" class="form-control" placeholder="Tìm theo tên, SĐT hoặc email...">
                                <button class="btn btn-outline-secondary" @click="getListNhanVien()">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr class="table-primary text-center">
                                    <th style="width: 50px;">#</th>
                                    <th>Họ Và Tên</th>
                                    <th>Chức Vụ</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Email</th>
                                    <th>Số Buổi Làm</th>
                                    <th>Lương Cố Định</th>
                                    <th>Trạng Thái</th>
                                    <th style="width: 150px;">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody v-if="!isLoading">
                                <tr v-for="(v, i) in list_nhan_vien" :key="i">
                                    <td class="text-center">{{ i + 1 }}</td>
                                    <td class="fw-bold text-dark">{{ v.ho_ten }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">{{ v.chuc_vu }}</span>
                                    </td>
                                    <td class="text-center">{{ v.sdt }}</td>
                                    <td>{{ v.email }}</td>
                                    <td class="text-center fw-bold text-primary">{{ v.so_buoi_lam || 0 }}</td>
                                    <td class="text-end text-success fw-bold">{{ formatCurrency(v.luong_co_dinh || 0) }}</td>
                                    <td class="text-center">
                                        <span :class="v.trang_thai == 1 ? 'badge bg-success' : 'badge bg-danger'">
                                            {{ v.trang_thai == 1 ? 'Đang Làm' : 'Nghỉ Việc' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm me-1" @click="update_nhan_vien = { ...v }" data-bs-toggle="modal" data-bs-target="#updateModal">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" @click="delete_nhan_vien = { ...v }" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <div class="mt-2 text-muted">Đang tải danh sách nhân viên...</div>
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
                    <h5 class="modal-title">Thêm Nhân Viên Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Họ Tên</label>
                        <input v-model="create_nhan_vien.ho_ten" type="text" class="form-control" placeholder="Nhập họ tên đầy đủ">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số Điện Thoại</label>
                        <input v-model="create_nhan_vien.sdt" type="text" class="form-control" placeholder="Nhập số điện thoại">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input v-model="create_nhan_vien.email" type="email" class="form-control" placeholder="Nhập email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chức Vụ</label>
                        <select v-model="create_nhan_vien.chuc_vu" class="form-select">
                            <option value="Quản lý">Quản lý</option>
                            <option value="Huấn luyện viên">Huấn luyện viên</option>
                            <option value="Lễ tân">Lễ tân</option>
                            <option value="Bảo vệ">Bảo vệ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lương Cố Định</label>
                        <input v-model="create_nhan_vien.luong_co_dinh" type="number" class="form-control" placeholder="Nhập lương VNĐ">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select v-model="create_nhan_vien.trang_thai" class="form-select">
                            <option :value="1">Đang Làm</option>
                            <option :value="0">Nghỉ Việc</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" @click="themMoiNhanVien()" class="btn btn-primary" :disabled="isSaving">Lưu Lại</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập Nhật Nhân Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Họ Tên</label>
                        <input v-model="update_nhan_vien.ho_ten" type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số Điện Thoại</label>
                        <input v-model="update_nhan_vien.sdt" type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input v-model="update_nhan_vien.email" type="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chức Vụ</label>
                        <select v-model="update_nhan_vien.chuc_vu" class="form-select">
                            <option value="Quản lý">Quản lý</option>
                            <option value="Huấn luyện viên">Huấn luyện viên</option>
                            <option value="Lễ tân">Lễ tân</option>
                            <option value="Bảo vệ">Bảo vệ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lương Cố Định</label>
                        <input v-model="update_nhan_vien.luong_co_dinh" type="number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select v-model="update_nhan_vien.trang_thai" class="form-select">
                            <option :value="1">Đang Làm</option>
                            <option :value="0">Nghỉ Việc</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" @click="capNhatNhanVien()" class="btn btn-warning" :disabled="isSaving">Cập Nhật</button>
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
                    Bạn có chắc chắn muốn xóa nhân viên <b>{{ delete_nhan_vien.ho_ten }}</b> không?<br>
                    <small class="text-danger">Thao tác này không thể hoàn tác.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" @click="xoaNhanVien()" class="btn btn-danger" :disabled="isSaving">Xác Nhận Xóa</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '../../../axios';

export default {
    data() {
        return {
            list_nhan_vien: [],
            create_nhan_vien: {
                ho_ten: "",
                sdt: "",
                email: "",
                chuc_vu: "Huấn luyện viên",
                luong_co_dinh: 0,
                trang_thai: 1,
            },
            update_nhan_vien: {},
            delete_nhan_vien: {},
            tim_kiem: {
                noi_dung_tim_kiem: "",
            },
            currentPage: 1,
            totalPages: 1,
            totalRecords: 0,
            isLoading: false,
            isSaving: false,
        };
    },
    mounted() {
        this.getListNhanVien();
    },
    methods: {
        getListNhanVien(page = 1) {
            this.isLoading = true;
            axios.get('admin/nhan-vien/get-data', {
                params: {
                    page: page,
                    query: this.tim_kiem.noi_dung_tim_kiem
                }
            })
                .then((res) => {
                    this.list_nhan_vien = res.data.data.data || res.data.data;
                    this.currentPage = res.data.data.current_page || 1;
                    this.totalPages = res.data.data.last_page || 1;
                    this.totalRecords = res.data.data.total || this.list_nhan_vien.length;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        themMoiNhanVien() {
            this.isSaving = true;
            axios.post('admin/nhan-vien/add-data', this.create_nhan_vien)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.getListNhanVien();
                        this.create_nhan_vien = { ho_ten: "", sdt: "", email: "", chuc_vu: "Huấn luyện viên", luong_co_dinh: 0, trang_thai: 1 };
                        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        capNhatNhanVien() {
            this.isSaving = true;
            axios.post('admin/nhan-vien/update', this.update_nhan_vien)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.getListNhanVien();
                        bootstrap.Modal.getInstance(document.getElementById('updateModal')).hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        xoaNhanVien() {
            this.isSaving = true;
            axios.post('http://127.0.0.1:8000/api/admin/nhan-vien/delete', this.delete_nhan_vien)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.getListNhanVien();
                        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        }
    }
};
</script>
```
