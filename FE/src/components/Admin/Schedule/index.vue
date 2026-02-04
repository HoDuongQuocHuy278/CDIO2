<template>
  <div class="schedule-page">
    <!-- HEADER -->
    <div class="schedule-header">
      <div>
        <h2>Lịch làm PT</h2>
        <p>Quản lý lịch dạy của huấn luyện viên</p>
      </div>
      <button class="btn-add" @click="openAdd">+ Thêm lịch</button>
    </div>

    <!-- CALENDAR -->
    <div class="calendar">
      <!-- HEADER DAYS -->
      <div class="calendar-header">
        <div class="time-col"></div>
        <div v-for="day in days" :key="day" class="day-col">
          {{ day }}
        </div>
      </div>

      <!-- BODY -->
      <div class="calendar-body">
        <div v-for="hour in hours" :key="hour" class="calendar-row">
          <!-- TIME -->
          <div class="time-col">{{ hour }}:00</div>

          <!-- DAY CELLS -->
          <div v-for="day in days" :key="day" class="day-cell">
            <div
              v-for="event in getEvents(day, hour)"
              :key="event.id"
              class="event-note"
              :style="getEventStyle(event)"
            >
              <!-- ACTIONS -->
              <div class="note-actions">
                <i class="fa-solid fa-pen" @click.stop="openEdit(event)"></i>
                <i
                  class="fa-solid fa-trash"
                  @click.stop="openDelete(event)"
                ></i>
              </div>

              <div class="note-pt">{{ event.pt }}</div>
              <div class="note-customer">
                {{ event.customer || "Khách chưa đặt" }}
              </div>
              <div class="note-time">
                {{ event.start }}:00 - {{ event.end }}:00
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ADD MODAL -->
    <div v-if="showModal" class="modal-overlay">
  <div class="custom-modal">

    <h3 class="modal-title">
      {{ editingEvent ? "Sửa lịch làm" : "Thêm lịch làm" }}
    </h3>

    <div class="modal-form">

      <!-- ROW 1 -->
      <div class="form-row">
        <div class="form-group">
          <label>Tên PT</label>
          <input v-model="form.pt" placeholder="VD: PT An" />
        </div>

        <div class="form-group">
          <label>Tên khách</label>
          <input v-model="form.customer" placeholder="VD: Nguyễn Văn B" />
        </div>
      </div>

      <!-- ROW 2 -->
      <div class="form-row">
        <div class="form-group">
          <label>Thứ</label>
          <select v-model="form.day">
            <option v-for="day in days" :key="day">{{ day }}</option>
          </select>
        </div>

        <div class="form-group">
          <label>Bắt đầu</label>
          <input type="number" v-model.number="form.start" />
        </div>

        <div class="form-group">
          <label>Kết thúc</label>
          <input type="number" v-model.number="form.end" />
        </div>
      </div>

    </div>

    <!-- ACTIONS -->
    <div class="modal-actions">
      <button @click="closeModal">Hủy</button>
      <button class="primary" @click="saveEvent">
        {{ editingEvent ? "Cập nhật" : "Lưu" }}
      </button>
    </div>

  </div>
</div>

    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="custom-modal text-center">
        <i class="fa-solid fa-trash delete-icon"></i>
        <h3>Xóa lịch làm?</h3>
        <p>
          {{ deletingEvent?.pt }} — {{ deletingEvent?.start }}:00 →
          {{ deletingEvent?.end }}:00
        </p>

        <div class="modal-actions">
          <button @click="showDeleteModal = false">Hủy</button>
          <button class="danger" @click="confirmDelete">Xóa</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import "./index.css";
export default {
  name: "SchedulePT",

  data() {
    return {
      showModal: false,
      showDeleteModal: false, // ✅ THÊM
      editingEvent: null, // ✅ THÊM
      deletingEvent: null, // ✅ THÊM

      days: ["Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "CN"],
      hours: Array.from({ length: 14 }, (_, i) => i + 8), // 8 → 21
      form: {
        pt: "",
        customer: "",
        day: "Thứ 2",
        start: 8,
        end: 9,
      },
      events: [
        {
          id: 1,
          pt: "PT An",
          customer: "Nguyễn Văn B",
          day: "Thứ 3",
          start: 8,
          end: 12,
        },
        {
          id: 2,
          pt: "PT An",
          customer: "Nguyễn Văn B",
          day: "Thứ 2",
          start: 13,
          end: 16,
        },
        {
          id: 3,
          pt: "PT An",
          customer: "Nguyễn Văn B",
          day: "CN",
          start: 16,
          end: 21,
        },
      ],

      newEvent: {
        pt: "",
        day: "Thứ 2",
        start: 8,
        end: 9,
      },
    };
  },

  methods: {
    openAdd() {
      this.editingEvent = null;
      this.form = {
        pt: "",
        customer: "",
        day: "Thứ 2",
        start: 8,
        end: 9,
      };
      this.showModal = true;
    },

    openEdit(event) {
      this.editingEvent = event;
      this.form = { ...event };
      this.showModal = true;
    },

    openDelete(event) {
      this.deletingEvent = event;
      this.showDeleteModal = true;
    },

    saveEvent() {
      if (this.editingEvent) {
        Object.assign(this.editingEvent, this.form);
      } else {
        this.events.push({
          ...this.form,
          id: Date.now(),
        });
      }
      this.closeModal();
    },

    confirmDelete() {
      this.events = this.events.filter((e) => e.id !== this.deletingEvent.id);
      this.showDeleteModal = false;
      this.deletingEvent = null;
    },

    closeModal() {
      this.showModal = false;
      this.editingEvent = null;
      this.form = {
        pt: "",
        customer: "",
        day: "Thứ 2",
        start: 8,
        end: 9,
      };
    },

    getEvents(day, hour) {
      return this.events.filter((e) => e.day === day && e.start === hour);
    },

    getEventStyle(event) {
      return {
        height: (event.end - event.start) * 60 + "px",
      };
    },

    addEvent() {
      this.events.push({
        ...this.newEvent,
        id: Date.now(),
      });

      this.newEvent = {
        pt: "",
        day: "Thứ 2",
        start: 8,
        end: 9,
      };

      this.showModal = false;
    },
  },
};
</script>
