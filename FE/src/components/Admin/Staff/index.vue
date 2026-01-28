<template>
  <!-- HEADER -->
<div class="staff-header">
  <div class="staff-header-left">
    <h2>Quản lý nhân viên</h2>
    <p>Quản lý thông tin nhân viên và lịch làm việc</p>
  </div>

  <div class="staff-header-right">
    <!-- SEARCH -->
    <div class="staff-search">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input
        type="text"
        placeholder="Tìm kiếm nhân viên..."
        v-model="tim_kiem.noi_dung_tim_kiem"
      />
    </div>

    <!-- ADD BUTTON -->
    <button
      class="staff-add-btn"
      data-bs-toggle="modal"
      data-bs-target="#addModal"
    >
      <i class="fa-solid fa-plus"></i>
      Thêm nhân viên
    </button>
  </div>
</div>
    <!-- STAFF GRID -->

  <div class="staff-grid">
    <div
      class="staff-card"
      v-for="(staff, index) in filteredStaff"
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
            {{ staff.trang_thai === 1 ? "Ca sáng" : "Ca chiều" }}
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
  <!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-custom">
      <div class="modal-header gradient">
        <h5>Thêm nhân viên</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input v-model="create_member.ho_ten" placeholder="Họ tên" />
        <input v-model="create_member.sdt" placeholder="Số điện thoại" />
        <input v-model="create_member.email" placeholder="Email" />
        <select v-model="create_member.trang_thai">
          <option :value="1">Ca sáng</option>
          <option :value="0">Ca chiều</option>
        </select>
      </div>

      <div class="modal-footer">
        <button data-bs-dismiss="modal">Hủy</button>
        <button class="btn-primary" @click="ThemMoiThanhVien" data-bs-dismiss="modal">
          Lưu
        </button>
      </div>
    </div>
  </div>
</div>
<!-- UPDATE MODAL -->
<div class="modal fade" id="updateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-custom">
      <div class="modal-header gradient">
        <h5>Cập nhật nhân viên</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input v-model="update_member.ho_ten" />
        <input v-model="update_member.sdt" />
        <input v-model="update_member.email" />
        <select v-model="update_member.trang_thai">
          <option :value="1">Ca sáng</option>
          <option :value="0">Ca chiều</option>
        </select>
      </div>

      <div class="modal-footer">
        <button data-bs-dismiss="modal">Hủy</button>
        <button class="btn-primary" data-bs-dismiss="modal">
          Cập nhật
        </button>
      </div>
    </div>
  </div>
</div>
<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-custom text-center">
      <div class="modal-body">
        <i class="fa-solid fa-trash delete-icon"></i>
        <h4>Xóa nhân viên?</h4>
        <p>{{ delete_member.ho_ten }} sẽ bị xóa vĩnh viễn</p>
      </div>

      <div class="modal-footer center">
        <button data-bs-dismiss="modal">Hủy</button>
        <button class="btn-danger" @click="xoaThanhVien" data-bs-dismiss="modal">
          Xóa
        </button>
      </div>
    </div>
  </div>
</div>

</template>

<script>
import "./index.css";

export default {
  data() {
    return {
      list_members: [
        {
          id: 101,
          ho_ten: "Nguyễn Văn An",
          sdt: "0901234567",
          email: "an.nguyen@gmail.com",
          avatar: "",
          goi_dang_ky: "Premium 6 Tháng",
          trang_thai: 1,
          ngay_cap: "15/01/2026",
          ngay_het_han: "15/07/2026",
        },
        {
          id: 102,
          ho_ten: "Trần Thị Bình",
          sdt: "0912345678",
          email: "binh.tran@gmail.com",
          avatar: "",
          goi_dang_ky: "Standard 1 Tháng",
          trang_thai: 1,
          ngay_cap: "01/04/2026",
          ngay_het_han: "01/05/2026",
        },
        {
          id: 103,
          ho_ten: "Lê Hoàng Cường",
          sdt: "0988777666",
          email: "cuong.gym@hotmail.com",
          avatar: "",
          goi_dang_ky: "VIP 12 Tháng",
          trang_thai: 0,
          ngay_cap: "20/01/2025",
          ngay_het_han: "20/01/2026",
        },
      ],
      create_member: {
        ho_ten: "",
        sdt: "",
        email: "",
        goi_dang_ky: "",
        ngay_cap: "",
        ngay_het_han: "",
        trang_thai: 1,
      },
      update_member: {},
      delete_member: {},
      tim_kiem: {
        noi_dung_tim_kiem: "",
      },
    };
  },
  computed: {
    filteredStaff() {
      const key = this.tim_kiem.noi_dung_tim_kiem.toLowerCase();
      return this.list_members.filter(
        (s) =>
          s.ho_ten.toLowerCase().includes(key) ||
          s.sdt.includes(key) ||
          s.email.toLowerCase().includes(key),
      );
    },
  },
  methods: {
    getInitials(name) {
      return name ? name.split(" ").pop().charAt(0).toUpperCase() : "";
    },
    getRandomColor(index) {
      const colors = [
        "bg-gradient-blue",
        "bg-gradient-purple",
        "bg-gradient-green",
        "bg-gradient-orange",
      ];
      return colors[index % colors.length];
    },
    getStatusClass(status) {
      return status === 1 ? "status-active" : "status-expired";
    },

    ThemMoiThanhVien() {
      const newId = Math.floor(Math.random() * 1000) + 100;
      // Thêm logic xử lý ngày nếu cần (ví dụ format lại)
      const newMember = { ...this.create_member, id: newId, avatar: "" };
      this.list_members.unshift(newMember);

      // Reset form
      this.create_member = {
        ho_ten: "",
        sdt: "",
        email: "",
        goi_dang_ky: "",
        ngay_cap: "",
        ngay_het_han: "",
        trang_thai: 1,
      };
    },
    CapNhatThanhVien() {
      alert(`Đã cập nhật: ${this.update_member.ho_ten}`);
    },
    xoaThanhVien() {
      this.list_members = this.list_members.filter(
        (m) => m.id !== this.delete_member.id,
      );
    },
    timKiem() {
      alert(`Tìm kiếm: ${this.tim_kiem.noi_dung_tim_kiem}`);
    },
  },
};
</script>
