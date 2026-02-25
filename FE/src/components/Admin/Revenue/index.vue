<template>
  <div class="revenue-page">

    <!-- HEADER -->
    <div class="page-header">
      <div>
        <h2>Quản lý doanh thu</h2>
        <p>Theo dõi doanh thu theo thời gian và nguồn thu</p>
      </div>
    </div>

    <!-- STAT CARDS -->
    <div class="stat-grid">
      <div class="stat-card">
        <p>Tổng doanh thu</p>
        <h3>{{ formatMoney(totalRevenue) }}</h3>
      </div>

      <div class="stat-card success">
        <p>Hôm nay</p>
        <h3>{{ formatMoney(todayRevenue) }}</h3>
      </div>

      <div class="stat-card">
        <p>Tháng này</p>
        <h3>{{ formatMoney(monthRevenue) }}</h3>
      </div>

      <div class="stat-card warning">
        <p>Giao dịch</p>
        <h3>{{ transactionCount }}</h3>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
      <div class="filter-left">
        <input type="date" v-model="filter.fromDate" />
        <input type="date" v-model="filter.toDate" />

        <select v-model="filter.source">
          <option value="">Tất cả nguồn thu</option>
          <option>Gói tập</option>
          <option>Dịch vụ</option>
          <option>Sản phẩm</option>
        </select>
      </div>

      <button class="btn-export" @click="exportReport">
        ⬇ Xuất báo cáo
      </button>
    </div>

    <!-- REVENUE SOURCE GRID -->
    <div class="revenue-grid">
      <div class="revenue-card">
        <h4>💎 Gói tập</h4>
        <p class="money">{{ formatMoney(revenueBySource["Gói tập"]) }}</p>
        <span>
          {{
            totalRevenue
              ? Math.round(
                  (revenueBySource["Gói tập"] / totalRevenue) * 100
                )
              : 0
          }}%
        </span>
      </div>

      <div class="revenue-card">
        <h4>🏋️ Dịch vụ</h4>
        <p class="money">{{ formatMoney(revenueBySource["Dịch vụ"]) }}</p>
        <span>
          {{
            totalRevenue
              ? Math.round(
                  (revenueBySource["Dịch vụ"] / totalRevenue) * 100
                )
              : 0
          }}%
        </span>
      </div>

      <div class="revenue-card">
        <h4>🛒 Sản phẩm</h4>
        <p class="money">{{ formatMoney(revenueBySource["Sản phẩm"]) }}</p>
        <span>
          {{
            totalRevenue
              ? Math.round(
                  (revenueBySource["Sản phẩm"] / totalRevenue) * 100
                )
              : 0
          }}%
        </span>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Thời gian</th>
            <th>Khách hàng</th>
            <th>Nguồn thu</th>
            <th>Dịch vụ / Sản phẩm</th>
            <th>Số tiền</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in filteredRevenues" :key="item.id">
            <td>{{ item.date }}</td>
            <td>{{ item.customer }}</td>
            <td>{{ item.source }}</td>
            <td>{{ item.item }}</td>
            <td class="money">{{ formatMoney(item.amount) }}</td>
          </tr>

          <tr v-if="filteredRevenues.length === 0">
            <td colspan="5" style="text-align:center; color:#6b7280;">
              Không có dữ liệu doanh thu
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <div class="pagination-container mt-3" v-if="totalPages > 1">
      <div class="pagination-info">
        Trang {{ currentPage }} / {{ totalPages }} (Tổng {{ totalRecords }} bản ghi)
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
</template>

<script>
import "./index.css";
import axios from '@/axios';

export default {
  name: "RevenueManager",

  data() {
    return {
      filter: {
        fromDate: "",
        toDate: "",
        source: ""
      },

      revenues: [],
      currentPage: 1,
      totalPages: 1,
      totalRecords: 0
    };
  },

  computed: {
    filteredRevenues() {
      return this.revenues.filter(r => {
        const matchSource =
          !this.filter.source || r.source === this.filter.source;

        const matchFrom =
          !this.filter.fromDate || r.date >= this.filter.fromDate;

        const matchTo =
          !this.filter.toDate || r.date <= this.filter.toDate;

        return matchSource && matchFrom && matchTo;
      });
    },

    totalRevenue() {
      return this.filteredRevenues.reduce(
        (sum, r) => sum + Number(r.amount),
        0
      );
    },

    todayRevenue() {
      const today = new Date().toISOString().slice(0, 10);
      return this.revenues
        .filter(r => r.date === today)
        .reduce((sum, r) => sum + Number(r.amount), 0);
    },

    monthRevenue() {
      const month = new Date().toISOString().slice(0, 7);
      return this.revenues
        .filter(r => r.date.startsWith(month))
        .reduce((sum, r) => sum + Number(r.amount), 0);
    },

    transactionCount() {
      return this.filteredRevenues.length;
    },

    revenueBySource() {
      const result = {
        "Gói tập": 0,
        "Dịch vụ": 0,
        "Sản phẩm": 0
      };

      this.filteredRevenues.forEach(r => {
        if (result[r.source] !== undefined) {
            result[r.source] += Number(r.amount);
        }
      });

      return result;
    }
  },
  mounted() {
    this.getListRevenue();
  },
  methods: {
    getListRevenue(page = 1) {
        axios.get(`admin/doanh-thu/get-data?page=${page}`)
            .then((res) => {
                this.revenues = res.data.data.data;
                this.currentPage = res.data.data.current_page;
                this.totalPages = res.data.data.last_page;
                this.totalRecords = res.data.data.total;
            });
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.getListRevenue(page);
      }
    },
    exportReport() {
      alert("Xuất báo cáo (demo)");
    },

    formatMoney(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value * 1000);
    }
  }
};
</script>
