<template>
  <div class="page-container">
    <div class="content-wrapper">
      <div class="header-section">
        <div class="header-content">
          <h2 class="page-title">Quản lý thành viên</h2>
          <p class="page-subtitle">
            Quản lý hồ sơ, gói tập và theo dõi nguy cơ rời bỏ (Churn Risk)
          </p>
        </div>

        <div class="action-bar">
          <div class="search-wrapper">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input
              type="text"
              class="search-input"
              v-model="tim_kiem.noi_dung_tim_kiem"
              @keyup.enter="timKiem()"
              placeholder="Tìm kiếm thành viên..."
            />
          </div>
          <button
            class="btn-action primary"
            data-bs-toggle="modal"
            data-bs-target="#addModal"
          >
            <i class="fa-solid fa-plus"></i>
            <span>Thêm mới</span>
          </button>
        </div>
      </div>

      <div class="table-card">
        <table class="custom-table">
          <thead>
            <tr>
              <th class="col-member">Thành viên</th>
              <th class="col-contact">Liên hệ</th>
              <th class="col-package">Gói đăng ký</th>
              <th class="col-date">Thời hạn (Start - End)</th>
              <th class="col-warning">Cảnh báo / Vắng</th>
              <th class="col-action">Tác vụ</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(member, index) in list_members"
              :key="index"
              :class="{ 'risk-row': getDaysAbsent(member.ngay_tap_cuoi) > 14 }"
            >
              <td>
                <div class="member-profile">
                  <div class="avatar" :class="getRandomColor(index)">
                    <img v-if="member.avatar" :src="member.avatar" alt="" />
                    <span v-else>{{ getInitials(member.full_name) }}</span>
                  </div>
                  <div class="profile-info">
                    <span class="full-name">{{ member.full_name }}</span>
                    <div class="d-flex align-items-center gap-2">
                      <span
                        class="mini-status"
                        :class="member.status === 1 ? 'active' : 'expired'"
                      >
                        {{ member.status === 1 ? "Active" : "Expired" }}
                      </span>
                    </div>
                  </div>
                </div>
              </td>

              <td>
                <div class="contact-group">
                  <div class="contact-row">
                    <i class="fa-solid fa-phone"></i> {{ member.phone }}
                  </div>
                  <div class="contact-row secondary">
                    <i class="fa-solid fa-envelope"></i> {{ member.email }}
                  </div>
                </div>
              </td>

              <td>
                <div class="package-tag">
                  <i class="fa-solid fa-dumbbell"></i> {{ member.package_name }}
                </div>
              </td>

              <td>
                <div class="date-column">
                  <div class="date-row start" title="Ngày bắt đầu">
                    <i class="fa-regular fa-calendar-plus"></i>
                    {{ member.start_date }}
                  </div>
                  <div class="date-row end" title="Ngày hết hạn">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    {{ member.end_date }}
                  </div>
                </div>
              </td>

              <td class="cell-center">
                <div class="risk-container">
                  <div
                    v-if="getDaysAbsent(member.ngay_tap_cuoi) > 14"
                    class="risk-badge danger blink"
                  >
                    <i class="fa-solid fa-triangle-exclamation"></i> Nguy cơ cao
                  </div>
                  <div
                    v-else-if="getDaysAbsent(member.ngay_tap_cuoi) > 7"
                    class="risk-badge warning"
                  >
                    <i class="fa-solid fa-clock-rotate-left"></i> Cần chú ý
                  </div>
                  <div v-else class="risk-badge safe">
                    <i class="fa-solid fa-check"></i> Đều đặn
                  </div>

                  <span class="days-absent">
                    Vắng: <b>{{ getDaysAbsent(member.ngay_tap_cuoi) }}</b> ngày
                  </span>
                </div>
              </td>

              <td class="cell-end">
                <div class="action-group">
                  <button
                    class="btn-icon view"
                    @click="openViewDetail(member)"
                    data-bs-toggle="modal"
                    data-bs-target="#viewModal"
                    title="Xem chi tiết"
                  >
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  <button
                    v-if="getDaysAbsent(member.ngay_tap_cuoi) > 7"
                    class="btn-icon zalo-btn"
                    title="Gửi tin nhắn nhắc nhở"
                  >
                    <i class="fa-solid fa-comment-dots"></i>
                  </button>

                  <button
                    class="btn-icon edit"
                    @click="openUpdateModal(member)"
                    data-bs-toggle="modal"
                    data-bs-target="#updateModal"
                    title="Sửa"
                  >
                    <i class="fa-solid fa-pen"></i>
                  </button>
                  <button
                    class="btn-icon delete"
                    @click="Object.assign(delete_member, member)"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal"
                    title="Xóa"
                  >
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="pagination-area" v-if="totalPages > 1">
          <span class="pagination-info">
            Trang <b>{{ currentPage }}</b> / <b>{{ totalPages }}</b> (Tổng <b>{{ totalRecords }}</b> kết quả)
          </span>
          <div class="pagination-buttons">
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
    </div>
  </div>

  <div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content modern-modal">
        <!-- HEADER -->
        <div class="modal-header-custom primary-header">
          <h5 class="modal-title-custom">
            <i class="fa-solid fa-user-plus me-2"></i>Thêm Thành Viên
          </h5>
          <button class="btn-close-custom" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>

        <!-- BODY -->
        <div class="modal-body-custom">
          <div class="row g-4">
            <!-- LEFT: FORM -->
            <div class="col-lg-6">
              <div class="row g-3">
                <!-- Họ tên -->
                <div class="col-12">
                  <label class="input-label">Họ và Tên *</label>
                  <input
                    v-model="create_member.full_name"
                    class="input-field"
                    placeholder="Nhập họ tên"
                  />
                </div>

                <!-- SĐT -->
                <div class="col-12">
                  <label class="input-label">Số điện thoại *</label>
                  <input
                    v-model="create_member.phone"
                    class="input-field"
                    placeholder="Nhập số điện thoại"
                  />
                </div>

                <!-- Email -->
                <div class="col-12">
                  <label class="input-label">Email</label>
                  <input
                    v-model="create_member.email"
                    class="input-field"
                    placeholder="example@gmail.com"
                  />
                </div>

                <!-- Gói + Thời hạn -->
                <div class="col-6">
                  <label class="input-label">Loại gói *</label>
                  <select v-model="create_member.package_id" class="input-field">
                    <option value="">Chọn gói</option>
                    <option v-for="pkg in list_packages" :key="pkg.id" :value="pkg.id">
                      {{ pkg.ten_goi }}
                    </option>
                  </select>
                </div>

                <div class="col-6">
                  <label class="input-label">Thời hạn *</label>
                  <select
                    v-model.number="create_member.thoi_han"
                    class="input-field"
                  >
                    <option :value="1">1 tháng</option>
                    <option :value="3">3 tháng</option>
                    <option :value="6">6 tháng</option>
                    <option :value="12">12 tháng</option>
                  </select>
                </div>

                <!-- Ngày bắt đầu -->
                <div class="col-12">
                  <label class="input-label">
                    Ngày bắt đầu <span class="req">*</span>
                  </label>
                  <input
                    type="date"
                    v-model="create_member.start_date"
                    class="input-field"
                  />
                  <small class="text-muted">
                    Ngày tính hạn gói và check-in đầu tiên
                  </small>
                </div>

                <!-- Price display -->
                <div class="col-12" v-if="calculatedPrice > 0">
                  <div class="price-summary-box">
                    <div class="d-flex justify-content-between">
                      <span class="text-muted">Đơn giá tháng:</span>
                      <span class="fw-bold">{{ formatCurrency(selectedPackagePrice) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                      <span class="text-muted">Thời hạn:</span>
                      <span class="fw-bold">{{ create_member.thoi_han }} tháng</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="fw-bold">TỔNG CỘNG:</span>
                      <span class="total-price-value">{{ formatCurrency(calculatedPrice) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- RIGHT: CAMERA -->
            <div class="col-lg-6">
              <div class="camera-box">
                <WebcamUI
                  ref="camera"
                  @photoTaken="onPhotoTaken"
                  @photoCleared="clearFaceImage"
                />

                <!-- Preview -->
                <div v-if="facePreview" class="mt-3 text-center">
                  <p class="fw-bold mb-1">Ảnh khuôn mặt</p>
                  <img :src="facePreview" class="face-preview" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer-custom">
          <button class="btn-action secondary" data-bs-dismiss="modal">
            Đóng
          </button>
          <button
            class="btn-action primary"
            @click="ThemMoiThanhVien"
            data-bs-dismiss="modal"
          >
            <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Hồ Sơ
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content modern-modal">
        <div class="modal-header-custom primary-header">
          <h5 class="modal-title-custom">
            <i class="fa-solid fa-pen-to-square me-2"></i>Cập Nhật Thông Tin
          </h5>
          <button
            type="button"
            class="btn-close-custom"
            data-bs-dismiss="modal"
          >
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
        <div class="modal-body-custom">
          <div class="row g-4">
            <div class="col-md-6">
              <label class="input-label">Họ và tên</label>
              <input
                v-model="update_member.full_name"
                type="text"
                class="input-field"
              />
            </div>
            <div class="col-md-6">
              <label class="input-label">Số điện thoại</label>
              <input
                v-model="update_member.phone"
                type="text"
                class="input-field"
              />
            </div>
            <div class="col-md-6">
              <label class="input-label">Email</label>
              <input
                v-model="update_member.email"
                type="email"
                class="input-field"
              />
            </div>
            <div class="col-md-6">
              <label class="input-label">Gói đăng ký</label>
              <select
                v-model="update_member.package_id"
                class="input-field select-field"
              >
                <option v-for="pkg in list_packages" :key="pkg.id" :value="pkg.id">
                  {{ pkg.ten_goi }}
                </option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="input-label">Ngày Bắt Đầu</label>
              <input
                v-model="update_member.start_date"
                type="date"
                class="input-field"
              />
            </div>
            <div class="col-md-6">
              <label class="input-label">Ngày Kết Thúc</label>
              <input
                v-model="update_member.end_date"
                type="date"
                class="input-field"
              />
            </div>

            <!-- Update Price Display -->
            <div class="col-md-12" v-if="calculatedPriceUpdate > 0">
               <div class="price-summary-box bg-light-purple">
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fa-solid fa-coins me-2"></i>Chi phí đăng ký mới:</span>
                    <span class="total-price-value text-primary fs-4">{{ formatCurrency(calculatedPriceUpdate) }}</span>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
              <label class="input-label">Trạng Thái</label>
              <select
                v-model.number="update_member.status"
                class="input-field select-field"
              >
                <option :value="1">Đang tập</option>
                <option :value="0">Hết hạn</option>
              </select>
            </div>

            <!-- UPDATE PHOTO -->
            <div class="col-md-12 mt-3">
              <div class="camera-box">
                <WebcamUI
                  ref="cameraUpdate"
                  @photoTaken="onUpdatePhotoTaken"
                  @photoCleared="clearUpdateFaceImage"
                />
                <div v-if="facePreviewUpdate || getFaceUrl(update_member.id)" class="mt-3 text-center">
                  <p class="fw-bold mb-1">Ảnh khuôn mặt (Cập nhật)</p>
                  <img :src="facePreviewUpdate || getFaceUrl(update_member.id)" class="face-preview" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer-custom">
          <button
            type="button"
            class="btn-action secondary"
            data-bs-dismiss="modal"
          >
            Đóng
          </button>
          <button
            v-on:click="CapNhatThanhVien()"
            type="button"
            class="btn-action primary"
            data-bs-dismiss="modal"
          >
            <i class="fa-solid fa-check me-1"></i> Cập Nhật
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modern-modal">
        <div class="modal-body-custom text-center-content">
          <div class="icon-circle danger-icon">
            <i class="fa-solid fa-trash-can"></i>
          </div>
          <h4 class="confirm-title">Xác nhận xóa?</h4>
          <p class="confirm-desc">
            Thành viên <strong>{{ delete_member.full_name }}</strong> sẽ bị xóa
            vĩnh viễn khỏi hệ thống.
          </p>
          <div class="confirm-actions">
            <button
              type="button"
              class="btn-action secondary"
              data-bs-dismiss="modal"
            >
              Hủy bỏ
            </button>
            <button
              v-on:click="xoaThanhVien()"
              type="button"
              class="btn-action danger"
              data-bs-dismiss="modal"
            >
              Xóa thành viên
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- VIEW DETAIL MODAL -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content modern-modal">
        <div class="modal-header-custom primary-header">
          <h5 class="modal-title-custom">
            <i class="fa-solid fa-user-check me-2"></i>Chi Tiết Thành Viên
          </h5>
          <button type="button" class="btn-close-custom" data-bs-dismiss="modal">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
        <div class="modal-body-custom">
          <div class="row g-4 ai-profile-view">
            <div class="col-md-5">
              <div class="ai-face-card">
                <div class="face-frame">
                   <img :src="getFaceUrl(detail_member.id)" @error="$event.target.src='https://ui-avatars.com/api/?name='+detail_member.full_name" class="img-fluid rounded">
                   <div class="scan-line-mini"></div>
                </div>
                <div class="face-id-badge">
                  <i class="fa-solid fa-microchip"></i> FACE ID: ACTIVE
                </div>
              </div>
            </div>
            <div class="col-md-7">
               <div class="info-list">
                  <div class="info-item">
                    <span class="label">Họ và Tên:</span>
                    <span class="value">{{ detail_member.full_name }}</span>
                  </div>
                  <div class="info-item">
                    <span class="label">Số điện thoại:</span>
                    <span class="value">{{ detail_member.phone }}</span>
                  </div>
                  <div class="info-item">
                    <span class="label">Email:</span>
                    <span class="value text-muted">{{ detail_member.email || 'N/A' }}</span>
                  </div>
                  <div class="info-item">
                    <span class="label">Gói đăng ký:</span>
                    <span class="value highlight">{{ detail_member.package_name }}</span>
                  </div>
                  <div class="info-item">
                    <span class="label">Thời hạn:</span>
                    <span class="value">{{ detail_member.start_date }} - {{ detail_member.end_date }}</span>
                  </div>
                  <div class="info-item">
                    <span class="label">Vắng mặt:</span>
                    <span class="value">{{ getDaysAbsent(detail_member.ngay_tap_cuoi) }} ngày</span>
                  </div>
                  <div class="info-item">
                    <span class="label">Giá trị gói:</span>
                    <span class="value text-success fw-bold">{{ formatCurrency(detail_member.package_price) }}</span>
                  </div>
               </div>
            </div>
          </div>
        </div>
        <div class="modal-footer-custom">
          <button class="btn-action secondary w-100" data-bs-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import "./index.css";
import WebcamUI from "@/components/Webcam/WebCamUI.vue";
import axios from '@/axios';

export default {
  components: {
    WebcamUI,
  },
  data() {
    return {
      list_members: [],
      create_member: {
        full_name: "",
        phone: "",
        email: "",
        package_id: "",
        thoi_han: 1,
        start_date: new Date().toISOString().split("T")[0],
        end_date: null,
        face_image: null,
        status: 1,
      },
      list_packages: [],
      facePreview: null,
      facePreviewUpdate: null,
      update_member: {},
      delete_member: {},
      detail_member: {},
      tim_kiem: { noi_dung_tim_kiem: "" },
      currentPage: 1,
      totalPages: 1,
      totalRecords: 0,
    };
  },
  mounted() {
    this.getListMembers();
    this.getListPackages();
  },
  computed: {
    selectedPackagePrice() {
      if (!this.create_member.package_id) return 0;
      const pkg = this.list_packages.find(p => p.id === this.create_member.package_id);
      return pkg ? pkg.gia_tien : 0;
    },
    calculatedPrice() {
      return this.selectedPackagePrice * (this.create_member.thoi_han || 1);
    },
    selectedPackagePriceUpdate() {
      if (!this.update_member.package_id) return 0;
      const pkg = this.list_packages.find(p => p.id === this.update_member.package_id);
      return pkg ? pkg.gia_tien : 0;
    },
    calculatedPriceUpdate() {
      return this.selectedPackagePriceUpdate * (this.update_member.thoi_han || this.update_member.package_duration || 1);
    }
  },
  methods: {
    onPhotoTaken({ image_data_url }) {
      this.create_member.face_image = image_data_url;
      this.facePreview = image_data_url;
    },
    clearFaceImage() {
      this.create_member.face_image = null;
      this.facePreview = null;
    },
    onUpdatePhotoTaken({ image_data_url }) {
      this.update_member.face_image = image_data_url;
      this.facePreviewUpdate = image_data_url;
    },
    clearUpdateFaceImage() {
      this.update_member.face_image = null;
      this.facePreviewUpdate = null;
    },
    openUpdateModal(member) {
      this.update_member = { ...member };
      this.facePreviewUpdate = null;
    },
    getInitials(name) {
      return name ? name.split(" ").pop().charAt(0).toUpperCase() : "";
    },
    getRandomColor(index) {
      const colors = [
        "bg-gradient-blue",
        "bg-gradient-purple",
        "bg-gradient-green",
        "bg-gradient-orange",
      ];
      return colors[index % colors.length];
    },

    getDaysAbsent(lastDateStr) {
      if (!lastDateStr) return 0;
      const today = new Date();
      const lastDate = new Date(lastDateStr);
      const diffTime = today - lastDate;
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays > 0 ? diffDays : 0;
    },

    getListMembers(page = 1) {
        axios.get(`admin/thanh-vien/get-data?page=${page}`)
            .then((res) => {
                this.list_members = res.data.data.data;
                this.currentPage = res.data.data.current_page;
                this.totalPages = res.data.data.last_page;
                this.totalRecords = res.data.data.total;
            });
    },

    getListPackages() {
        axios.get('admin/thanh-vien/get-packages')
            .then((res) => {
                this.list_packages = res.data.data;
            });
    },

    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.getListMembers(page);
      }
    },

    ThemMoiThanhVien() {
        const payload = {
            ...this.create_member,
            package_price: this.calculatedPrice
        };
        axios.post('admin/thanh-vien/add-data', payload)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.create_member = {
                        full_name: "",
                        phone: "",
                        email: "",
                        package_id: "",
                        thoi_han: 1,
                        start_date: new Date().toISOString().split("T")[0],
                        end_date: null,
                        status: 1
                    };
                    this.facePreview = null;
                    if (this.$refs.camera) this.$refs.camera.retakePhoto();
                    this.getListMembers(this.currentPage);
                } else {
                    this.$toast.error('Thêm thành viên thất bại: ' + res.data.message);
                }
            });
    },

    CapNhatThanhVien() {
        const payload = {
            ...this.update_member,
            package_price: this.calculatedPriceUpdate
        };
        axios.post('admin/thanh-vien/update', payload)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.facePreviewUpdate = null;
                    if (this.$refs.cameraUpdate) this.$refs.cameraUpdate.retakePhoto();
                    this.getListMembers(this.currentPage);
                } else {
                    this.$toast.error('Cập nhật thành viên thất bại: ' + res.data.message);
                }
            });
    },

    xoaThanhVien() {
        axios.post('admin/thanh-vien/delete', this.delete_member)
            .then((res) => {
                if (res.data.status) {
                    this.$toast.success(res.data.message);
                    this.getListMembers(this.currentPage);
                } else {
                    this.$toast.error('Xóa thành viên thất bại: ' + res.data.message);
                }
            });
    },

    timKiem() {
        // Logic tìm kiếm frontend hoặc gọi api tìm kiếm
    },

    openViewDetail(member) {
      this.detail_member = { ...member };
    },

    getFaceUrl(id) {
      if (!id) return '';
      return `http://localhost:8000/storage/member_faces/${id}.jpg?t=` + new Date().getTime();
    },

    formatCurrency(value) {
      if (!value) return "0 đ";
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
    }
  },
};
</script>
