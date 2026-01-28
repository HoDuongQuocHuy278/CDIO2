<template>
    <aside class="sidebar">
        <div class="logo-container">
            <div class="logo-icon">
                <i class='bx bxs-dumbbell'></i>
            </div>
            <div class="logo-text">
                <h1 class="title">GymManager</h1>
                <span class="sub">Administrator</span>
            </div>
        </div>

        <nav class="menu">
            <p class="menu-label">Menu</p>

            <router-link to="/admin/dashboard" class="menu-item">
                <i class='bx bxs-dashboard'></i>
                <span>Tổng quan</span>
            </router-link>

            <router-link to="/admin/members" class="menu-item">
                <i class='bx bxs-user-detail'></i>
                <span>Thành viên</span>
            </router-link>

            <router-link to="/admin/checkin" class="menu-item">
                <i class='bx bx-qr-scan'></i>
                <span>Check-in</span>
            </router-link>

            <router-link to="/admin/services" class="menu-item">
                <i class='bx bxs-layer'></i>
                <span>Dịch vụ</span>
            </router-link>

            <p class="menu-label">Quản lý</p>

            <router-link to="/admin/staff" class="menu-item">
                <i class='bx bxs-id-card'></i>
                <span>Nhân viên</span>
            </router-link>

            <router-link to="/admin/devices" class="menu-item">
                <i class='bx bx-dumbbell'></i>
                <span>Thiết bị</span>
            </router-link>

            <router-link to="/admin/products" class="menu-item">
                <i class='bx bxs-shopping-bag'></i>
                <span>Sản phẩm</span>
            </router-link>

            <router-link to="/admin/invoices" class="menu-item">
                <i class='bx bxs-file-doc'></i>
                <span>Hóa đơn</span>
            </router-link>
        </nav>

        <div class="sidebar-footer">
            <div class="logout-btn">
                <i class='bx bx-log-out'></i>
                <span>Đăng xuất</span>
            </div>
        </div>
    </aside>
</template>

<style scoped>
.sidebar {
    width: 280px;
    /* Rộng hơn xíu cho thoáng */
    height: 100vh;
    background: #ffffff;
    border-right: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    font-family: 'Inter', sans-serif;
    box-shadow: 4px 0 24px rgba(0, 0, 0, 0.02);
    position: sticky;
    top: 0;
}

/* --- LOGO --- */
.logo-container {
    padding: 32px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.logo-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    box-shadow: 0 8px 16px rgba(37, 99, 235, 0.2);
}

.title {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    line-height: 1.2;
}

.sub {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}

/* --- MENU --- */
.menu {
    padding: 0 16px;
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.menu::-webkit-scrollbar {
    width: 4px;
}

.menu::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 4px;
}

.menu-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #94a3b8;
    font-weight: 600;
    margin: 24px 0 8px 12px;
}

.menu-label:first-child {
    margin-top: 0;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 16px;
    border-radius: 12px;
    text-decoration: none;
    color: #64748b;
    font-weight: 500;
    font-size: 15px;
    transition: all 0.2s ease-in-out;
    position: relative;
}

.menu-item i {
    font-size: 22px;
    transition: transform 0.2s ease;
}

/* Hover Effect */
.menu-item:hover {
    background: #f8fafc;
    color: #2563eb;
    transform: translateX(4px);
}

.menu-item:hover i {
    transform: scale(1.1);
}

/* Active State (Vue Router tự thêm class router-link-active) */
.menu-item.router-link-active {
    background: #eff6ff;
    color: #2563eb;
    font-weight: 600;
}

.menu-item.router-link-active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    height: 20px;
    width: 4px;
    background: #2563eb;
    border-radius: 0 4px 4px 0;
}

/* --- FOOTER / LOGOUT --- */
.sidebar-footer {
    padding: 24px;
    border-top: 1px solid #f1f5f9;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 12px;
    color: #64748b;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
    border: 1px solid transparent;
}

.logout-btn:hover {
    background: #fef2f2;
    color: #ef4444;
    border-color: #fee2e2;
}
</style>