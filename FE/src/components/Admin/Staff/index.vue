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
        <p class="staff-role">{{ staff.chuc_vu }}</p>
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
            {{ staff.trang_thai === 1 ? "Đang làm" : "Nghỉ việc" }}
          </span>
        </div>
      </div>

      <!-- Footer -->
      <div class="staff-card-footer">
        <button
          class="btn-card edit"
          @click="Object.assign(update_nhan_vien, staff)"
          data-bs-toggle="modal"
          data-bs-target="#updateModal"
        >
          <i class="fa-solid fa-pen"></i> Sửa
        </button>

        <button
          class="btn-card delete"
          @click="Object.assign(delete_nhan_vien, staff)"
          data-bs-toggle="modal"
          data-bs-target="#deleteModal"
        >
          <i class="fa-solid fa-trash"></i> Xóa
        </button>
      </div>
    </div>
  </div>

  <!-- PAGINATION -->
  <div class="pagination-container mt-4" v-if="totalPages > 1">
      <div class="pagination-info">
        Trang <strong>{{ currentPage }}</strong> / {{ totalPages }} (Tổng {{ totalRecords }} nhân viên)
      </div>
      <div class="pagination-group">
        <button class="page-btn" :disabled="currentPage === 1" @click="changePage(currentPage - 1)">
          <i class="fa-solid fa-chevron-left"></i>
        </button>

        <button 
          v-for="page in totalPages" 
          :key="page" 
          class="page-btn" 
          :class="{ active: currentPage === page }"
          @click="changePage(page)"
        >
          {{ page }}
        </button>

        <button class="page-btn" :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
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
        <input v-model="create_nhan_vien.ho_ten" placeholder="Họ tên" />
        <input v-model="create_nhan_vien.sdt" placeholder="Số điện thoại" />
        <input v-model="create_nhan_vien.email" placeholder="Email" />
        <select v-model="create_nhan_vien.trang_thai">
          <option :value="1">Đang làm</option>
          <option :value="0">Nghỉ việc</option>
        </select>
      </div>

      <div class="modal-footer">
        <button data-bs-dismiss="modal">Hủy</button>
        <button class="btn-primary" @click="ThemMoiNhanVien" data-bs-dismiss="modal">
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
        <input v-model="update_nhan_vien.ho_ten" />
        <input v-model="update_nhan_vien.sdt" />
        <input v-model="update_nhan_vien.email" />
        <select v-model="update_nhan_vien.trang_thai">
          <option :value="1">Đang làm</option>
          <option :value="0">Nghỉ việc</option>
        </select>
      </div>

      <div class="modal-footer">
        <button data-bs-dismiss="modal">Hủy</button>
        <button class="btn-primary" @click="CapNhatNhanVien" data-bs-dismiss="modal">
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
        <p>{{ delete_nhan_vien.ho_ten }} sẽ bị xóa vĩnh viễn</p>
      </div>

      <div class="modal-footer center">
        <button data-bs-dismiss="modal">Hủy</button>
        <button class="btn-danger" @click="xoaNhanVien" data-bs-dismiss="modal">
          Xóa
        </button>
      </div>
    </div>
  </div>
</div>

</template>

<script>
import "./index.css";
import axios from '@/axios';

export default {
  data() {
    return {
      list_nhan_vien: [],
      create_nhan_vien: {
        ho_ten: "",
        sdt: "",
        email: "",
        chuc_vu: "Huấn luyện viên",
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
    };
  },
  computed: {
    filteredStaff() {
      const key = this.tim_kiem.noi_dung_tim_kiem.toLowerCase();
      return this.list_nhan_vien.filter(
        (s) =>
          (s.ho_ten && s.ho_ten.toLowerCase().includes(key)) ||
          (s.sdt && s.sdt.includes(key)) ||
          (s.email && s.email.toLowerCase().includes(key)),
      );
    },
  },
  mounted() {
    this.getListNhanVien();
  },
  methods: {
    getListNhanVien(page = 1) {
        axios.get(`admin/nhan-vien/get-data?page=${page}`)
            .then((res) => {
                this.list_nhan_vien = res.data.data.data;
                this.currentPage = res.data.data.current_page;
                this.totalPages = res.data.data.last_page;
                this.totalRecords = res.data.data.total;
            });
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.getListNhanVien(page);
      }
    },
    ThemMoiNhanVien() {
        axios.post('admin/nhan-vien/add-data', this.create_nhan_vien)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.create_nhan_vien = { ho_ten: "", sdt: "", email: "", chuc_vu: "Huấn luyện viên", trang_thai: 1 };
                    this.getListNhanVien(this.currentPage);
                }
            });
    },
    CapNhatNhanVien() {
        axios.post('admin/nhan-vien/update', this.update_nhan_vien)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListNhanVien(this.currentPage);
                }
            });
    },
    xoaNhanVien() {
        axios.post('admin/nhan-vien/delete', this.delete_nhan_vien)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListNhanVien(this.currentPage);
                }
            });
    }
  },
};
</script>
