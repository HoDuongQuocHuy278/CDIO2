<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mt-2 bg-transparent border-bottom-0">
                    <h4 class="fw-bold text-primary mb-0">QUẢN LÝ THÀNH VIÊN</h4>
                    <button class="btn btn-primary shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Mới
                    </button>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" v-model="noi_dung_tim_kiem" @keyup.enter="loadData()" class="form-control" placeholder="Tìm kiếm thành viên...">
                        <button class="btn btn-outline-secondary" @click="loadData()">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr class="table-primary text-center">
                                    <th>#</th>
                                    <th>Họ Tên</th>
                                    <th>Số Điện Thoại</th>
                                    <th>Gói Tập</th>
                                    <th>Thời Hạn</th>
                                    <th>Số Buổi Tập/Tháng</th>
                                    <th>Checkin Gần Nhất</th>
                                    <th>Đã Chi (Tháng)</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody v-if="!isLoading">
                                <tr v-for="(v, k) in list_members" :key="v.id">
                                    <td class="text-center align-middle">{{ k + 1 }}</td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <img :src="getFaceUrl(v.id)" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;" @error="$event.target.src='https://ui-avatars.com/api/?name='+v.full_name">
                                            {{ v.full_name }}
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">{{ v.phone }}</td>
                                    <td class="text-center align-middle">{{ v.package_name }}</td>
                                    <td class="text-center align-middle">
                                        <small>{{ v.start_date }}</small><br>
                                        <small class="text-danger">{{ v.end_date }}</small>
                                    </td>
                                    <td class="text-center align-middle fw-bold text-primary">{{ v.monthly_checkins || 0 }} lần</td>
                                    <td class="text-center align-middle"><small>{{ formatDate(v.last_checkin) }}</small></td>
                                    <td class="text-end align-middle fw-bold text-success">{{ formatCurrency(v.monthly_spending || 0) }}</td>
                                    <td class="text-center align-middle">
                                        <span :class="v.status == 1 ? 'badge bg-success' : 'badge bg-danger'">
                                            {{ v.status == 1 ? 'Đang Tập' : 'Hết Hạn' }}
                                        </span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-info btn-sm me-1" @click="openView(v)" data-bs-toggle="modal" data-bs-target="#viewModal">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm me-1" @click="openUpdate(v)" data-bs-toggle="modal" data-bs-target="#updateModal">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" @click="member_delete = v" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status"></div>
                                        <div class="mt-2">Đang tải dữ liệu...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Mới -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Mới Thành Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ Tên</label>
                            <input v-model="create_member.full_name" type="text" class="form-control" placeholder="Nhập họ tên">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Điện Thoại</label>
                            <input v-model="create_member.phone" type="text" class="form-control" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input v-model="create_member.email" type="email" class="form-control" placeholder="Nhập email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gói Tập</label>
                            <select v-model="create_member.package_id" class="form-select" @change="onPackageChange('create')">
                                <option v-for="pkg in list_packages" :key="pkg.id" :value="pkg.id">{{ pkg.ten_dich_vu }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá Gói Tập</label>
                            <input :value="create_package_price ? formatCurrency(create_package_price) : ''" type="text" class="form-control text-danger fw-bold bg-light" disabled placeholder="Chọn gói để xem giá">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày Bắt Đầu</label>
                            <input v-model="create_member.start_date" type="date" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3 text-center">
                            <label class="form-label">Ảnh Khuôn Mặt (Check-in Camera)</label>
                            <div class="d-flex justify-content-center">
                                <WebcamUI ref="webcamUI" @photoTaken="onPhotoTaken" />
                            </div>
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

    <!-- Modal Cập Nhật -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập Nhật Thành Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ Tên</label>
                            <input v-model="member_update.full_name" type="text" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số Điện Thoại</label>
                            <input v-model="member_update.phone" type="text" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input v-model="member_update.email" type="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trạng Thái</label>
                            <select v-model="member_update.status" class="form-select">
                                <option :value="1">Đang Tập</option>
                            <option :value="0">Hết Hạn</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gói Tập</label>
                            <select v-model="member_update.package_id" class="form-select" @change="onPackageChange('update')">
                                <option v-for="pkg in list_packages" :key="pkg.id" :value="pkg.id">{{ pkg.ten_dich_vu }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá Gói Tập</label>
                            <input :value="update_package_price ? formatCurrency(update_package_price) : ''" type="text" class="form-control text-danger fw-bold bg-light" disabled placeholder="Chọn gói để xem giá">
                        </div>
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
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa thành viên <b>{{ member_delete.full_name }}</b> không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" @click="xoa()" class="btn btn-danger" :disabled="isSaving">Xác Nhận Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chi Tiết Thành Viên (existing) -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi Tiết Thành Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img :src="getFaceUrl(member_detail.id)" class="rounded shadow" style="width: 200px; height: 200px; object-fit: cover;" @error="$event.target.src='https://ui-avatars.com/api/?name='+member_detail.full_name">
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Họ Tên</th>
                                <td>{{ member_detail.full_name }}</td>
                            </tr>
                            <tr>
                                <th>Số Điện Thoại</th>
                                <td>{{ member_detail.phone }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ member_detail.email || 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Gói Tập</th>
                                <td>{{ member_detail.package_name }}</td>
                            </tr>
                            <tr>
                                <th>Ngày Hết Hạn</th>
                                <td class="text-danger fw-bold">{{ member_detail.end_date }}</td>
                            </tr>
                            <tr>
                                <th>Tổng Số Tháng Đã Tập</th>
                                <td class="text-primary fw-bold">{{ calculateMonths(member_detail.start_date) }} tháng</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal In Hóa Đơn -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content overflow-hidden border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-4 text-center d-block">
                    <h4 class="fw-bold mb-1">WELLFIT GYM</h4>
                    <p class="small mb-0 opacity-75 text-uppercase">Hóa Đơn Thanh Toán</p>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="text-center mb-4">
                        <div class="display-6 fw-bold text-dark mb-1">{{ formatCurrency(new_invoice?.amount || 0) }}</div>
                        <span class="badge bg-success text-white px-3">Đã thanh toán</span>
                    </div>
                    
                    <div class="border-top border-bottom py-3 mb-4">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Mã hóa đơn</span>
                            <span class="fw-bold text-dark">{{ new_invoice?.code }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Ngày lập</span>
                            <span class="fw-bold text-dark">{{ new_invoice?.date }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Khách hàng</span>
                            <span class="fw-bold text-dark">{{ new_invoice?.customer }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Thanh toán</span>
                            <span class="fw-bold text-dark">{{ new_invoice?.method }}</span>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 py-2 px-3 small">
                        <i class="fa-solid fa-info-circle me-1"></i> Cảm ơn bạn đã tin tưởng dịch vụ của chúng tôi!
                    </div>
                </div>
                <div class="modal-footer bg-white border-0 py-3 d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light px-4 btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" @click="printInvoice()" class="btn btn-primary px-4 btn-sm"><i class="fa-solid fa-print me-1"></i> In HĐ</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '../../../axios';
import WebcamUI from '../../Webcam/WebCamUI.vue';

export default {
    components: { WebcamUI },
    data() {
        return {
            list_members: [],
            list_packages: [],
            create_member: {
                full_name: '',
                phone: '',
                email: '',
                thoi_han: 1,
                start_date: new Date().toISOString().split('T')[0],
                package_id: null,
                face_image: null
            },
            member_update: {},
            member_delete: {},
            member_detail: {},
            new_invoice: null,
            noi_dung_tim_kiem: '',
            isLoading: false,
            isSaving: false,
            lastReload: new Date().getTime()
        }
    },
    computed: {
        create_package_price() {
            const pkg = this.list_packages.find(p => p.id === this.create_member.package_id);
            return pkg ? pkg.gia_tien : 0;
        },
        update_package_price() {
            const pkg = this.list_packages.find(p => p.id === this.member_update.package_id);
            return pkg ? pkg.gia_tien : 0;
        }
    },
    mounted() {
        this.loadData();
        this.loadPackages();
    },
    methods: {
        loadData() {
            this.isLoading = true;
            this.lastReload = new Date().getTime();
            axios.get('admin/thanh-vien/get-data', {
                params: { query: this.noi_dung_tim_kiem }
            })
                .then((res) => {
                    this.list_members = res.data.data.data || res.data.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        loadPackages() {
            axios.get('admin/thanh-vien/get-packages')
                .then((res) => {
                    this.list_packages = res.data.data;
                });
        },
        themMoi() {
            if (this.isSaving) return;
            if (!this.create_member.face_image) {
                this.$toast.warning("Vui lòng chụp ảnh khuôn mặt để check-in!");
                return;
            }
            if (!this.create_member.package_id) {
                this.$toast.warning("Vui lòng chọn gói tập!");
                return;
            }
            this.isSaving = true;
            axios.post('admin/thanh-vien/add-data', this.create_member)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData();
                        this.new_invoice = res.data.invoice;
                        this.create_member = { 
                            full_name: '',
                            phone: '',
                            email: '',
                            thoi_han: 1, 
                            start_date: new Date().toISOString().split('T')[0], 
                            face_image: null 
                        };
                        if (this.$refs.webcamUI) {
                            this.$refs.webcamUI.retakePhoto();
                        }
                        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                        
                        if (this.new_invoice) {
                            setTimeout(() => {
                                const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
                                receiptModal.show();
                            }, 500);
                        }
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => {
                    const message = err.response?.data?.message || "Lỗi khi thêm thành viên!";
                    this.$toast.error(message);
                    if (err.response?.data?.errors) {
                        const errors = err.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            this.$toast.error(errors[key][0]);
                        });
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        printInvoice() {
            window.print();
        },
        onPhotoTaken(data) {
            this.create_member.face_image = data.image_data_url;
        },
        openUpdate(v) {
            this.member_update = { ...v, thoi_han: v.package_duration || 1 };
        },
        onPackageChange(type) {
            if (type === 'create') {
                const pkg = this.list_packages.find(p => p.id === this.create_member.package_id);
                if (pkg) this.create_member.thoi_han = pkg.thoi_han;
            } else if (type === 'update') {
                const pkg = this.list_packages.find(p => p.id === this.member_update.package_id);
                if (pkg) this.member_update.thoi_han = pkg.thoi_han;
            }
        },
        capNhat() {
            if (this.isSaving) return;
            this.isSaving = true;
            axios.post('admin/thanh-vien/update', this.member_update)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData();
                        bootstrap.Modal.getInstance(document.getElementById('updateModal')).hide();
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
            axios.post('admin/thanh-vien/destroy', this.member_delete)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData();
                        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        openView(v) {
            this.member_detail = { ...v };
        },
        getFaceUrl(id) {
            if (!id) return '';
            // Only add timestamp if we have a valid lastReload (meaning logic has run at least once)
            const ts = this.lastReload || '';
            return `http://127.0.0.1:8000/storage/member_faces/${id}.jpg?t=` + ts;
        },
        calculateMonths(startDate) {
            if (!startDate) return 0;
            const start = new Date(startDate);
            const now = new Date();
            const diffTime = now - start;
            if (diffTime < 0) return 0;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return Math.round(diffDays / 30);
        },
        formatDate(date) {
            if (!date) return 'Chưa có';
            return new Date(date).toLocaleString('vi-VN');
        },
        formatCurrency(value) {
            if (!value) return "0 đ";
            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
        }
    }
}
</script>
<style></style>
