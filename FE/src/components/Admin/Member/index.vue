<template>
    <div class="page-container">
        <div class="content-wrapper">
            <div class="header-section">
                <div class="header-content">
                    <h2 class="page-title">Quản lý thành viên</h2>
                    <p class="page-subtitle">Quản lý hồ sơ, gói tập và theo dõi nguy cơ rời bỏ (Churn Risk)</p>
                </div>

                <div class="action-bar">
                    <div class="search-wrapper">
                        <i class="fa-solid fa-magnifying-glass search-icon"></i>
                        <input type="text" class="search-input" v-model="tim_kiem.noi_dung_tim_kiem"
                            @keyup.enter="timKiem()" placeholder="Tìm kiếm thành viên...">
                    </div>
                    <button class="btn-action primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm mới</span>
                    </button>
                </div>
            </div>

            <div class="table-card">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="col-member">Thành viên</th>
                            <th class="col-contact">Liên hệ</th>
                            <th class="col-package">Gói đăng ký</th>
                            <th class="col-date">Thời hạn (Start - End)</th>
                            <th class="col-warning">Cảnh báo / Vắng</th>
                            <th class="col-action">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(member, index) in list_members" :key="index"
                            :class="{ 'risk-row': getDaysAbsent(member.ngay_tap_cuoi) > 14 }">

                            <td>
                                <div class="member-profile">
                                    <div class="avatar" :class="getRandomColor(index)">
                                        <img v-if="member.avatar" :src="member.avatar" alt="">
                                        <span v-else>{{ getInitials(member.ho_ten) }}</span>
                                    </div>
                                    <div class="profile-info">
                                        <span class="full-name">{{ member.ho_ten }}</span>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="id-tag">ID: #{{ member.id }}</span>
                                            <span class="mini-status"
                                                :class="member.trang_thai === 1 ? 'active' : 'expired'">
                                                {{ member.trang_thai === 1 ? 'Active' : 'Expired' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="contact-group">
                                    <div class="contact-row"><i class="fa-solid fa-phone"></i> {{ member.sdt }}</div>
                                    <div class="contact-row secondary"><i class="fa-solid fa-envelope"></i> {{
                                        member.email }}</div>
                                </div>
                            </td>

                            <td>
                                <div class="package-tag">
                                    <i class="fa-solid fa-dumbbell"></i> {{ member.goi_dang_ky }}
                                </div>
                            </td>

                            <td>
                                <div class="date-column">
                                    <div class="date-row start" title="Ngày bắt đầu">
                                        <i class="fa-regular fa-calendar-plus"></i> {{ member.ngay_cap }}
                                    </div>
                                    <div class="date-row end" title="Ngày hết hạn">
                                        <i class="fa-regular fa-calendar-xmark"></i> {{ member.ngay_het_han }}
                                    </div>
                                </div>
                            </td>

                            <td class="cell-center">
                                <div class="risk-container">
                                    <div v-if="getDaysAbsent(member.ngay_tap_cuoi) > 14"
                                        class="risk-badge danger blink">
                                        <i class="fa-solid fa-triangle-exclamation"></i> Nguy cơ cao
                                    </div>
                                    <div v-else-if="getDaysAbsent(member.ngay_tap_cuoi) > 7" class="risk-badge warning">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Cần chú ý
                                    </div>
                                    <div v-else class="risk-badge safe">
                                        <i class="fa-solid fa-check"></i> Đều đặn
                                    </div>

                                    <span class="days-absent">
                                        Vắng: <b>{{ getDaysAbsent(member.ngay_tap_cuoi) }}</b> ngày
                                    </span>
                                </div>
                            </td>

                            <td class="cell-end">
                                <div class="action-group">
                                    <button v-if="getDaysAbsent(member.ngay_tap_cuoi) > 7" class="btn-icon zalo-btn"
                                        title="Gửi tin nhắn nhắc nhở">
                                        <i class="fa-solid fa-comment-dots"></i>
                                    </button>

                                    <button class="btn-icon edit" @click="Object.assign(update_member, member)"
                                        data-bs-toggle="modal" data-bs-target="#updateModal" title="Sửa">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="btn-icon delete" @click="Object.assign(delete_member, member)"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination-area">
                    <span class="pagination-info">Hiển thị <b>3</b> trên <b>128</b> kết quả</span>
                    <div class="pagination-buttons">
                        <button class="page-btn disabled"><i class="fa-solid fa-chevron-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header-custom primary-header">
                    <h5 class="modal-title-custom"><i class="fa-solid fa-user-plus me-2"></i>Thêm Thành Viên</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body-custom">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="input-label">Họ và Tên <span class="req">*</span></label>
                            <input v-model="create_member.ho_ten" type="text" class="input-field"
                                placeholder="Nhập họ tên..." />
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Số điện thoại <span class="req">*</span></label>
                            <input v-model="create_member.sdt" type="text" class="input-field"
                                placeholder="Nhập SĐT..." />
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Email</label>
                            <input v-model="create_member.email" type="email" class="input-field"
                                placeholder="example@gmail.com" />
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Gói Đăng Ký</label>
                            <select v-model="create_member.goi_dang_ky" class="input-field select-field">
                                <option value="" disabled selected>Chọn gói tập...</option>
                                <option value="Premium 6 Tháng">Premium 6 Tháng</option>
                                <option value="Standard 1 Tháng">Standard 1 Tháng</option>
                                <option value="VIP 12 Tháng">VIP 12 Tháng</option>
                                <option value="Yoga Basic">Yoga Basic</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Ngày Bắt Đầu</label>
                            <input v-model="create_member.ngay_cap" type="date" class="input-field" />
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Ngày Hết Hạn</label>
                            <input v-model="create_member.ngay_het_han" type="date" class="input-field" />
                        </div>
                        <div class="col-md-12">
                            <label class="input-label">Trạng Thái</label>
                            <select v-model.number="create_member.trang_thai" class="input-field select-field">
                                <option :value="1">Đang tập (Active)</option>
                                <option :value="0">Đã hết hạn/Dừng</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-action secondary" data-bs-dismiss="modal">Đóng</button>
                    <button @click="ThemMoiThanhVien()" type="button" class="btn-action primary"
                        data-bs-dismiss="modal">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Hồ Sơ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header-custom primary-header">
                    <h5 class="modal-title-custom"><i class="fa-solid fa-pen-to-square me-2"></i>Cập Nhật Thông Tin</h5>
                    <button type="button" class="btn-close-custom" data-bs-dismiss="modal"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body-custom">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="input-label">Họ và tên</label>
                            <input v-model="update_member.ho_ten" type="text" class="input-field" />
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Số điện thoại</label>
                            <input v-model="update_member.sdt" type="text" class="input-field" />
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Email</label>
                            <input v-model="update_member.email" type="email" class="input-field" />
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Gói đăng ký</label>
                            <select v-model="update_member.goi_dang_ky" class="input-field select-field">
                                <option value="Premium 6 Tháng">Premium 6 Tháng</option>
                                <option value="Standard 1 Tháng">Standard 1 Tháng</option>
                                <option value="VIP 12 Tháng">VIP 12 Tháng</option>
                                <option value="Yoga Basic">Yoga Basic</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Ngày Bắt Đầu</label>
                            <input v-model="update_member.ngay_cap" type="date" class="input-field" />
                        </div>
                        <div class="col-md-6">
                            <label class="input-label">Ngày Kết Thúc</label>
                            <input v-model="update_member.ngay_het_han" type="date" class="input-field" />
                        </div>
                        <div class="col-md-12">
                            <label class="input-label">Trạng Thái</label>
                            <select v-model.number="update_member.trang_thai" class="input-field select-field">
                                <option :value="1">Đang tập</option>
                                <option :value="0">Hết hạn</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button type="button" class="btn-action secondary" data-bs-dismiss="modal">Đóng</button>
                    <button v-on:click="CapNhatThanhVien()" type="button" class="btn-action primary"
                        data-bs-dismiss="modal">
                        <i class="fa-solid fa-check me-1"></i> Cập Nhật
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-body-custom text-center-content">
                    <div class="icon-circle danger-icon">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    <h4 class="confirm-title">Xác nhận xóa?</h4>
                    <p class="confirm-desc">Thành viên <strong>{{ delete_member.ho_ten }}</strong> sẽ bị xóa vĩnh viễn
                        khỏi hệ thống.</p>
                    <div class="confirm-actions">
                        <button type="button" class="btn-action secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button v-on:click="xoaThanhVien()" type="button" class="btn-action danger"
                            data-bs-dismiss="modal">Xóa thành viên</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import './index.css';

export default {
    data() {
        return {
            // MOCK DATA: Giả định ngày hiện tại hệ thống là 28/01/2026
            list_members: [
                {
                    id: 101, ho_ten: 'Nguyễn Văn An', sdt: '0901234567', email: 'an.nguyen@gmail.com', avatar: '',
                    goi_dang_ky: 'Premium 6 Tháng', trang_thai: 1,
                    ngay_cap: '15/01/2026', ngay_het_han: '15/07/2026',
                    ngay_tap_cuoi: '2026-01-27' // Vắng 1 ngày -> An toàn
                },
                {
                    id: 102, ho_ten: 'Trần Thị Bình', sdt: '0912345678', email: 'binh.tran@gmail.com', avatar: '',
                    goi_dang_ky: 'Standard 1 Tháng', trang_thai: 1,
                    ngay_cap: '01/04/2026', ngay_het_han: '01/05/2026',
                    ngay_tap_cuoi: '2026-01-10' // Vắng 18 ngày -> BỊ BÔI ĐỎ (Risk)
                },
                {
                    id: 103, ho_ten: 'Lê Hoàng Cường', sdt: '0988777666', email: 'cuong.gym@hotmail.com', avatar: '',
                    goi_dang_ky: 'VIP 12 Tháng', trang_thai: 1,
                    ngay_cap: '20/01/2025', ngay_het_han: '20/01/2026',
                    ngay_tap_cuoi: '2026-01-20' // Vắng 8 ngày -> Cảnh báo vàng
                },
            ],
            create_member: {
                ho_ten: '', sdt: '', email: '', goi_dang_ky: '', ngay_cap: '', ngay_het_han: '', trang_thai: 1
            },
            update_member: {},
            delete_member: {},
            tim_kiem: { noi_dung_tim_kiem: "" },
        }
    },
    methods: {
        getInitials(name) { return name ? name.split(' ').pop().charAt(0).toUpperCase() : ''; },
        getRandomColor(index) {
            const colors = ['bg-gradient-blue', 'bg-gradient-purple', 'bg-gradient-green', 'bg-gradient-orange'];
            return colors[index % colors.length];
        },

        // --- LOGIC TÍNH NGÀY VẮNG MẶT ---
        getDaysAbsent(lastDateStr) {
            if (!lastDateStr) return 0;
            // Giả lập "Hôm nay" là 28/01/2026 để khớp với data mẫu. 
            // Khi chạy thật bạn đổi dòng dưới thành: const today = new Date();
            const today = new Date('2026-01-28');
            const lastDate = new Date(lastDateStr);

            const diffTime = today - lastDate;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays > 0 ? diffDays : 0;
        },

        ThemMoiThanhVien() {
            const newId = Math.floor(Math.random() * 1000) + 100;
            const todayStr = new Date().toISOString().split('T')[0]; // YYYY-MM-DD

            // Mặc định người mới thêm coi như vừa tập hôm nay
            const newMember = {
                ...this.create_member,
                id: newId,
                avatar: '',
                ngay_tap_cuoi: todayStr
            };
            this.list_members.unshift(newMember);

            // Reset form
            this.create_member = { ho_ten: '', sdt: '', email: '', goi_dang_ky: '', ngay_cap: '', ngay_het_han: '', trang_thai: 1 };
        },
        CapNhatThanhVien() { alert(`Đã cập nhật: ${this.update_member.ho_ten}`); },
        xoaThanhVien() {
            this.list_members = this.list_members.filter(m => m.id !== this.delete_member.id);
        },
        timKiem() { alert(`Tìm kiếm: ${this.tim_kiem.noi_dung_tim_kiem}`); }
    }
}
</script>
