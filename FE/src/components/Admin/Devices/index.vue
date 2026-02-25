<template>
  <div class="equipment-page">
    <!-- HEADER -->
    <div class="page-header">
      <div>
        <h2>Quản lý thiết bị</h2>
        <p>Quản lý thiết bị tập luyện và tình trạng</p>
      </div>
    </div>

    <!-- STAT CARDS -->
    <div class="stat-grid">
      <div class="stat-card">
        <p>Tổng thiết bị</p>
        <h3>6</h3>
      </div>
      <div class="stat-card success">
        <p>Hoạt động tốt</p>
        <h3>4</h3>
      </div>
      <div class="stat-card warning">
        <p>Cần bảo trì</p>
        <h3>1</h3>
      </div>
      <div class="stat-card danger">
        <p>Hỏng</p>
        <h3>1</h3>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
      <div class="filter-left">
        <select>
          <option>Tất cả loại</option>
        </select>
        <select>
          <option>Tất cả trạng thái</option>
        </select>
      </div>

      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus"></i> Thêm thiết bị
      </button>
    </div>

    <!-- TABLE -->
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Thiết bị</th>
            <th>Loại</th>
            <th>Nhà cung cấp</th>
            <th>Phòng</th>
            <th>Tình trạng</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in list_thiet_bi" :key="item.id">
            <td>
              <div class="device">
                <div class="icon">🏋️</div>
                <div>
                  <strong>{{ item.ten_thiet_bi }}</strong>
                  <span>
                    <br />
                    ID: {{ item.id }}</span
                  >
                </div>
              </div>
            </td>
            <td>{{ item.type?.ten_loai || 'N/A' }}</td>
            <td>{{ item.supplier?.ten_nha_cung_cap || 'N/A' }}</td>
            <td>{{ item.room?.ten_phong || 'N/A' }}</td>
            <td>
              <span :class="['status', statusToClass(item.tinh_trang)]">
                {{ statusToLabel(item.tinh_trang) }}
              </span>
            </td>
            <td class="actions">
              <i
                class="fa-solid fa-pen"
                @click="openEdit(item)"
                data-bs-toggle="modal"
                data-bs-target="#editModal"
              ></i>
              <i
                class="fa-solid fa-trash"
                @click="openDelete(item)"
                data-bs-toggle="modal"
                data-bs-target="#deleteModal"
              ></i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <div class="pagination-container mt-3" v-if="totalPages > 1">
      <div class="pagination-info">
        Trang {{ currentPage }} / {{ totalPages }} (Tổng {{ totalRecords }} thiết bị)
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

  </div>
  <div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-custom">
        <div class="modal-header gradient">
          <h5>Thêm thiết bị</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input v-model="create_thiet_bi.ten_thiet_bi" placeholder="Tên thiết bị" />
          <input v-model="create_thiet_bi.type_id" type="number" placeholder="ID Loại" />
          <input v-model="create_thiet_bi.supplier_id" type="number" placeholder="ID Nhà cung cấp" />
          <input v-model="create_thiet_bi.room_id" type="number" placeholder="ID Phòng" />

          <select v-model="create_thiet_bi.tinh_trang">
            <option :value="1">Tốt</option>
            <option :value="2">Cần bảo trì</option>
            <option :value="3">Hỏng</option>
          </select>
        </div>

        <div class="modal-footer">
          <button data-bs-dismiss="modal">Hủy</button>
          <button
            class="btn-primary"
            @click="addEquipment"
            data-bs-dismiss="modal"
          >
            Lưu
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-custom">
        <div class="modal-header gradient">
          <h5>Cập nhật thiết bị</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input v-model="edit_thiet_bi.ten_thiet_bi" />
          <input v-model="edit_thiet_bi.type_id" type="number" />
          <input v-model="edit_thiet_bi.supplier_id" type="number" />
          <input v-model="edit_thiet_bi.room_id" type="number" />

          <select v-model="edit_thiet_bi.tinh_trang">
            <option :value="1">Tốt</option>
            <option :value="2">Cần bảo trì</option>
            <option :value="3">Hỏng</option>
          </select>
        </div>

        <div class="modal-footer">
          <button data-bs-dismiss="modal">Hủy</button>
          <button
            class="btn-primary"
            @click="updateEquipment"
            data-bs-dismiss="modal"
          >
            Cập nhật
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-custom">
        <div class="modal-body text-center">
          <i class="fa-solid fa-trash delete-icon"></i>
          <h4>Xóa thiết bị?</h4>
          <p>{{ delete_thiet_bi?.ten_thiet_bi }} sẽ bị xóa vĩnh viễn</p>
        </div>

        <div class="modal-footer">
          <button data-bs-dismiss="modal">Hủy</button>
          <button
            class="btn-danger"
            @click="deleteEquipment"
            data-bs-dismiss="modal"
          >
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
      list_thiet_bi: [],
      create_thiet_bi: {
        ten_thiet_bi: "",
        type_id: 1,
        supplier_id: 1,
        room_id: 1,
        tinh_trang: 1,
      },
      edit_thiet_bi: {},
      delete_thiet_bi: null,
      currentPage: 1,
      totalPages: 1,
      totalRecords: 0,
    };
  },
  mounted() {
    this.getListThietBi();
  },
  methods: {
    getListThietBi(page = 1) {
        axios.get(`admin/thiet-bi/get-data?page=${page}`)
            .then((res) => {
                this.list_thiet_bi = res.data.data.data;
                this.currentPage = res.data.data.current_page;
                this.totalPages = res.data.data.last_page;
                this.totalRecords = res.data.data.total;
            });
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.getListThietBi(page);
      }
    },
    addEquipment() {
        axios.post('admin/thiet-bi/add-data', this.create_thiet_bi)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.create_thiet_bi = { ten_thiet_bi: "", type_id: 1, supplier_id: 1, room_id: 1, tinh_trang: 1 };
                    this.getListThietBi(this.currentPage);
                }
            });
    },
    openEdit(item) {
      this.edit_thiet_bi = { ...item };
    },
    updateEquipment() {
        axios.post('admin/thiet-bi/update', this.edit_thiet_bi)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListThietBi(this.currentPage);
                }
            });
    },
    openDelete(item) {
      this.delete_thiet_bi = item;
    },
    deleteEquipment() {
        axios.post('admin/thiet-bi/delete', this.delete_thiet_bi)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListThietBi(this.currentPage);
                }
            });
    },
    statusToClass(status) {
      if (status == 1) return "good";
      if (status == 2) return "warning";
      return "bad";
    },
    statusToLabel(status) {
      if (status == 1) return "Tốt";
      if (status == 2) return "Cần bảo trì";
      return "Hỏng";
    },
  },
};
</script>
