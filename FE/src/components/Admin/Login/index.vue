<template>
    <div class="login-container">
        <div class="login-header">
            <div class="logo-box">
                <i class="fa-solid fa-dumbbell"></i>
            </div>
            <h1>GymManager</h1>
            <p>Hệ thống quản lý phòng gym</p>
        </div>

        <div class="login-card">
            <h2>Đăng nhập</h2>
            <p class="welcome-text">Chào mừng bạn quay trở lại!</p>

            <form @submit.prevent="dangNhap" class="login-form">
                <div class="form-group">
                    <label>Email hoặc tên đăng nhập</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-envelope"></i>
                        <input 
                            v-model="thong_tin_dang_nhap.ten_dang_nhap" 
                            type="text" 
                            placeholder="Nhập email hoặc tên đăng nhập"
                            required
                        />
                    </div>
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock"></i>
                        <input 
                            v-model="thong_tin_dang_nhap.mat_khau" 
                            :type="showPassword ? 'text' : 'password'" 
                            placeholder="Nhập mật khẩu"
                            required
                        />
                        <i 
                            class="fa-regular password-toggle" 
                            :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"
                            @click="showPassword = !showPassword"
                        ></i>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" v-model="rememberMe">
                        <span class="checkmark"></span>
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="btn-login" :disabled="loading">
                    <span v-if="!loading">Đăng nhập</span>
                    <span v-else class="spinner"></span>
                </button>

                <p class="register-link">
                    Chưa có tài khoản? <a href="#">Đăng ký ngay</a>
                </p>

                <div class="demo-box">
                    Demo: Nhập bất kỳ thông tin nào để đăng nhập
                </div>
            </form>
        </div>

        <div class="login-footer">
            © 2024 GymManager. All rights reserved.
        </div>
    </div>
</template>

<script>


export default {
    name: 'Login',
    data() {
        return {
            thong_tin_dang_nhap: {
                ten_dang_nhap: '',
                mat_khau: ''
            },
            showPassword: false,
            rememberMe: false,
            loading: false
        }
    },
    methods: {
        dangNhap() {
            this.loading = true;
            axios.post('/admin/dang-nhap', this.thong_tin_dang_nhap)
                .then((res) => {
                    if (res.data.status) {
                        this.$toast.success(res.data.message || 'Đăng nhập thành công!');
                        localStorage.setItem('key_admin', res.data.token);
                        localStorage.setItem('user_info', JSON.stringify(res.data.user));
                        this.$router.push('/');
                    } else {
                        this.$toast.error(res.data.message || 'Thông tin đăng nhập không chính xác');
                    }
                })
                .catch((error) => {
                    console.error('Lỗi đăng nhập:', error);
                    const message = error.response?.data?.message || 'Không thể kết nối đến máy chủ';
                    this.$toast.error(message);
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    },
    mounted() {
        const token = localStorage.getItem('key_admin');
        if (token) {
            this.$router.push('/');
        }
    }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.login-container {
    width: 100%;
    min-height: 100vh;
    background-color: #f3f4f6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', sans-serif;
    padding: 20px;
    margin: 0;
}

.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.logo-box {
    width: 48px;
    height: 48px;
    background-color: #2563eb;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
}

.login-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 5px 0;
}

.login-header p {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.login-card {
    background-color: white;
    width: 100%;
    max-width: 420px;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.login-card h2 {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 8px 0;
}

.welcome-text {
    color: #6b7280;
    font-size: 14px;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-wrapper i:not(.password-toggle) {
    position: absolute;
    left: 14px;
    color: #9ca3af;
    font-size: 18px;
}

.input-wrapper input {
    width: 100%;
    padding: 12px 14px 12px 42px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.2s;
    outline: none;
}

.input-wrapper input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.password-toggle {
    position: absolute;
    right: 14px;
    color: #9ca3af;
    cursor: pointer;
    transition: color 0.2s;
}

.password-toggle:hover {
    color: #6b7280;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.checkbox-container {
    display: flex;
    align-items: center;
    font-size: 14px;
    color: #4b5563;
    cursor: pointer;
    user-select: none;
}

.checkbox-container input {
    margin-right: 8px;
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.forgot-password {
    font-size: 14px;
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.forgot-password:hover {
    text-decoration: underline;
}

.btn-login {
    width: 100%;
    padding: 12px;
    background-color: #2563eb;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-login:hover {
    background-color: #1d4ed8;
}

.btn-login:disabled {
    background-color: #93c5fd;
    cursor: not-allowed;
}

.register-link {
    text-align: center;
    font-size: 14px;
    color: #4b5563;
    margin-top: 25px;
}

.register-link a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
}

.register-link a:hover {
    text-decoration: underline;
}

.demo-box {
    margin-top: 25px;
    background-color: #f9fafb;
    border: 1px solid #f3f4f6;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    font-size: 12px;
    color: #6b7280;
}

.login-footer {
    margin-top: 40px;
    color: #9ca3af;
    font-size: 12px;
}

.spinner {
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Mobile Responsiveness */
@media (max-width: 480px) {
    .login-card {
        padding: 30px 20px;
        border-radius: 12px;
    }
    
    .login-header h1 {
        font-size: 24px;
    }
    
    .logo-box {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }
}
</style>