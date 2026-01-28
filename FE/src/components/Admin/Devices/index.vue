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
          <tr v-for="item in equipments" :key="item.id">
            <td>
              <div class="device">
                <div class="icon">🏋️</div>
                <div>
                  <strong>{{ item.name }}</strong>
                  <span>
                    <br />
                    ID: {{ item.id }}</span
                  >
                </div>
              </div>
            </td>
            <td>{{ item.type }}</td>
            <td>{{ item.vendor }}</td>
            <td>{{ item.room }}</td>
            <td>
              <span :class="['status', item.statusClass]">
                {{ item.status }}
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
  </div>
  <div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-custom">
        <div class="modal-header gradient">
          <h5>Thêm thiết bị</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input v-model="newItem.name" placeholder="Tên thiết bị" />
          <input v-model="newItem.type" placeholder="Loại" />
          <input v-model="newItem.vendor" placeholder="Nhà cung cấp" />
          <input v-model="newItem.room" placeholder="Phòng" />

          <select v-model="newItem.status">
            <option value="Tốt">Tốt</option>
            <option value="Cần bảo trì">Cần bảo trì</option>
            <option value="Hỏng">Hỏng</option>
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
          <input v-model="editItem.name" />
          <input v-model="editItem.type" />
          <input v-model="editItem.vendor" />
          <input v-model="editItem.room" />

          <select v-model="editItem.status">
            <option value="Tốt">Tốt</option>
            <option value="Cần bảo trì">Cần bảo trì</option>
            <option value="Hỏng">Hỏng</option>
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
          <p>{{ deleteItem?.name }} sẽ bị xóa vĩnh viễn</p>
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
export default {
  data() {
    return {
      equipments: [
        {
          id: 1,
          name: "Máy chạy bộ Technogym",
          type: "Cardio",
          vendor: "Technogym Vietnam",
          room: "Phòng Cardio A",
          status: "Tốt",
          statusClass: "good",
        },
        {
          id: 3,
          name: "Xe đạp tập",
          type: "Cardio",
          vendor: "Technogym Vietnam",
          room: "Phòng Cardio A",
          status: "Cần bảo trì",
          statusClass: "warning",
        },
        {
          id: 5,
          name: "Máy kéo xô",
          type: "Máy tập sức mạnh",
          vendor: "Life Fitness",
          room: "Phòng tập tạ",
          status: "Hỏng",
          statusClass: "bad",
        },
      ],

      newItem: {
        name: "",
        type: "",
        vendor: "",
        room: "",
        status: "Tốt",
      },

      editItem: {},
      deleteItem: null,
    };
  },

  methods: {
    statusToClass(status) {
      if (status === "Tốt") return "good";
      if (status === "Cần bảo trì") return "warning";
      return "bad";
    },

    addEquipment() {
      const id = Date.now();
      this.equipments.push({
        ...this.newItem,
        id,
        statusClass: this.statusToClass(this.newItem.status),
      });

      this.newItem = {
        name: "",
        type: "",
        vendor: "",
        room: "",
        status: "Tốt",
      };
    },

    openEdit(item) {
      this.editItem = { ...item };
    },

    updateEquipment() {
      const index = this.equipments.findIndex((e) => e.id === this.editItem.id);
      this.editItem.statusClass = this.statusToClass(this.editItem.status);
      this.equipments.splice(index, 1, this.editItem);
    },

    openDelete(item) {
      this.deleteItem = item;
    },

    deleteEquipment() {
      this.equipments = this.equipments.filter(
        (e) => e.id !== this.deleteItem.id,
      );
    },
  },
};
</script>
