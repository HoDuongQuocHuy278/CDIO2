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
        <h3>{{ products.length }}</h3>
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

    <!-- PRODUCT GRID -->
    <div class="product-grid">
      <div class="product-card" v-for="item in filteredProducts" :key="item.id">

        <div class="product-header">
          <div class="product-icon">📦</div>
          <span :class="['badge', item.stock < 20 ? 'danger' : 'success']">
            {{ item.stock < 20 ? 'Sắp hết' : 'Còn hàng' }}
          </span>
        </div>

        <h4>{{ item.name }}</h4>
        <p class="category">{{ item.category }}</p>

        <div class="price">{{ item.price }}k <span>/ sản phẩm</span></div>

        <div class="stock">
          <span>Tồn kho</span>
          <span>{{ item.stock }} sản phẩm</span>
        </div>

        <div class="progress">
          <div
            class="progress-bar"
            :style="{ width: stockPercent(item) + '%' }"
            :class="item.stock < 20 ? 'red' : 'green'"
          ></div>
        </div>

        <div class="card-actions">
          <button class="btn-edit" @click="openEdit(item)" data-bs-toggle="modal" data-bs-target="#editProductModal">
            ✏️ Sửa
          </button>
          <button class="btn-delete" @click="openDelete(item)" data-bs-toggle="modal" data-bs-target="#deleteProductModal">
            🗑️ Xóa
          </button>
        </div>
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
            <input v-model="newProduct.name" placeholder="Tên sản phẩm" />
            <input v-model="newProduct.category" placeholder="Danh mục" />
            <input type="number" v-model="newProduct.price" placeholder="Giá (k)" />
            <input type="number" v-model="newProduct.stock" placeholder="Tồn kho" />
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
            <input v-model="editProduct.name" />
            <input v-model="editProduct.category" />
            <input type="number" v-model="editProduct.price" />
            <input type="number" v-model="editProduct.stock" />
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
            <p>{{ deleteProduct?.name }} sẽ bị xóa</p>
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
export default {
  data() {
    return {
      products: [
        { id: 1, name: "Whey Protein 2kg", category: "Thực phẩm bổ sung", price: 1200, stock: 45 },
        { id: 2, name: "BCAA 300g", category: "Thực phẩm bổ sung", price: 450, stock: 12 },
        { id: 3, name: "Găng tay tập gym", category: "Phụ kiện", price: 150, stock: 78 },
      ],
      newProduct: { name: "", category: "", price: 0, stock: 0 },
      editProduct: {},
      deleteProduct: null,
      filterCategory: ""
    };
  },

  computed: {
    filteredProducts() {
      return this.filterCategory
        ? this.products.filter(p => p.category === this.filterCategory)
        : this.products;
    },
    totalStock() {
      return this.products.reduce((s, p) => s + p.stock, 0);
    },
    totalValue() {
      return this.products.reduce((s, p) => s + p.price * p.stock, 0) / 1000;
    },
    lowStockCount() {
      return this.products.filter(p => p.stock < 20).length;
    }
  },

  methods: {
    stockPercent(item) {
      return Math.min((item.stock / 100) * 100, 100);
    },
    addProduct() {
      this.products.push({ ...this.newProduct, id: Date.now() });
      this.newProduct = { name: "", category: "", price: 0, stock: 0 };
    },
    openEdit(item) {
      this.editProduct = { ...item };
    },
    updateProduct() {
      const i = this.products.findIndex(p => p.id === this.editProduct.id);
      this.products.splice(i, 1, this.editProduct);
    },
    openDelete(item) {
      this.deleteProduct = item;
    },
    removeProduct() {
      this.products = this.products.filter(p => p.id !== this.deleteProduct.id);
    }
  }
};
</script>
