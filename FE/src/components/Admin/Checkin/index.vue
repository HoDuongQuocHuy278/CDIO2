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

                <div v-if="!member" class="camera-wrapper">
                    <div class="camera-feed">
                        <img src="https://images.unsplash.com/photo-1599058945522-28d584b6f0ff?q=80&w=2069&auto=format&fit=crop"
                            alt="CCTV">
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
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { ref, reactive, onMounted } from 'vue';
import './index.css'

export default {
    name: 'SmartCheckIn',

    // Dùng setup() để quản lý biến tập trung, dễ return
    setup() {
        // --- 1. STATE (KHAI BÁO BIẾN) ---
        const searchQuery = ref('');
        const member = ref(null);
        const checkInCount = ref(142);
        const activeNow = ref(45);

        const zones = reactive([
            { name: 'Khu Cardio (Tầng 1)', percent: 85 },
            { name: 'Free Weight (Tầng 2)', percent: 30 },
            { name: 'Yoga Studio', percent: 10 }
        ]);

        const recentLogs = reactive([
            { time: '17:30', name: 'Phạm Văn Nam' },
            { time: '17:28', name: 'Lê Thị Hoa' },
            { time: '17:25', name: 'Trần Minh Đức' }
        ]);

        // MOCK DATA (Thay thế bằng API Call sau này)
        const mockDB = [
            { id: 'WF-101', name: 'Nguyễn Văn An', avatar: 'https://randomuser.me/api/portraits/men/32.jpg', pack: 'Diamond Member', expiry: '12/2026', lastVisitDays: 1, visits: 105 },
            { id: 'WF-102', name: 'Trần Thị Bình', avatar: 'https://randomuser.me/api/portraits/women/44.jpg', pack: 'Yoga Basic', expiry: '05/2026', lastVisitDays: 12, visits: 12 },
            { id: 'WF-103', name: 'Lê Hùng', avatar: 'https://randomuser.me/api/portraits/men/11.jpg', pack: 'Student Pack', expiry: '02/2026', lastVisitDays: 35, visits: 4 }
        ];

        // --- 2. BUSINESS LOGIC (CHURN PREDICTION) ---
        const analyzeMember = (rawMember) => {
            let result = { ...rawMember };
            result.daysAbsent = rawMember.lastVisitDays;
            result.frequency = (Math.random() * 5 + 1).toFixed(1);
            result.totalVisits = rawMember.visits + 1;

            // Logic 3-Day Rule (Quan trọng cho đồ án)
            if (result.daysAbsent <= 3) {
                result.churnClass = 'safe-bg';
                result.churnIcon = 'fa-circle-check';
                result.churnLabel = 'THÀNH VIÊN TÍCH CỰC';
                result.aiMessage = "Khách hàng duy trì tập luyện tốt. Đề xuất: Mời thử nước uống mới.";
                result.isChurnRisk = false;
            } else if (result.daysAbsent <= 14) {
                result.churnClass = 'warning-bg';
                result.churnIcon = 'fa-triangle-exclamation';
                result.churnLabel = 'CẦN QUAN TÂM (RISK)';
                result.aiMessage = `Đã vắng ${result.daysAbsent} ngày. Đề xuất: Lễ tân hỏi thăm nhẹ nhàng về công việc/sức khỏe.`;
                result.isChurnRisk = true;
            } else {
                result.churnClass = 'danger-bg';
                result.churnIcon = 'fa-skull-crossbones';
                result.churnLabel = 'NGUY CƠ BỎ TẬP CAO';
                result.aiMessage = "CẢNH BÁO ĐỎ: Khách có nguy cơ hủy gói. Hành động: Tặng Voucher, PT ra hướng dẫn ngay.";
                result.isChurnRisk = true;
            }
            return result;
        };

        // --- 3. METHODS (XỬ LÝ SỰ KIỆN) ---

        // Xử lý khi có dữ liệu khách (từ Camera hoặc nhập tay)
        const processCheckIn = (user) => {
            member.value = analyzeMember(user);

            // Log lại
            const now = new Date();
            const timeStr = `${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`;
            recentLogs.unshift({ time: timeStr, name: user.name });
            if (recentLogs.length > 5) recentLogs.pop();

            checkInCount.value++;
            activeNow.value++;
            searchQuery.value = '';
        };

        // [TODO] Hàm này sau này sẽ gọi API Check Face
        const simulateFaceID = () => {
            // Reset trạng thái để tạo hiệu ứng "Đang quét lại"
            member.value = null;

            console.log("Connecting to Camera RTSP...");
            // TODO: Kết nối WebSocket hoặc API tại đây
            // const response = await axios.post('/api/face-recognize');

            // Giả lập độ trễ mạng 1.5s
            setTimeout(() => {
                const randomUser = mockDB[Math.floor(Math.random() * mockDB.length)];
                processCheckIn(randomUser);
            }, 1500);
        };

        const manualCheckIn = () => {
            const found = mockDB.find(u => u.id.includes(searchQuery.value) || u.name.includes(searchQuery.value));
            if (found) processCheckIn(found);
            else alert('Không tìm thấy thành viên!');
        };

        const resetScan = () => {
            member.value = null;
        };

        const sendZaloMsg = (m) => {
            // TODO: Gọi Zalo OA API
            // await axios.post('/api/zalo/send', { phone: m.phone, msg: m.aiMessage });
            alert(`[API CALL] Đã gửi tin nhắn Zalo giữ chân khách hàng tới: ${m.name}`);
        };

        // Helper Functions cho UI
        const getZoneColor = (p) => p > 80 ? 'bg-danger' : (p > 50 ? 'bg-warning' : 'bg-success');
        const getZoneStatus = (p) => p > 80 ? 'Full' : (p > 50 ? 'Medium' : 'Empty');

        // --- 4. RETURN (TRẢ BIẾN VỀ TEMPLATE) ---
        return {
            searchQuery,
            member,
            checkInCount,
            activeNow,
            zones,
            recentLogs,
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