<template>
  <div class="service-page">

    <!-- HEADER -->
    <div class="page-header">
      <div>
        <h2><i class="fa-solid fa-gem me-2 text-primary"></i>Quản lý Dịch vụ</h2>
        <p>Thiết lập gói tập, dịch vụ huấn luyện viên và tiện ích đi kèm</p>
      </div>
      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addServiceModal">
        <i class="fa-solid fa-plus-circle"></i> Thêm dịch vụ
      </button>
    </div>

    <!-- STATS -->
    <div class="stat-grid">
      <div class="stat-card">
        <p>Tổng dịch vụ</p>
        <h3>{{ list_dich_vu.length }}</h3>
      </div>
      <div class="stat-card success">
        <p>Đang hoạt động</p>
        <h3>{{ activeCount }}</h3>
      </div>
      <div class="stat-card warning">
        <p>Ngưng bán</p>
        <h3>{{ inactiveCount }}</h3>
      </div>
      <div class="stat-card">
        <p>Gói bán chạy</p>
        <h3 class="text-primary">{{ hotService }}</h3>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
      <div class="d-flex align-items-center gap-3">
        <i class="fa-solid fa-filter text-muted"></i>
        <select v-model="filterType" class="form-select shadow-none">
            <option value="">Tất cả loại dịch vụ</option>
            <option>Gói tập</option>
            <option>PT</option>
            <option>Yoga</option>
            <option>Spa</option>
        </select>
      </div>
    </div>

    <div class="service-grid">
      <div class="service-card" v-for="item in filteredServices" :key="item.id">

        <div class="service-header">
          <div class="service-icon">
            <i :class="getServiceIcon(item.loai_dich_vu)"></i>
          </div>
          <span :class="['badge', item.status ? 'success' : 'danger']">
            <i class="fa-solid" :class="item.status ? 'fa-check-circle' : 'fa-times-circle'"></i>
            {{ item.status ? 'Đang bán' : 'Ngưng' }}
          </span>
        </div>

        <h4>{{ item.ten_dich_vu }}</h4>
        <p class="type text-uppercase">{{ item.loai_dich_vu }}</p>

        <div class="price">{{ formatCurrency(item.gia_tien) }}</div>

        <div class="meta">
          <span><i class="fa-solid fa-calendar-days text-primary"></i> {{ item.thoi_han }}</span>
          <span><i class="fa-solid fa-ticket text-primary"></i> {{ item.so_buoi }}</span>
        </div>

        <div class="card-actions">
          <button class="btn-edit"
            @click="openEdit(item)"
            data-bs-toggle="modal"
            data-bs-target="#editServiceModal">
            <i class="fa-solid fa-edit me-1"></i>Sửa
          </button>
          <button class="btn-delete"
            @click="openDelete(item)"
            data-bs-toggle="modal"
            data-bs-target="#deleteServiceModal">
            <i class="fa-solid fa-trash me-1"></i>Xóa
          </button>
        </div>

      </div>
    </div>

    <!-- PAGINATION -->
    <div class="pagination-container mt-4" v-if="totalPages > 1">
        <div class="pagination-info">
          Trang <strong>{{ currentPage }}</strong> / {{ totalPages }} (Tổng {{ totalRecords }} dịch vụ)
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
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
          <div class="modal-header-custom gradient">
            <h5 class="modal-title-custom"><i class="fa-solid fa-plus-circle me-2"></i>Thêm dịch vụ mới</h5>
            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body modal-body-custom">
            <div class="input-group-custom">
                <label class="input-label">Tên dịch vụ</label>
                <input v-model="create_dich_vu.ten_dich_vu" class="input-field" placeholder="Ví dụ: Gói VIP 6 tháng" />
            </div>

            <div class="input-group-custom">
                <label class="input-label">Loại dịch vụ</label>
                <select v-model="create_dich_vu.loai_dich_vu" class="input-field">
                  <option>Gói tập</option>
                  <option>PT</option>
                  <option>Yoga</option>
                  <option>Spa</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="input-label">Giá (VNĐ)</label>
                        <input type="number" v-model="create_dich_vu.gia_tien" class="input-field" placeholder="Ví dụ: 500" />
                        <small class="text-muted">Đơn vị: k (1k = 1.000đ)</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="input-label">Trạng thái</label>
                        <select v-model="create_dich_vu.status" class="input-field">
                            <option :value="1">Đang bán</option>
                            <option :value="0">Ngưng bán</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="input-label">Thời hạn</label>
                        <input v-model="create_dich_vu.thoi_han" class="input-field" placeholder="Ví dụ: 30 ngày" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="input-label">Số buổi</label>
                        <input v-model="create_dich_vu.so_buoi" class="input-field" placeholder="Ví dụ: Không giới hạn" />
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer-custom">
             <button class="btn-action secondary" data-bs-dismiss="modal">Hủy</button>
             <button class="btn-action primary w-100" @click="addService" data-bs-dismiss="modal">
               <i class="fa-solid fa-save me-2"></i>Lưu dịch vụ
             </button>
          </div>
        </div>
      </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
          <div class="modal-header-custom gradient">
            <h5 class="modal-title-custom"><i class="fa-solid fa-pen-to-square me-2"></i>Cập nhật dịch vụ</h5>
            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body modal-body-custom">
            <div class="input-group-custom">
                <label class="input-label">Tên dịch vụ</label>
                <input v-model="edit_dich_vu.ten_dich_vu" class="input-field" />
            </div>

            <div class="input-group-custom">
                <label class="input-label">Loại dịch vụ</label>
                <select v-model="edit_dich_vu.loai_dich_vu" class="input-field">
                  <option>Gói tập</option>
                  <option>PT</option>
                  <option>Yoga</option>
                  <option>Spa</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="input-label">Giá (VNĐ)</label>
                        <input type="number" v-model="edit_dich_vu.gia_tien" class="input-field" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="input-label">Trạng thái</label>
                        <select v-model="edit_dich_vu.status" class="input-field">
                            <option :value="1">Đang bán</option>
                            <option :value="0">Ngưng bán</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="input-label">Thời hạn</label>
                        <input v-model="edit_dich_vu.thoi_han" class="input-field" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group-custom">
                        <label class="input-label">Số buổi</label>
                        <input v-model="edit_dich_vu.so_buoi" class="input-field" />
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer-custom">
            <button class="btn-action secondary" data-bs-dismiss="modal">Hủy</button>
            <button class="btn-action primary w-100" @click="updateService" data-bs-dismiss="modal">
              <i class="fa-solid fa-check-circle me-2"></i>Cập nhật
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modern-modal">
          <div class="modal-body modal-body-custom text-center py-5">
            <div class="delete-avatar-container mb-4">
                <i class="fa-solid fa-trash-can text-danger fs-1"></i>
            </div>
            <h4 class="fw-bold">Xác nhận xóa?</h4>
            <p class="text-muted">Hành động này không thể hoàn tác. Dịch vụ <br><strong>{{ del_dich_vu?.ten_dich_vu }}</strong> sẽ bị xóa vĩnh viễn.</p>
          </div>
          <div class="modal-footer-custom justify-content-center">
            <button class="btn-action secondary px-4" data-bs-dismiss="modal">Hủy</button>
            <button class="btn-action danger px-4" @click="removeService" data-bs-dismiss="modal">
              Xác nhận xóa
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import "./index.css";
import axios from '@/axios';

export default {
  name: "ServiceManager",
  data() {
    return {
      list_dich_vu: [],
      create_dich_vu: {
        ten_dich_vu: "",
        loai_dich_vu: "Gói tập",
        gia_tien: null,
        thoi_han: "",
        so_buoi: "",
        status: 1
      },
      edit_dich_vu: {},
      del_dich_vu: null,
      filterType: "",
      currentPage: 1,
      totalPages: 1,
      totalRecords: 0,
    };
  },
  computed: {
    filteredServices() {
      return this.filterType
        ? this.list_dich_vu.filter(s => s.loai_dich_vu === this.filterType)
        : this.list_dich_vu;
    },
    activeCount() {
      return this.list_dich_vu.filter(s => s.status).length;
    },
    inactiveCount() {
      return this.list_dich_vu.filter(s => !s.status).length;
    },
    hotService() {
      return "PT cá nhân";
    }
  },
  mounted() {
    this.getListDichVu();
  },
  methods: {
    getListDichVu(page = 1) {
        axios.get(`admin/dich-vu/get-data?page=${page}`)
            .then((res) => {
                this.list_dich_vu = res.data.data.data;
                this.currentPage = res.data.data.current_page;
                this.totalPages = res.data.data.last_page;
                this.totalRecords = res.data.data.total;
            });
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.getListDichVu(page);
      }
    },
    addService() {
        axios.post('admin/dich-vu/add-data', this.create_dich_vu)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.create_dich_vu = { ten_dich_vu: "", loai_dich_vu: "Gói tập", gia_tien: null, thoi_han: "", so_buoi: "", status: 1 };
                    this.getListDichVu(this.currentPage);
                }
            });
    },
    openEdit(item) {
      this.edit_dich_vu = { ...item };
    },
    updateService() {
        axios.post('admin/dich-vu/update', this.edit_dich_vu)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListDichVu(this.currentPage);
                }
            });
    },
    openDelete(item) {
      this.del_dich_vu = item;
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value * 1000);
    },
    removeService() {
        axios.post('admin/dich-vu/delete', this.del_dich_vu)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListDichVu(this.currentPage);
                }
            });
    },
    getServiceIcon(type) {
      const map = {
        'Gói tập': 'fa-solid fa-dumbbell',
        'PT': 'fa-solid fa-user-ninja',
        'Yoga': 'fa-solid fa-spa',
        'Spa': 'fa-solid fa-heart-pulse'
      };
      return map[type] || 'fa-solid fa-gem';
    }
  }
};
</script>

