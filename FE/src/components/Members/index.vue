<template>
    <div class="members-page">
        <div class="row">
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0 text-uppercase font-weight-bold">👥 Quản lý thành viên</h5>
                            </div>
                            <div class="header-actions">
                                <button @click="goBack" class="btn btn-secondary btn-sm me-2">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
                                </button>
                                <button @click="showAddForm" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus me-1"></i> Thêm thành viên
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Form Modal -->
        <div v-if="showForm" class="modal-overlay" @click.self="closeForm">
            <div class="modal-content card radius-10">
                <div class="card-header bg-transparent border-bottom-0">
                    <h5 class="mb-0 text-uppercase font-weight-bold">{{ isEditing ? 'Cập nhật thành viên' : 'Thêm thành viên mới' }}</h5>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submitForm" class="member-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ tên *</label>
                                <input v-model="formData.ho_ten" type="text" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại *</label>
                                <input v-model="formData.sdt" type="tel" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày sinh *</label>
                                <input v-model="formData.ngay_sinh" type="date" class="form-control" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input v-model="formData.email" type="email" class="form-control" required />
                            </div>
                            <div class="col-12">
                                <label class="form-label">Địa chỉ *</label>
                                <textarea v-model="formData.dia_chi" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" @click="closeForm" class="btn btn-secondary me-2">Hủy</button>
                            <button type="submit" class="btn btn-primary">{{ isEditing ? 'Cập nhật' : 'Thêm mới' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Members List -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="search-bar mb-4">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input 
                                    v-model="searchQuery" 
                                    type="text" 
                                    class="form-control border-start-0" 
                                    placeholder="Tìm kiếm theo tên, SĐT, email..."
                                />
                            </div>
                        </div>

                        <div v-if="loading" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Đang tải...</span>
                            </div>
                            <p class="mt-2">Đang tải dữ liệu...</p>
                        </div>

                        <div v-else-if="filteredMembers.length === 0" class="text-center py-5">
                            <p class="text-muted">Không có thành viên nào</p>
                        </div>

                        <div v-else class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Họ tên</th>
                                        <th>SĐT</th>
                                        <th>Email</th>
                                        <th>Ngày sinh</th>
                                        <th>Địa chỉ</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="member in filteredMembers" :key="member.id">
                                        <td>{{ member.id }}</td>
                                        <td><strong>{{ member.ho_ten }}</strong></td>
                                        <td>{{ member.sdt }}</td>
                                        <td>{{ member.email }}</td>
                                        <td>{{ formatDate(member.ngay_sinh) }}</td>
                                        <td>{{ member.dia_chi }}</td>
                                        <td>
                                            <div class="d-flex order-actions">
                                                <button @click="editMember(member)" class="btn btn-warning btn-sm me-2">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <button @click="deleteMember(member.id)" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
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
    </div>
</template>

<script>
import axios from '../../config/axios';

export default {
    name: 'MembersManagement',
    data() {
        return {
            members: [],
            searchQuery: '',
            loading: false,
            showForm: false,
            isEditing: false,
            formData: {
                id: null,
                ho_ten: '',
                sdt: '',
                email: '',
                ngay_sinh: '',
                dia_chi: ''
            }
        }
    },
    computed: {
        filteredMembers() {
            if (!this.searchQuery) return this.members;
            
            const query = this.searchQuery.toLowerCase();
            return this.members.filter(member => 
                member.ho_ten.toLowerCase().includes(query) ||
                member.sdt.includes(query) ||
                member.email.toLowerCase().includes(query)
            );
        }
    },
    methods: {
        async loadMembers() {
            this.loading = true;
            try {
                const res = await axios.get('/admin/members');
                if (res.data.status) {
                    this.members = res.data.data;
                }
            } catch (error) {
                console.error('Lỗi tải danh sách thành viên:', error);
                this.$toast.error('Không thể tải danh sách thành viên');
            } finally {
                this.loading = false;
            }
        },
        showAddForm() {
            this.isEditing = false;
            this.resetForm();
            this.showForm = true;
        },
        editMember(member) {
            this.isEditing = true;
            this.formData = {
                id: member.id,
                ho_ten: member.ho_ten,
                sdt: member.sdt,
                email: member.email,
                ngay_sinh: member.ngay_sinh,
                dia_chi: member.dia_chi
            };
            this.showForm = true;
        },
        async submitForm() {
            try {
                if (this.isEditing) {
                    const res = await axios.put(`/admin/members/${this.formData.id}`, this.formData);
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadMembers();
                        this.closeForm();
                    }
                } else {
                    const res = await axios.post('/admin/members', this.formData);
                    if (res.data.status) {
                        this.$toast.success(res.data.message);
                        this.loadMembers();
                        this.closeForm();
                    }
                }
            } catch (error) {
                console.error('Lỗi:', error);
                if (error.response && error.response.data) {
                    this.$toast.error(error.response.data.message || 'Có lỗi xảy ra');
                } else {
                    this.$toast.error('Không thể kết nối đến server');
                }
            }
        },
        async deleteMember(id) {
            if (!confirm('Bạn có chắc muốn xóa thành viên này?')) return;
            
            try {
                const res = await axios.delete(`/admin/members/${id}`);
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.loadMembers();
                }
            } catch (error) {
                console.error('Lỗi xóa thành viên:', error);
                this.$toast.error('Không thể xóa thành viên');
            }
        },
        closeForm() {
            this.showForm = false;
            this.resetForm();
        },
        resetForm() {
            this.formData = {
                id: null,
                ho_ten: '',
                sdt: '',
                email: '',
                ngay_sinh: '',
                dia_chi: ''
            };
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN');
        },
        goBack() {
            this.$router.push('/');
        }
    },
    mounted() {
        const token = localStorage.getItem('key_admin');
        if (!token) {
            this.$router.push('/login');
            return;
        }
        this.loadMembers();
    }
}
</script>

<style scoped>
.members-page {
    padding: 20px;
    background-color: #f8f9fa;
    min-height: 100vh;
}
.radius-10 {
    border-radius: 10px;
}
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    width: 90%;
    max-width: 600px;
    background: white;
}
.order-actions button {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
