<template>
  <div class="product-page">

    <!-- HEADER -->
    <div class="page-header">
      <div>
        <h2>Quản lý sản phẩm</h2>
        <p>Quản lý sản phẩm và tồn kho</p>
      </div>
    </div>

    <!-- STAT CARDS -->
    <div class="stat-grid">
      <div class="stat-card">
        <p>Tổng sản phẩm</p>
        <h3>{{ totalRecords }}</h3>
      </div>
      <div class="stat-card">
        <p>Tổng tồn kho</p>
        <h3>{{ totalStock }}</h3>
      </div>
      <div class="stat-card">
        <p>Giá trị kho</p>
        <h3>{{ totalValue }}tr</h3>
      </div>
      <div class="stat-card danger">
        <p>Sắp hết hàng</p>
        <h3>{{ lowStockCount }}</h3>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
      <select v-model="filterCategory">
        <option value="">Tất cả danh mục</option>
        <option>Thực phẩm bổ sung</option>
        <option>Phụ kiện</option>
        <option>Đồ uống</option>
      </select>

      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="fa-solid fa-plus"></i> Thêm sản phẩm
      </button>
    </div>

    <div class="product-grid">
      <div class="product-card" v-for="item in filteredProducts" :key="item.id">

        <div class="product-header">
          <div class="product-icon">📦</div>
          <span :class="['badge', item.so_luong < 20 ? 'danger' : 'success']">
            {{ item.so_luong < 20 ? 'Sắp hết' : 'Còn hàng' }} </span>
        </div>

        <h4>{{ item.ten_san_pham }}</h4>
        <p class="category">{{ item.danh_muc }}</p>

        <div class="price">{{ formatCurrency(item.gia_ban) }} <span>/ sản phẩm</span></div>

        <div class="stock">
          <span>Tồn kho</span>
          <span>{{ item.so_luong }} sản phẩm</span>
        </div>

        <div class="progress">
          <div class="progress-bar" :style="{ width: stockPercent(item) + '%' }"
            :class="item.so_luong < 20 ? 'red' : 'green'"></div>
        </div>

        <div class="card-actions">
          <button class="btn-edit" @click="openEdit(item)" data-bs-toggle="modal" data-bs-target="#editProductModal">
            ✏️ Sửa
          </button>
          <button class="btn-delete" @click="openDelete(item)" data-bs-toggle="modal"
            data-bs-target="#deleteProductModal">
            🗑️ Xóa
          </button>
        </div>
      </div>
    </div>

    <!-- PAGINATION -->
    <div class="pagination-container mt-4" v-if="totalPages > 1">
        <div class="pagination-info">
          Trang <strong>{{ currentPage }}</strong> / {{ totalPages }} (Tổng {{ totalRecords }} sản phẩm)
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

    <!-- MODALS -->
    <!-- ADD -->
    <div class="modal fade" id="addProductModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">
          <div class="modal-header gradient">
            <h5>Thêm sản phẩm</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input v-model="create_san_pham.ten_san_pham" placeholder="Tên sản phẩm" />
            <input v-model="create_san_pham.danh_muc" placeholder="Danh mục" />
            <input type="number" v-model="create_san_pham.gia_ban" placeholder="Giá (k)" />
            <input type="number" v-model="create_san_pham.so_luong" placeholder="Tồn kho" />
          </div>
          <div class="modal-footer">
            <button data-bs-dismiss="modal">Hủy</button>
            <button class="btn-primary" @click="addProduct" data-bs-dismiss="modal">Lưu</button>
          </div>
        </div>
      </div>
    </div>

    <!-- EDIT -->
    <div class="modal fade" id="editProductModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">
          <div class="modal-header gradient">
            <h5>Sửa sản phẩm</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input v-model="edit_san_pham.ten_san_pham" />
            <input v-model="edit_san_pham.danh_muc" />
            <input type="number" v-model="edit_san_pham.gia_ban" />
            <input type="number" v-model="edit_san_pham.so_luong" />
          </div>
          <div class="modal-footer">
            <button data-bs-dismiss="modal">Hủy</button>
            <button class="btn-primary" @click="updateProduct" data-bs-dismiss="modal">Cập nhật</button>
          </div>
        </div>
      </div>
    </div>

    <!-- DELETE -->
    <div class="modal fade" id="deleteProductModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">
          <div class="modal-body text-center">
            <i class="fa-solid fa-trash delete-icon"></i>
            <h4>Xóa sản phẩm?</h4>
            <p>{{ delete_san_pham?.ten_san_pham }} sẽ bị xóa</p>
          </div>
          <div class="modal-footer">
            <button data-bs-dismiss="modal">Hủy</button>
            <button class="btn-danger" @click="removeProduct" data-bs-dismiss="modal">Xóa</button>
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
  data() {
    return {
      list_san_pham: [],
      create_san_pham: { ten_san_pham: "", danh_muc: "Thực phẩm bổ sung", gia_ban: 0, so_luong: 0, status: 1 },
      edit_san_pham: {},
      delete_san_pham: null,
      filterCategory: "",
      currentPage: 1,
      totalPages: 1,
      totalRecords: 0
    };
  },
  computed: {
    filteredProducts() {
      return this.filterCategory
        ? this.list_san_pham.filter(p => p.danh_muc === this.filterCategory)
        : this.list_san_pham;
    },
    totalStock() {
      return this.list_san_pham.reduce((s, p) => s + Number(p.so_luong), 0);
    },
    totalValue() {
      const total = this.list_san_pham.reduce((s, p) => s + Number(p.gia_ban) * Number(p.so_luong), 0);
      return new Intl.NumberFormat('vi-VN').format(total);
    },
    lowStockCount() {
      return this.list_san_pham.filter(p => p.so_luong < 20).length;
    }
  },
  mounted() {
    this.getListSanPham();
  },
  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value * 1000);
    },
    stockPercent(item) {
      return Math.min((item.so_luong / 100) * 100, 100);
    },
    getListSanPham(page = 1) {
        axios.get(`admin/san-pham/get-data?page=${page}`)
            .then((res) => {
                this.list_san_pham = res.data.data.data;
                this.currentPage = res.data.data.current_page;
                this.totalPages = res.data.data.last_page;
                this.totalRecords = res.data.data.total;
            });
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.getListSanPham(page);
      }
    },
    addProduct() {
        axios.post('admin/san-pham/add-data', this.create_san_pham)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.create_san_pham = { ten_san_pham: "", danh_muc: "Thực phẩm bổ sung", gia_ban: 0, so_luong: 0, status: 1 };
                    this.getListSanPham(this.currentPage);
                }
            });
    },
    openEdit(item) {
      this.edit_san_pham = { ...item };
    },
    updateProduct() {
        axios.post('admin/san-pham/update', this.edit_san_pham)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListSanPham(this.currentPage);
                }
            });
    },
    openDelete(item) {
      this.delete_san_pham = item;
    },
    removeProduct() {
        axios.post('admin/san-pham/delete', this.delete_san_pham)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListSanPham(this.currentPage);
                }
            });
    },
  }
};
</script>
