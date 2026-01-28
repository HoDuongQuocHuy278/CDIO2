<template>
  <div class="service-page">

    <!-- HEADER -->
    <div class="page-header">
      <div>
        <h2>Quản lý dịch vụ</h2>
        <p>Quản lý các dịch vụ tập luyện và chăm sóc khách hàng</p>
      </div>
    </div>

    <!-- STATS -->
    <div class="stat-grid">
      <div class="stat-card">
        <p>Tổng dịch vụ</p>
        <h3>{{ services.length }}</h3>
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
        <p>Bán chạy</p>
        <h3>{{ hotService }}</h3>
      </div>
    </div>

    <!-- FILTER -->
    <div class="filter-bar">
      <select v-model="filterType">
        <option value="">Tất cả loại dịch vụ</option>
        <option>Gói tập</option>
        <option>PT</option>
        <option>Yoga</option>
        <option>Spa</option>
      </select>

      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addServiceModal">
        + Thêm dịch vụ
      </button>
    </div>

    <!-- SERVICE GRID -->
    <div class="service-grid">
      <div class="service-card" v-for="item in filteredServices" :key="item.id">

        <div class="service-header">
          <div class="service-icon">💎</div>
          <span :class="['badge', item.active ? 'success' : 'danger']">
            {{ item.active ? 'Đang bán' : 'Ngưng' }}
          </span>
        </div>

        <h4>{{ item.name }}</h4>
        <p class="type">{{ item.type }}</p>

        <div class="price">{{ item.price }}k</div>

        <div class="meta">
          <span>⏱ {{ item.duration }}</span>
          <span>🎟 {{ item.sessions }}</span>
        </div>

        <div class="card-actions">
          <button class="btn-edit"
            @click="openEdit(item)"
            data-bs-toggle="modal"
            data-bs-target="#editServiceModal">
            Sửa
          </button>
          <button class="btn-delete"
            @click="openDelete(item)"
            data-bs-toggle="modal"
            data-bs-target="#deleteServiceModal">
            Xóa
          </button>
        </div>

      </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addServiceModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">
          <div class="modal-header gradient">
            <h5>Thêm dịch vụ</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input v-model="newService.name" placeholder="Tên dịch vụ" />
            <select v-model="newService.type">
              <option>Gói tập</option>
              <option>PT</option>
              <option>Yoga</option>
              <option>Spa</option>
            </select>
            <input type="number" v-model="newService.price" placeholder="Giá (k)" />
            <input v-model="newService.duration" placeholder="Thời hạn" />
            <input v-model="newService.sessions" placeholder="Số buổi" />
          </div>
          <div class="modal-footer">
            <button data-bs-dismiss="modal">Hủy</button>
            <button class="btn-primary" @click="addService" data-bs-dismiss="modal">
              Lưu
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editServiceModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">
          <div class="modal-header gradient">
            <h5>Cập nhật dịch vụ</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input v-model="editService.name" />
            <select v-model="editService.type">
              <option>Gói tập</option>
              <option>PT</option>
              <option>Yoga</option>
              <option>Spa</option>
            </select>
            <input type="number" v-model="editService.price" />
            <input v-model="editService.duration" />
            <input v-model="editService.sessions" />
          </div>
          <div class="modal-footer">
            <button data-bs-dismiss="modal">Hủy</button>
            <button class="btn-primary" @click="updateService" data-bs-dismiss="modal">
              Cập nhật
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteServiceModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">
          <div class="modal-body text-center">
            <div class="delete-icon">🗑️</div>
            <h4>Xóa dịch vụ?</h4>
            <p>{{ deleteService?.name }} sẽ bị xóa</p>
          </div>
          <div class="modal-footer">
            <button data-bs-dismiss="modal">Hủy</button>
            <button class="btn-danger" @click="removeService" data-bs-dismiss="modal">
              Xóa
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import "./index.css";
export default {
  name: "ServiceManager",
  data() {
    return {
      services: [
        { id: 1, name: "Gói tập Gym 1 tháng", type: "Gói tập", price: 500, duration: "30 ngày", sessions: "Không giới hạn", active: true },
        { id: 2, name: "PT cá nhân", type: "PT", price: 3000, duration: "1 tháng", sessions: "12 buổi", active: true },
        { id: 3, name: "Yoga cơ bản", type: "Yoga", price: 800, duration: "1 tháng", sessions: "8 buổi", active: false }
      ],
      filterType: "",
      newService: { name: "", type: "Gói tập", price: null, duration: "", sessions: "" },
      editService: {},
      deleteService: null
    };
  },
  computed: {
    filteredServices() {
      return this.filterType
        ? this.services.filter(s => s.type === this.filterType)
        : this.services;
    },
    activeCount() {
      return this.services.filter(s => s.active).length;
    },
    inactiveCount() {
      return this.services.filter(s => !s.active).length;
    },
    hotService() {
      return "PT cá nhân";
    }
  },
  methods: {
    addService() {
      this.services.push({ ...this.newService, id: Date.now(), active: true });
      this.newService = { name: "", type: "Gói tập", price: null, duration: "", sessions: "" };
    },
    openEdit(item) {
      this.editService = { ...item };
    },
    updateService() {
      const i = this.services.findIndex(s => s.id === this.editService.id);
      this.services.splice(i, 1, this.editService);
    },
    openDelete(item) {
      this.deleteService = item;
    },
    removeService() {
      this.services = this.services.filter(s => s.id !== this.deleteService.id);
    }
  }
};
</script>

