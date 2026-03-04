<template>
    <div class="row">
        <div class="col-lg-12">
            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1 opacity-75">Tổng Thiết Bị</p>
                                    <h3 class="fw-bold mb-0">6</h3>
                                </div>
                                <div class="fs-1 opacity-25"><i class="fa-solid fa-dumbbell"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1 opacity-75">Hoạt Động Tốt</p>
                                    <h3 class="fw-bold mb-0">4</h3>
                                </div>
                                <div class="fs-1 opacity-25"><i class="fa-solid fa-check-circle"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-warning text-dark">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1 opacity-75">Cần Bảo Trì</p>
                                    <h3 class="fw-bold mb-0">1</h3>
                                </div>
                                <div class="fs-1 opacity-25"><i class="fa-solid fa-wrench"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-danger text-white">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-1 opacity-75">Đang Hỏng</p>
                                    <h3 class="fw-bold mb-0">1</h3>
                                </div>
                                <div class="fs-1 opacity-25"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 border-bottom-0">
                    <h5 class="fw-bold text-primary mb-0">DANH SÁCH THIẾT BỊ</h5>
                    <button class="btn btn-primary shadow-sm px-4" @click="openAdd()">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Thiết Bị
                    </button>
                </div>
                <div class="card-body">
                    <div v-if="isLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Đang tải thiết bị...</p>
                    </div>
                    <div v-else class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Thiết Bị</th>
                                    <th>Loại</th>
                                    <th>Phòng</th>
                                    <th>Tình Trạng</th>
                                    <th class="text-end">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in list_thiet_bi" :key="item.id">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded p-2 me-3">
                                                <i class="fa-solid fa-gear text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ item.ten_thiet_bi }}</div>
                                                <div class="small text-muted">ID: {{ item.id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ item.type?.ten_loai || 'N/A' }}</td>
                                    <td>{{ item.room?.ten_phong || 'N/A' }}</td>
                                    <td>
                                        <span :class="statusBadge(item.tinh_trang)" class="badge">
                                            {{ statusToLabel(item.tinh_trang) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info me-1" @click="openEdit(item)">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" @click="openDelete(item)">
                                            <i class="fa-solid fa-trash"></i>
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

    <!-- Modal Form -->
    <div class="modal fade" id="deviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ isEditing ? 'Cập Nhật Thiết Bị' : 'Thêm Thiết Bị' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tên Thiết Bị</label>
                            <input v-model="form.ten_thiet_bi" type="text" class="form-control" placeholder="VD: Máy chạy bộ Matrix">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại Thiết Bị (ID)</label>
                            <input v-model.number="form.type_id" type="number" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phòng (ID)</label>
                            <input v-model.number="form.room_id" type="number" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Tình Trạng</label>
                            <select v-model="form.tinh_trang" class="form-select">
                                <option :value="1">Hoạt động tốt</option>
                                <option :value="2">Cần bảo trì</option>
                                <option :value="3">Đang hỏng</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nhà Cung Cấp (ID)</label>
                            <input v-model.number="form.supplier_id" type="number" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" @click="saveDevice()" class="btn btn-primary" :disabled="isSaving">
                        {{ isSaving ? 'Đang lưu...' : (isEditing ? 'Cập Nhật' : 'Lưu Lại') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-center py-4">
                <div class="modal-body">
                    <i class="fa-solid fa-circle-exclamation text-danger fs-1 mb-3"></i>
                    <h5 class="fw-bold">Xác Nhận Xóa</h5>
                    <p class="text-muted">Bạn có chắc chắn muốn xóa thiết bị <br> <b>{{ deletingDevice?.ten_thiet_bi }}</b>?</p>
                </div>
                <div class="d-flex justify-content-center gap-2 px-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" @click="confirmDelete()" class="btn btn-danger px-4" :disabled="isSaving">Xóa Bỏ</button>
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
            list_thiet_bi: [],
            form: { ten_thiet_bi: "", type_id: 1, supplier_id: 1, room_id: 1, tinh_trang: 1 },
            isEditing: false,
            deletingDevice: null,
            currentPage: 1,
            totalPages: 1,
            isLoading: false,
            isSaving: false,
            modalInstance: null,
            deleteModalInstance: null
        };
    },
    mounted() {
        this.getListThietBi();
        this.modalInstance = new bootstrap.Modal(document.getElementById('deviceModal'));
        this.deleteModalInstance = new bootstrap.Modal(document.getElementById('deleteModal'));
    },
    methods: {
        getListThietBi(page = 1) {
            this.isLoading = true;
            axios.get(`admin/thiet-bi/get-data?page=${page}`)
                .then((res) => {
                    this.list_thiet_bi = res.data.data.data;
                    this.currentPage = res.data.data.current_page;
                    this.totalPages = res.data.data.last_page;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.getListThietBi(page);
            }
        },
        openAdd() {
            this.isEditing = false;
            this.form = { ten_thiet_bi: "", type_id: 1, supplier_id: 1, room_id: 1, tinh_trang: 1 };
            this.modalInstance.show();
        },
        openEdit(item) {
            this.isEditing = true;
            this.form = { ...item };
            this.modalInstance.show();
        },
        openDelete(item) {
            this.deletingDevice = item;
            this.deleteModalInstance.show();
        },
        saveDevice() {
            this.isSaving = true;
            const api = this.isEditing ? 'admin/thiet-bi/update' : 'admin/thiet-bi/add-data';
            axios.post(api, this.form)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.getListThietBi(this.currentPage);
                        this.modalInstance.hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        confirmDelete() {
            this.isSaving = true;
            axios.post('admin/thiet-bi/delete', this.deletingDevice)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.getListThietBi(this.currentPage);
                        this.deleteModalInstance.hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        statusBadge(status) {
            if (status == 1) return "bg-success-soft text-success";
            if (status == 2) return "bg-warning-soft text-warning";
            return "bg-danger-soft text-danger";
        },
        statusToLabel(status) {
            if (status == 1) return "Hoạt động tốt";
            if (status == 2) return "Cần bảo trì";
            return "Đang hỏng";
        }
    }
};
</script>

<style scoped>
.avatar-sm { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
.bg-success-soft { background-color: #e6f4ea; }
.bg-warning-soft { background-color: #fff8e1; }
.bg-danger-soft { background-color: #fce8e6; }
</style>
