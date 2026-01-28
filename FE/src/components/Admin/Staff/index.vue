<template>
    <div class="staff-grid">
  <div
    class="staff-card"
    v-for="(staff, index) in list_members"
    :key="staff.id"
  >
    <!-- Header -->
    <div class="staff-card-header">
      <div class="avatar-circle">
        <i class="fa-solid fa-user"></i>
      </div>
      <h4 class="staff-name">{{ staff.ho_ten }}</h4>
      <p class="staff-role">{{ staff.goi_dang_ky }}</p>
    </div>

    <!-- Body -->
    <div class="staff-card-body">
      <div class="info-row">
        <i class="fa-solid fa-phone"></i>
        <span>{{ staff.sdt }}</span>
      </div>
      <div class="info-row">
        <i class="fa-solid fa-envelope"></i>
        <span>{{ staff.email }}</span>
      </div>
      <div class="info-row">
        <i class="fa-solid fa-clock"></i>
        <span>
          {{ staff.trang_thai === 1 ? 'Ca sáng' : 'Ca chiều' }}
        </span>
      </div>
    </div>

    <!-- Footer -->
    <div class="staff-card-footer">
      <button
        class="btn-card edit"
        @click="Object.assign(update_member, staff)"
        data-bs-toggle="modal"
        data-bs-target="#updateModal"
      >
        <i class="fa-solid fa-pen"></i> Sửa
      </button>

      <button
        class="btn-card delete"
        @click="Object.assign(delete_member, staff)"
        data-bs-toggle="modal"
        data-bs-target="#deleteModal"
      >
        <i class="fa-solid fa-trash"></i> Xóa
      </button>
    </div>
  </div>
</div>

</template>

<script>
import './index.css';

export default {
    data() {
        return {
            list_members: [
                { id: 101, ho_ten: 'Nguyễn Văn An', sdt: '0901234567', email: 'an.nguyen@gmail.com', avatar: '', goi_dang_ky: 'Premium 6 Tháng', trang_thai: 1, ngay_cap: '15/01/2026', ngay_het_han: '15/07/2026' },
                { id: 102, ho_ten: 'Trần Thị Bình', sdt: '0912345678', email: 'binh.tran@gmail.com', avatar: '', goi_dang_ky: 'Standard 1 Tháng', trang_thai: 1, ngay_cap: '01/04/2026', ngay_het_han: '01/05/2026' },
                { id: 103, ho_ten: 'Lê Hoàng Cường', sdt: '0988777666', email: 'cuong.gym@hotmail.com', avatar: '', goi_dang_ky: 'VIP 12 Tháng', trang_thai: 0, ngay_cap: '20/01/2025', ngay_het_han: '20/01/2026' },
            ],
            create_member: { 
                ho_ten: '', 
                sdt: '', 
                email: '', 
                goi_dang_ky: '', 
                ngay_cap: '', 
                ngay_het_han: '', 
                trang_thai: 1 
            },
            update_member: {},
            delete_member: {},
            tim_kiem: { 
                noi_dung_tim_kiem: "" 
            },
        }
    },
    methods: {
        getInitials(name) { return name ? name.split(' ').pop().charAt(0).toUpperCase() : ''; },
        getRandomColor(index) {
            const colors = ['bg-gradient-blue', 'bg-gradient-purple', 'bg-gradient-green', 'bg-gradient-orange'];
            return colors[index % colors.length];
        },
        getStatusClass(status) { return status === 1 ? 'status-active' : 'status-expired'; },

        ThemMoiThanhVien() {
            const newId = Math.floor(Math.random() * 1000) + 100;
            // Thêm logic xử lý ngày nếu cần (ví dụ format lại)
            const newMember = { ...this.create_member, id: newId, avatar: '' };
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