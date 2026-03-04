<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center mt-2">
                    <h4 class="fw-bold text-primary">Lịch Làm Việc PT</h4>
                    <button class="btn btn-primary" @click="openAdd()">
                        <i class="fa-solid fa-calendar-plus me-1"></i> Thêm Lịch Mới
                    </button>
                </div>
                <div class="card-body">
                    <div v-if="isLoading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Đang tải lịch làm việc...</p>
                    </div>
                    
                    <div v-else class="table-responsive">
                        <table class="table table-bordered align-middle calendar-table">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 100px;" class="text-center">Giờ</th>
                                    <th v-for="day in days" :key="day" class="text-center">{{ day }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="hour in hours" :key="hour">
                                    <td class="text-center fw-bold bg-light">{{ hour }}:00</td>
                                    <td v-for="day in days" :key="day" class="calendar-cell p-1" style="min-height: 80px; width: 13%;">
                                        <div v-for="event in getEvents(day, hour)" :key="event.id" 
                                             class="event-card p-2 mb-1 shadow-sm border-start border-4" 
                                             :class="getEventColorClass(event)"
                                             @click="openEdit(event)">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span class="fw-bold small text-truncate" :title="getStaffNames(event.pt)">{{ getStaffNames(event.pt) }}</span>
                                                <div class="event-actions">
                                                    <i class="fa-solid fa-trash text-danger cursor-pointer" @click.stop="openDelete(event)"></i>
                                                </div>
                                            </div>
                                            <div class="small text-muted text-truncate">{{ event.customer || 'Trống khách hàng' }}</div>
                                            <div class="small fw-medium">{{ event.start }}:00-{{ event.end }}:00</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Quản Lý Lịch -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ editingEvent ? 'Cập Nhật Lịch' : 'Thêm Lịch Làm Việc' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Chọn PT (Nhân Viên)</label>
                            <select v-model="form.pt" multiple class="form-select" size="3">
                                <option v-for="staff in list_staff" :key="staff.id" :value="staff.ho_ten + ' (' + staff.chuc_vu + ')'">
                                    {{ staff.ho_ten }} - {{ staff.chuc_vu }}
                                </option>
                            </select>
                            <small class="text-muted">Nhấn giữ Ctrl (hoặc Cmd) để chọn nhiều người.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tên Khách Hàng</label>
                            <input v-model="form.customer" type="text" class="form-control" placeholder="VD: Anh Bình">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Thứ Trong Tuần</label>
                            <select v-model="form.day" class="form-select">
                                <option v-for="day in days" :key="day" :value="day">{{ day }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bắt Đầu (Giờ)</label>
                            <input v-model.number="form.start" type="number" min="8" max="21" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kết Thúc (Giờ)</label>
                            <input v-model.number="form.end" type="number" min="9" max="22" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" @click="saveEvent()" class="btn btn-primary" :disabled="isSaving">
                        {{ editingEvent ? 'Cập Nhật' : 'Lưu Lịch' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác Nhận Xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Bạn có chắc muốn xóa lịch của <b>{{ deletingEvent?.pt }}</b>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" @click="confirmDelete()" class="btn btn-danger" :disabled="isSaving">Xóa Ngay</button>
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
            days: ["Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "CN"],
            hours: Array.from({ length: 14 }, (_, i) => i + 8),
            events: [],
            list_staff: [],
            form: {
                pt: [],
                customer: "",
                day: "Thứ 2",
                start: 8,
                end: 9,
            },
            editingEvent: null,
            deletingEvent: null,
            isLoading: false,
            isSaving: false,
            modalInstance: null,
            deleteModalInstance: null
        };
    },
    mounted() {
        this.loadData();
        this.loadStaff();
        this.modalInstance = new bootstrap.Modal(document.getElementById('scheduleModal'));
        this.deleteModalInstance = new bootstrap.Modal(document.getElementById('deleteModal'));
    },
    methods: {
        loadData() { // Renamed from getListSchedule
            this.isLoading = true;
            axios.get('admin/lich-lam/get-data') // Relative path
                .then((res) => {
                    this.events = res.data.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        loadStaff() {
            axios.get('admin/nhan-vien/get-data', { params: { paginate: false } })
                .then((res) => {
                    this.list_staff = res.data.data.data || res.data.data;
                });
        },
        openAdd() {
            this.editingEvent = null;
            this.form = { pt: [], customer: "", day: "Thứ 2", start: 8, end: 9 };
            this.modalInstance.show();
        },
        openEdit(event) {
            this.editingEvent = event;
            this.form = { ...event };
            // Ensure pt is an array
            if (typeof this.form.pt === 'string') {
                try {
                    this.form.pt = JSON.parse(this.form.pt);
                } catch(e) {
                    this.form.pt = [this.form.pt];
                }
            }
            if (!Array.isArray(this.form.pt)) {
                this.form.pt = [];
            }
            this.modalInstance.show();
        },
        openDelete(event) {
            this.deletingEvent = event;
            this.deleteModalInstance.show();
        },
        saveEvent() { // This method now handles both add and update
            this.isSaving = true;
            const api = this.editingEvent ? 'admin/lich-lam/update' : 'admin/lich-lam/add-data'; // Relative paths
            axios.post(api, this.form)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData(); // Call the renamed method
                        this.modalInstance.hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => {
                    const errors = err.response?.data?.errors;
                    if (errors) {
                        for (let key in errors) {
                            this.$toast.error(errors[key][0]);
                        }
                    } else {
                        this.$toast.error("Có lỗi xảy ra, vui lòng thử lại!");
                    }
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        confirmDelete() { // Renamed from confirmDelete
            this.isSaving = true;
            axios.post('admin/lich-lam/destroy', this.deletingEvent) // Relative path and new endpoint
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadData(); // Call the renamed method
                        this.deleteModalInstance.hide();
                    } else {
                        this.$toast.error(res.data.message);
                    }
                })
                .catch((err) => {
                    this.$toast.error("Vui lòng tải lại trang và thử lại!");
                })
                .finally(() => {
                    this.isSaving = false;
                });
        },
        getEvents(day, hour) {
            return this.events.filter((e) => e.day === day && e.start === hour);
        },
        getEventColorClass(event) {
            let pts = this.getStaffArray(event.pt);
            let count = pts.length;
            if (count >= 5) return 'border-danger bg-danger-light text-dark';
            if (count >= 3) return 'border-warning bg-warning-light text-dark';
            return 'border-success bg-success-light text-dark';
        },
        getStaffNames(ptData) {
            return this.getStaffArray(ptData).join(', ') || 'Chưa phân công';
        },
        getStaffArray(ptData) {
            if (!ptData) return [];
            if (Array.isArray(ptData)) return ptData;
            try { return JSON.parse(ptData); } catch (e) { return [ptData]; }
        }
    }
};
</script>

<style scoped>
.calendar-table th { min-width: 120px; }
.calendar-cell { background-color: #fff; border: 1px solid #dee2e6; }
.event-card { border-radius: 4px; transition: all 0.2s; cursor: pointer; }
.event-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important; }
.bg-success-light { background-color: #e8f5e9; }
.bg-warning-light { background-color: #fff3e0; }
.bg-danger-light { background-color: #ffebee; }
.cursor-pointer { cursor: pointer; }
.event-actions { opacity: 0.2; transition: opacity 0.2s; }
.event-card:hover .event-actions { opacity: 1; }
</style>
