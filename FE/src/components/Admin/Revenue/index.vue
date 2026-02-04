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

  </div>
</template>

<script>
import "./index.css";
export default {
  name: "RevenueManager",

  data() {
    return {
      /* ===== FILTER ===== */
      filter: {
        fromDate: "",
        toDate: "",
        source: ""
      },

      /* ===== REVENUE LIST (DATA GIẢ) ===== */
      revenues: [
        {
          id: 1,
          date: "2026-01-14",
          customer: "Nguyễn Văn A",
          source: "Gói tập",
          item: "Gym 1 tháng",
          amount: 500
        },
        {
          id: 2,
          date: "2026-01-14",
          customer: "Trần Thị B",
          source: "Dịch vụ",
          item: "PT cá nhân",
          amount: 3000
        },
        {
          id: 3,
          date: "2026-01-13",
          customer: "Lê Văn C",
          source: "Sản phẩm",
          item: "Whey Protein",
          amount: 1200
        },
        {
          id: 4,
          date: "2026-01-12",
          customer: "Phạm Thị D",
          source: "Dịch vụ",
          item: "Yoga cơ bản",
          amount: 800
        }
      ]
    };
  },

  /* ===== COMPUTED ===== */
  computed: {
    /* LỌC DANH SÁCH DOANH THU */
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

    /* TỔNG DOANH THU */
    totalRevenue() {
      return this.filteredRevenues.reduce(
        (sum, r) => sum + r.amount,
        0
      );
    },

    /* DOANH THU HÔM NAY */
    todayRevenue() {
      const today = new Date().toISOString().slice(0, 10);
      return this.revenues
        .filter(r => r.date === today)
        .reduce((sum, r) => sum + r.amount, 0);
    },

    /* DOANH THU THÁNG HIỆN TẠI */
    monthRevenue() {
      const month = new Date().toISOString().slice(0, 7);
      return this.revenues
        .filter(r => r.date.startsWith(month))
        .reduce((sum, r) => sum + r.amount, 0);
    },

    /* SỐ GIAO DỊCH */
    transactionCount() {
      return this.filteredRevenues.length;
    },

    /* DOANH THU THEO NGUỒN */
    revenueBySource() {
      const result = {
        "Gói tập": 0,
        "Dịch vụ": 0,
        "Sản phẩm": 0
      };

      this.filteredRevenues.forEach(r => {
        result[r.source] += r.amount;
      });

      return result;
    }
  },

  /* ===== METHODS ===== */
  methods: {
    exportReport() {
      alert("Xuất báo cáo (demo)");
      // Sau này thay bằng export Excel / PDF
    },

    formatMoney(value) {
      return value.toLocaleString("vi-VN") + "k";
    }
  }
};
</script>
