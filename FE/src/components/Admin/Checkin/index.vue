<template>
    <div class="row">
        <!-- Scan Section -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center mt-2">
                    <h4 class="fw-bold mb-0">
                        <i class="fa-solid fa-face-smile me-2"></i> WELLFIT ACCESS CONTROL
                    </h4>
                    <span class="badge bg-success">
                        <i class="fa-solid fa-circle me-1 animate-pulse"></i> SYSTEM ONLINE
                    </span>
                </div>
                <div class="card-body bg-light position-relative p-0" style="min-height: 500px;">
                    <!-- Camera View -->
                    <div v-if="!member" class="camera-container text-center p-0 position-relative overflow-hidden">
                        <div class="video-wrapper position-relative mx-auto" style="background: #1a1a1a;">
                            <video ref="videoRef" autoplay playsinline muted class="w-100" style="object-fit: cover; height: 500px;"></video>
                            
                            <!-- AI Status Overlay -->
                            <div class="position-absolute top-0 start-0 p-3 w-100 d-flex justify-content-between align-items-start z-index-2">
                                <div class="ai-badge" :class="aiStatus.class">
                                    <i class="fa-solid fa-microchip me-1"></i> {{ aiStatus.text }}
                                </div>
                                <div v-if="isRecognizing" class="scanning-badge animate-pulse">
                                    <i class="fa-solid fa-expand me-1"></i> ANALYZING BIOMETRICS...
                                </div>
                            </div>

                            <!-- Professional Targeting Frame -->
                            <div class="targeting-frame-container position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="targeting-frame" :class="{ 'locked': isRecognizing, 'detected': isFaceDetected }">
                                    <div class="corner top-left"></div>
                                    <div class="corner top-right"></div>
                                    <div class="corner bottom-left"></div>
                                    <div class="corner bottom-right"></div>
                                    
                                    <div class="scan-line-v2" v-if="isRecognizing"></div>
                                    
                                    <div class="frame-data top-data">REC [00:{{ Math.floor(Math.random() * 60).toString().padStart(2, '0') }}]</div>
                                    <div class="frame-data bottom-data">FACE_ID: {{ isFaceDetected ? 'DETECTED' : 'SEARCHING...' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="control-panel p-4 bg-white border-top shadow-sm">
                            <div class="row g-3 align-items-center justify-content-center">
                                <div class="col-md-8">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fa-solid fa-id-card text-primary"></i>
                                        </span>
                                        <input v-model="searchQuery" @keyup.enter="manualCheckIn()" type="text" 
                                            class="form-control border-start-0 bg-light" placeholder="Nhập ID hoặc Số điện thoại để check-in thủ công..." :disabled="isSaving">
                                        <button @click="manualCheckIn()" class="btn btn-primary px-4" :disabled="isSaving">
                                            <i class="fa-solid fa-check-circle me-1"></i> XÁC NHẬN
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                    <button @click="simulateFaceID()" class="btn btn-outline-secondary btn-lg" title="Demo Nhận Diện">
                                        <i class="fa-solid fa-robot"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Card -->
                    <transition name="fade">
                        <div v-if="member" class="profile-display h-100 d-flex flex-column p-4">
                            <div class="card border-0 shadow-lg overflow-hidden flex-fill">
                                <div :class="member.churnClass" class="card-header py-3 text-white text-center fw-bold">
                                    <i class="fa-solid me-2" :class="member.churnIcon"></i> {{ member.churnLabel }}
                                </div>
                                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-5">
                                    <div class="position-relative mb-4">
                                        <img :src="member.avatar" class="rounded-circle shadow" style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #fff;">
                                        <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm">
                                            <i class="fa-solid fa-check-circle text-success fs-4"></i>
                                        </div>
                                    </div>
                                    <h1 class="display-6 fw-bold mb-1">{{ member.name }}</h1>
                                    <p class="text-muted fs-5 mb-4">
                                        <span class="badge bg-primary px-3 py-2 rounded-pill">{{ member.pack }}</span>
                                        <span class="mx-2">•</span>
                                        <span class="text-dark">Hết hạn: {{ member.expiry }}</span>
                                    </p>

                                    <div class="alert alert-info w-100 rounded-3 border-0 d-flex align-items-center mb-4">
                                        <i class="fa-solid fa-lightbulb me-3 fs-3 text-info"></i>
                                        <div class="text-start">
                                            <div class="small fw-bold text-uppercase text-muted">AI Suggestion</div>
                                            <div class="fw-bold">{{ member.aiMessage }}</div>
                                        </div>
                                    </div>

                                    <div class="row w-100 g-3">
                                        <div class="col-6">
                                            <div class="p-3 bg-white border rounded-3 text-center shadow-sm">
                                                <div class="small text-muted mb-1 text-uppercase">Check-in #</div>
                                                <div class="h4 fw-bold text-dark mb-0 font-monospace">{{ member.totalVisits }}</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-white border rounded-3 text-center shadow-sm">
                                                <div class="small text-muted mb-1 text-uppercase">Tần suất</div>
                                                <div class="h4 fw-bold text-dark mb-0">{{ member.frequency }} <small class="fs-6">/ tuần</small></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-auto pt-5 w-100 d-flex gap-3">
                                        <button @click="resetScan()" class="btn btn-outline-secondary btn-lg flex-fill">
                                            <i class="fa-solid fa-arrow-left me-2"></i> QUÉT TIẾP
                                        </button>
                                        <button v-if="member.isChurnRisk" @click="sendZaloMsg(member)" class="btn btn-warning btn-lg flex-fill">
                                            <i class="fa-solid fa-comment-dots me-2"></i> GỬI ZALO CSKH
                                        </button>
                                        <button v-else class="btn btn-success btn-lg flex-fill">
                                            <i class="fa-solid fa-door-open me-2"></i> MỞ CỬA
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="col-lg-4">
            <!-- Counter Widget -->
            <div class="card mb-4 border-0 shadow-sm bg-primary text-white overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <i class="fa-solid fa-users position-absolute top-50 end-0 translate-middle-y me-4 opacity-25" style="font-size: 5rem;"></i>
                    <h6 class="text-uppercase fw-bold opacity-75 mb-3">Lượt Khách Hôm Nay</h6>
                    <div class="display-3 fw-bold mb-2">{{ checkInCount }}</div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-white text-primary me-2">Active</span>
                        <span>Đang tập: <strong>{{ activeNow }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Area Status Widget -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-layer-group me-2 text-primary"></i>TRẠNG THÁI KHU VỰC</h6>
                </div>
                <div class="card-body pt-0">
                    <div v-for="zone in zones" :key="zone.name" class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-medium">{{ zone.name }}</span>
                            <span class="badge" :class="getZoneColor(zone.percent)">{{ getZoneStatus(zone.percent) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" :class="getZoneColor(zone.percent)" role="progressbar" :style="{ width: zone.percent + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs Widget -->
            <div class="card border-0 shadow-sm flex-fill">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-primary"></i>VỪA CHECK-IN</h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" :disabled="logCurrentPage === 1" @click="changeLogPage(logCurrentPage - 1)">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-outline-secondary" :disabled="logCurrentPage === logTotalPages" @click="changeLogPage(logCurrentPage + 1)">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div v-if="isLoadingLogs" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    </div>
                    <ul v-else class="list-group list-group-flush">
                        <li v-for="(log, i) in recentLogs" :key="i" class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-light">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-light text-dark font-monospace me-2">{{ log.time }}</span>
                                <span class="fw-medium text-dark">{{ log.name }}</span>
                            </div>
                            <i class="fa-solid fa-circle-check text-success"></i>
                        </li>
                    </ul>
                    <div class="text-center mt-3" v-if="logTotalPages > 0">
                        <small class="text-muted">Trang {{ logCurrentPage }} / {{ logTotalPages }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '../../../axios';

export default {
    data() {
        return {
            searchQuery: '',
            member: null,
            checkInCount: 0,
            activeNow: 0,
            videoRef: null,
            stream: null,
            zones: [
                { name: 'Khu Cardio (Tầng 1)', percent: 85 },
                { name: 'Free Weight (Tầng 2)', percent: 30 },
                { name: 'Yoga Studio', percent: 10 }
            ],
            recentLogs: [],
            logCurrentPage: 1,
            logTotalPages: 1,
            logTotalRecords: 0,
            isLoadingLogs: false,
            isSaving: false,
            isRecognizing: false,
            detectInterval: null,
            isDetecting: false,
            isFaceDetected: false,
            aiStatus: {
                text: 'AI OFFLINE',
                class: 'bg-danger'
            }
        };
    },
    mounted() {
        this.startCamera();
        this.fetchLogs();
        this.checkAIHealth();
        this.startAutoDetection();
    },
    beforeUnmount() {
        this.stopCamera();
        if (this.detectInterval) clearInterval(this.detectInterval);
    },
    methods: {
        async checkAIHealth() {
            try {
                // We use baseRequest directly or axios instance
                const res = await axios.get('http://localhost:5000/health');
                if (res.data.status) {
                    this.aiStatus = {
                        text: `AI ONLINE (${res.data.members_loaded} Users)`,
                        class: 'bg-success shadow-sm'
                    };
                }
            } catch (err) {
                this.aiStatus = {
                    text: 'AI SERVICE ERROR',
                    class: 'bg-warning text-dark shadow-sm'
                };
            }
        },
        async fetchLogs(page = 1) {
            this.isLoadingLogs = true;
            try {
                const res = await axios.get(`admin/check-in/get-data?page=${page}`);
                this.recentLogs = res.data.data.data.map(log => ({
                    time: new Date(log.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                    name: log.member.full_name
                }));
                this.logCurrentPage = res.data.data.current_page;
                this.logTotalPages = res.data.data.last_page;
                this.logTotalRecords = res.data.data.total;
                
                this.checkInCount = this.logTotalRecords;
                this.activeNow = Math.max(0, this.logTotalRecords - 2); 
            } catch (err) {
                this.$toast.error("Không thể tải lịch sử check-in!");
            } finally {
                this.isLoadingLogs = false;
            }
        },
        changeLogPage(page) {
            if (page >= 1 && page <= this.logTotalPages) {
                this.fetchLogs(page);
            }
        },
        async startCamera() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 1280, height: 720 },
                    audio: false
                });
                if (this.$refs.videoRef) {
                    this.$refs.videoRef.srcObject = this.stream;
                }
            } catch (err) {
                this.$toast.error('❌ Không mở được camera. Kiểm tra quyền trình duyệt!');
            }
        },
        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
        },
        startAutoDetection() {
            // Higher frequency check (every 1.5s) for responsive feel
            this.detectInterval = setInterval(async () => {
                if (this.isDetecting || this.member || !this.$refs.videoRef) return;
                
                const video = this.$refs.videoRef;
                if (video.readyState !== 4) return; // Haven't loaded yet

                // Simulation of face detection (frontend part)
                this.isFaceDetected = true; 
                
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0);
                
                const imageData = canvas.toDataURL('image/jpeg', 0.7);
                
                this.isDetecting = true;
                this.isRecognizing = true;
                
                try {
                    // Call the Python AI Service directly for speed
                    const res = await axios.post('http://localhost:5000/recognize', { image: imageData });
                    
                    if (res.data.status && res.data.member_id) {
                        const confidence = res.data.confidence || 0;
                        // Unified threshold 0.40
                        if (confidence >= 0.40) {
                            // Find member in our DB via ID
                            this.lookupAndCheckIn(res.data.member_id);
                        } else {
                            console.log("Face found but confidence too low:", confidence);
                        }
                    } else {
                        // Slowly fade out detection status if no face found
                        setTimeout(() => { if (this.isDetecting) this.isFaceDetected = false; }, 500);
                    }
                } catch (err) {
                    console.error("AI Service Offline or Error");
                    this.aiStatus = { text: 'AI DISCONNECTED', class: 'bg-dark' };
                } finally {
                    this.isDetecting = false;
                    this.isRecognizing = false;
                }
            }, 1500); 
        },
        async lookupAndCheckIn(memberId) {
            try {
                // First get member details from main API
                const res = await axios.get(`admin/thanh-vien/get-data`, {
                    params: { query: memberId }
                });
                
                // Usually returns a list or direct object depending on backend
                const list = res.data.data.data || res.data.data;
                const user = Array.isArray(list) ? list.find(m => m.id == memberId) : list;
                
                if (user) {
                    this.processCheckIn(user, 'FaceID');
                }
            } catch (err) {
                console.error("Error looking up member after AI match");
            }
        },
        analyzeMember(rawMember) {
            let result = {
                id: rawMember.id,
                name: rawMember.full_name,
                avatar: rawMember.avatar || 'https://ui-avatars.com/api/?name=' + rawMember.full_name,
                pack: rawMember.package_name || 'Standard Plan',
                expiry: rawMember.end_date || 'N/A',
                daysAbsent: 0,
                frequency: (Math.random() * 5 + 1).toFixed(1),
                totalVisits: rawMember.total_checkin || 0
            };

            const daysLeft = rawMember.end_date ? Math.ceil((new Date(rawMember.end_date) - new Date()) / (1000 * 60 * 60 * 24)) : 999;
            
            if (daysLeft > 7) {
                result.churnClass = 'bg-success';
                result.churnIcon = 'fa-circle-check';
                result.churnLabel = 'THÀNH VIÊN TÍCH CỰC';
                result.aiMessage = "Gói tập còn hạn dài. Chúc bạn tập luyện vui vẻ!";
                result.isChurnRisk = false;
            } else if (daysLeft > 0) {
                result.churnClass = 'bg-warning text-dark';
                result.churnIcon = 'fa-triangle-exclamation';
                result.churnLabel = 'SẮP HẾT HẠN';
                result.aiMessage = `Gói tập chỉ còn ${daysLeft} ngày. Hãy gia hạn sớm!`;
                result.isChurnRisk = true;
            } else {
                result.churnClass = 'bg-danger';
                result.churnIcon = 'fa-skull-crossbones';
                result.churnLabel = 'ĐÃ HẾT HẠN';
                result.aiMessage = "Gói tập đã hết hạn. Vui lòng gia hạn để tiếp tục.";
                result.isChurnRisk = true;
            }
            return result;
        },
        async processCheckIn(user, type = 'Manual') {
            this.isSaving = true;
            try {
                const res = await axios.post('admin/check-in/add-data', {
                    member_id: user.id,
                    check_in_type: type
                });
                if (res.data.status) {
                    this.$toast.success(`✅ Check-in thành công: ${user.full_name}`);
                    this.member = this.analyzeMember(user);
                    this.fetchLogs();
                } else {
                    this.$toast.error(res.data.message);
                }
            } catch (err) {
                this.$toast.error('Lỗi khi ghi nhận check-in');
            } finally {
                this.isSaving = false;
            }
        },
        async simulateFaceID() {
            this.member = null;
            try {
                const res = await axios.get('admin/thanh-vien/get-data');
                const list = res.data.data.data || res.data.data;
                if (list.length > 0) {
                    const randomUser = list[Math.floor(Math.random() * list.length)];
                    this.isRecognizing = true;
                    setTimeout(() => {
                        this.processCheckIn(randomUser, 'FaceID');
                        this.isRecognizing = false;
                    }, 1500);
                } else {
                    this.$toast.warning("Hệ thống chưa có thành viên nào");
                }
            } catch (err) {
                console.error(err);
            }
        },
        async manualCheckIn() {
            if (!this.searchQuery) return;
            this.isSaving = true;
            try {
                const res = await axios.get('admin/check-in/search-member', {
                    params: { query: this.searchQuery }
                });
                if (res.data.status) {
                    this.processCheckIn(res.data.data);
                } else {
                    this.$toast.error(res.data.message);
                }
            } catch (err) {
                this.$toast.error('Lỗi khi tìm kiếm thành viên');
            } finally {
                this.isSaving = false;
            }
        },
        resetScan() {
            this.member = null;
            this.searchQuery = '';
        },
        sendZaloMsg(m) {
            this.$toast.info(`Đã gửi tin nhắn Zalo tới: ${m.name}`);
        },
        getZoneColor(p) { return p > 80 ? 'bg-danger' : (p > 50 ? 'bg-warning' : 'bg-primary'); },
        getZoneStatus(p) { return p > 80 ? 'Full' : (p > 50 ? 'Medium' : 'Available'); }
    }
};
</script>

<style scoped>
.camera-container {
    background-color: #000;
}
.video-wrapper {
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
}
.ai-badge {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: white;
}
.scanning-badge {
    padding: 6px 12px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    border-radius: 4px;
    color: #00f2fe;
    font-size: 0.75rem;
    font-weight: 700;
    border: 1px solid rgba(0,242,254,0.3);
}

.targeting-frame {
    width: 250px;
    height: 250px;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    opacity: 0.5;
}
.targeting-frame.detected {
    width: 280px;
    height: 280px;
    opacity: 1;
}
.targeting-frame.locked {
    border: 1px solid rgba(0, 242, 254, 0.2);
}

.corner {
    position: absolute;
    width: 30px;
    height: 30px;
    border: 3px solid #fff;
    transition: all 0.3s ease;
}
.targeting-frame.detected .corner {
    border-color: #00f2fe;
}
.top-left { top: 0; left: 0; border-right: none; border-bottom: none; }
.top-right { top: 0; right: 0; border-left: none; border-bottom: none; }
.bottom-left { bottom: 0; left: 0; border-right: none; border-top: none; }
.bottom-right { bottom: 0; right: 0; border-left: none; border-top: none; }

.frame-data {
    position: absolute;
    font-family: 'Courier New', monospace;
    font-size: 10px;
    color: rgba(255,255,255,0.7);
    text-transform: uppercase;
    white-space: nowrap;
}
.top-data { top: -20px; left: 0; }
.bottom-data { bottom: -20px; right: 0; color: #00f2fe; }

.scan-line-v2 {
    width: 100%;
    height: 1px;
    background: #00f2fe;
    position: absolute;
    top: 50%;
    box-shadow: 0 0 10px #00f2fe;
    animation: scanV2 1.5s infinite linear;
}

@keyframes scanV2 {
    0% { top: 0%; opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { top: 100%; opacity: 0; }
}

.animate-pulse {
    animation: pulse 1.5s infinite;
}
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.4; }
    100% { opacity: 1; }
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.control-panel {
    border-radius: 0 0 0.5rem 0.5rem;
}
</style>
