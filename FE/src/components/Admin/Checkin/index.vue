<template>
    <div class="checkin-layout">

        <header class="top-bar">
            <div class="brand">
                <i class="fa-brands fa-hive text-neon"></i> WELLFIT <span class="text-neon">ACCESS CONTROL</span>
            </div>
            <div class="system-status">
                <span class="status-indicator online"></span>
                <span>INSIGHTFACE CORE: <strong>ACTIVE</strong></span>
            </div>
        </header>

        <div class="main-content">
            <div class="scan-panel">

                <div v-show="!member" class="camera-wrapper">
                    <div class="camera-feed">
                        <video ref="videoRef" autoplay playsinline muted></video>
                        <div class="scan-overlay">
                            <div class="scan-line"></div>
                            <div class="scan-text">DETECTING FACE...</div>
                            <div class="face-box"></div>
                        </div>
                    </div>

                    <div class="manual-input">
                        <input v-model="searchQuery" @keyup.enter="manualCheckIn" type="text"
                            placeholder="Nhập ID hoặc SĐT nếu FaceID lỗi...">
                        <button @click="simulateFaceID" class="btn-demo">
                            <i class="fa-solid fa-robot"></i> DEMO AI DETECT
                        </button>
                    </div>
                </div>

                <transition name="zoom">
                    <div v-if="member" class="profile-card">
                        <div class="card-header" :class="member.churnClass">
                            <div class="churn-badge">
                                <i class="fa-solid" :class="member.churnIcon"></i> {{ member.churnLabel }}
                            </div>
                            <div class="visit-counter">Check-in #{{ member.totalVisits }}</div>
                        </div>

                        <div class="card-body">
                            <div class="flex-row">
                                <img :src="member.avatar" class="avatar">
                                <div class="info">
                                    <h2 class="name">{{ member.name }}</h2>
                                    <p class="pack">{{ member.pack }} <span class="dot">•</span> EXP: {{ member.expiry
                                        }}</p>
                                    <p class="id-tag">ID: {{ member.id }}</p>
                                </div>
                            </div>

                            <div class="ai-box">
                                <div class="ai-title"><i class="fa-solid fa-microchip"></i> AI SUGGESTION</div>
                                <p class="ai-msg">{{ member.aiMessage }}</p>
                            </div>

                            <div class="stats-grid">
                                <div class="stat-item">
                                    <span class="label">Vắng mặt</span>
                                    <span class="value" :class="{ 'text-danger': member.daysAbsent > 7 }">{{
                                        member.daysAbsent }} ngày</span>
                                </div>
                                <div class="stat-item">
                                    <span class="label">Tần suất</span>
                                    <span class="value">{{ member.frequency }} buổi/tuần</span>
                                </div>
                            </div>

                            <div class="actions">
                                <button @click="resetScan" class="btn-secondary">
                                    <i class="fa-solid fa-arrow-rotate-left"></i> Quét tiếp
                                </button>
                                <button v-if="member.isChurnRisk" @click="sendZaloMsg(member)" class="btn-zalo">
                                    <i class="fa-solid fa-comment-dots"></i> Gửi Zalo CSKH
                                </button>
                                <button v-else class="btn-primary">
                                    <i class="fa-solid fa-door-open"></i> MỞ CỬA
                                </button>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>

            <div class="info-panel">
                <div class="widget">
                    <h3 class="w-title"><i class="fa-solid fa-users"></i> LƯỢT KHÁCH HÔM NAY</h3>
                    <div class="big-number">{{ checkInCount }}</div>
                    <div class="sub-text">Đang tập: <span class="text-neon">{{ activeNow }}</span></div>
                </div>

                <div class="widget">
                    <h3 class="w-title"><i class="fa-solid fa-layer-group"></i> TRẠNG THÁI KHU VỰC</h3>
                    <div class="zone-list">
                        <div v-for="zone in zones" :key="zone.name" class="zone-item">
                            <div class="z-header">
                                <span>{{ zone.name }}</span>
                                <span class="z-status" :class="getZoneColor(zone.percent)">{{
                                    getZoneStatus(zone.percent) }}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="fill" :class="getZoneColor(zone.percent)"
                                    :style="{ width: zone.percent + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget logs-widget">
                    <h3 class="w-title"><i class="fa-solid fa-clock-rotate-left"></i> VỪA CHECK-IN</h3>
                    <ul class="logs-list">
                        <li v-for="(log, i) in recentLogs" :key="i" class="log-item">
                            <span class="time">{{ log.time }}</span>
                            <span class="u-name">{{ log.name }}</span>
                        </li>
                    </ul>

                    <!-- LOGS PAGINATION -->
                    <div class="pagination-mini mt-2" v-if="logTotalPages > 1">
                        <button class="btn-mini" :disabled="logCurrentPage === 1" @click="changeLogPage(logCurrentPage - 1)">
                             <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <span>{{ logCurrentPage }} / {{ logTotalPages }}</span>
                        <button class="btn-mini" :disabled="logCurrentPage === logTotalPages" @click="changeLogPage(logCurrentPage + 1)">
                             <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { ref, reactive, onMounted, onBeforeUnmount } from 'vue';
import './index.css';
import axios from '@/axios';
import { useToast } from 'vue-toastification';

export default {
    name: 'SmartCheckIn',

    setup() {
        const toast = useToast();
        const searchQuery = ref('');
        const member = ref(null);
        const checkInCount = ref(0);
        const activeNow = ref(0);

        const videoRef = ref(null);
        let stream = null;

        const zones = reactive([
            { name: 'Khu Cardio (Tầng 1)', percent: 85 },
            { name: 'Free Weight (Tầng 2)', percent: 30 },
            { name: 'Yoga Studio', percent: 10 }
        ]);

        const recentLogs = ref([]);
        const logCurrentPage = ref(1);
        const logTotalPages = ref(1);
        const logTotalRecords = ref(0);

        const fetchLogs = async (page = 1) => {
            try {
                const res = await axios.get(`admin/check-in/get-data?page=${page}`);
                recentLogs.value = res.data.data.data.map(log => ({
                    time: new Date(log.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                    name: log.member.full_name
                }));
                logCurrentPage.value = res.data.data.current_page;
                logTotalPages.value = res.data.data.last_page;
                logTotalRecords.value = res.data.data.total;
                
                checkInCount.value = logTotalRecords.value;
                activeNow.value = Math.max(0, logTotalRecords.value - 2); // Mocking active count for now
            } catch (err) {
                console.error(err);
            }
        };

        const changeLogPage = (page) => {
            if (page >= 1 && page <= logTotalPages.value) {
                fetchLogs(page);
            }
        };

        const startCamera = async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 1280, height: 720 },
                    audio: false
                });
                if (videoRef.value) {
                    videoRef.value.srcObject = stream;
                }
            } catch (err) {
                toast.error('❌ Không mở được camera. Kiểm tra quyền trình duyệt!');
                console.error(err);
            }
        };

        const stopCamera = () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        };

        onBeforeUnmount(() => {
            stopCamera();
            if (detectInterval) clearInterval(detectInterval);
        });

        let detectInterval = null;
        let isDetecting = false;

        const startAutoDetection = () => {
            detectInterval = setInterval(async () => {
                if (isDetecting || member.value) return;
                
                const canvas = document.createElement('canvas');
                if (!videoRef.value) return;
                
                canvas.width = videoRef.value.videoWidth;
                canvas.height = videoRef.value.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(videoRef.value, 0, 0);
                
                const imageData = canvas.toDataURL('image/jpeg', 0.8);
                
                isDetecting = true;
                try {
                    const res = await axios.post('admin/check-in/recognize', { image: imageData });
                    if (res.data.status && res.data.data) {
                        const confidence = res.data.confidence || 0;
                        // Score requirement: 0.42 is our backend strict threshold
                        // We can add a high-confidence threshold for auto-success
                        if (confidence > 0.45) {
                            toast.success(`✅ Nhận diện: ${res.data.data.full_name} (${Math.round(confidence*100)}%)`);
                            processCheckIn(res.data.data);
                        } else {
                            console.log("Confidence too low for auto check-in", confidence);
                        }
                    }
                } catch (err) {
                    console.error("Face Recognition Service Error:", err);
                } finally {
                    isDetecting = false;
                }
            }, 2500); // Check every 2.5 seconds
        };

        onMounted(() => {
            startCamera();
            fetchLogs();
            startAutoDetection();
        });

        const analyzeMember = (rawMember) => {
            let result = {
                id: rawMember.id,
                name: rawMember.full_name,
                avatar: rawMember.avatar || 'https://ui-avatars.com/api/?name=' + rawMember.full_name,
                pack: rawMember.package_name,
                expiry: rawMember.end_date,
                daysAbsent: 0, // Should be calculated if needed
                frequency: (Math.random() * 5 + 1).toFixed(1),
                totalVisits: 0 // Should be fetched if needed
            };

            // Logic cảnh báo
            const daysLeft = Math.ceil((new Date(rawMember.end_date) - new Date()) / (1000 * 60 * 60 * 24));
            
            if (daysLeft > 7) {
                result.churnClass = 'safe-bg';
                result.churnIcon = 'fa-circle-check';
                result.churnLabel = 'THÀNH VIÊN TÍCH CỰC';
                result.aiMessage = "Gói tập còn hạn dài. Chúc bạn tập luyện vui vẻ!";
                result.isChurnRisk = false;
            } else if (daysLeft > 0) {
                result.churnClass = 'warning-bg';
                result.churnIcon = 'fa-triangle-exclamation';
                result.churnLabel = 'SẮP HẾT HẠN';
                result.aiMessage = `Gói tập chỉ còn ${daysLeft} ngày. Hãy gia hạn sớm!`;
                result.isChurnRisk = true;
            } else {
                result.churnClass = 'danger-bg';
                result.churnIcon = 'fa-skull-crossbones';
                result.churnLabel = 'ĐÃ HẾT HẠN';
                result.aiMessage = "Gói tập đã hết hạn. Vui lòng gia hạn để tiếp tục.";
                result.isChurnRisk = true;
            }
            return result;
        };

        const processCheckIn = async (user) => {
            try {
                const res = await axios.post('admin/check-in/add-data', {
                    member_id: user.id,
                    check_in_type: 'FaceID'
                });
                if (res.data.status) {
                    member.value = analyzeMember(user);
                    fetchLogs();
                }
            } catch (err) {
                toast.error('Lỗi khi ghi nhận check-in');
            }
        };

        const simulateFaceID = async () => {
            member.value = null;
            // Mocking a search for a random member to simulate FaceID
            try {
                const res = await axios.get('admin/thanh-vien/get-data');
                const list = res.data.data.data;
                if (list.length > 0) {
                    const randomUser = list[Math.floor(Math.random() * list.length)];
                    setTimeout(() => {
                        processCheckIn(randomUser);
                    }, 1000);
                } else {
                    toast.warning("Hệ thống chưa có thành viên nào");
                }
            } catch (err) {
                console.error(err);
            }
        };

        const manualCheckIn = async () => {
            try {
                const res = await axios.get('admin/check-in/search-member', {
                    params: { query: searchQuery.value }
                });
                if (res.data.status) {
                    processCheckIn(res.data.data);
                } else {
                    toast.error(res.data.message);
                }
            } catch (err) {
                toast.error('Lỗi khi tìm kiếm thành viên');
            }
        };

        const resetScan = () => {
            member.value = null;
        };

        const sendZaloMsg = (m) => {
            toast.info(`Đã gửi tin nhắn Zalo giữ chân tới: ${m.name}`);
        };

        const getZoneColor = (p) => p > 80 ? 'bg-danger' : (p > 50 ? 'bg-warning' : 'bg-success');
        const getZoneStatus = (p) => p > 80 ? 'Full' : (p > 50 ? 'Medium' : 'Empty');

        return {
            videoRef,
            searchQuery,
            member,
            checkInCount,
            activeNow,
            zones,
            recentLogs,
            logCurrentPage,
            logTotalPages,
            changeLogPage,
            simulateFaceID,
            manualCheckIn,
            resetScan,
            sendZaloMsg,
            getZoneColor,
            getZoneStatus
        };
    }
};
</script>