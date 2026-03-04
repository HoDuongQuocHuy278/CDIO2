<template>
    <div class="login-page">
        <div class="login-container">
            <!-- Logo -->
            <div class="logo">
                <div class="logo-icon">🏋️</div>
                <h2>GymManager</h2>
                <p>Fitness Center Management System</p>
            </div>

            <!-- Card -->
            <div class="login-card">
                <h3 class="text-title">Sign In</h3>
                <span class="sub">Welcome back!</span>

                <form>
                    <div class="input-box has-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input v-model="user.so_dien_thoai" type="text" placeholder="Enter your email or so dien thoai" />
                    </div>

                    <div class="input-box password has-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input v-model="user.password" :type="showPassword ? 'text' : 'password'" placeholder="Enter your password" />
                        <span @click="showPassword = !showPassword">
                            <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </span>
                    </div>


                    <div class="options">
                        <label>
                            <input type="checkbox" />
                            Remember me
                        </label>
                        <a href="#">Forgot Password?</a>
                    </div>

                    <div class="d-grid mt-4">
                        <button :disabled="isLoading" @click="dangNhap()" type="button" class="btn btn-primary btn-lg custom-btn">
                            <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
                            {{ isLoading ? 'Đang xác thực...' : 'Sign In' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '@/axios';

export default {
    data() {
        return {
            user: {
                so_dien_thoai: '',
                password: ''
            },
            isLoading: false,
            showPassword: false
        }
    },
    methods: {
        dangNhap() {
            if (!this.user.so_dien_thoai || !this.user.password) {
                this.$toast.error("Vui lòng nhập đầy đủ thông tin!");
                return;
            }

            this.isLoading = true;
            console.log("Attempting login for:", this.user.so_dien_thoai);
            
            axios
                .post('admin/login', this.user)
                .then((res) => {
                    console.log("Login Response:", res.data);
                    if (res.data.status) {
                        try {
                            if (this.$toast) {
                                this.$toast.success(res.data.message);
                            }
                            
                            // Save token first
                            localStorage.setItem('key_admin', res.data.token);
                            localStorage.setItem('ho_ten_admin', res.data.admin.ho_ten);
                            localStorage.setItem('hinh_anh_admin', res.data.admin.hinh_anh);
                            
                            console.log("Token and user info saved to LocalStorage");
                            
                            setTimeout(() => {
                                console.log("Redirecting to dashboard...");
                                this.$router.push('/admin/dashboard');
                            }, 500);
                        } catch (e) {
                            console.error("Error in login success handler:", e);
                            // Fallback redirection if JS crashes but token is saved
                            window.location.href = '/admin/dashboard';
                        }
                    } else {
                        if (this.$toast) {
                            this.$toast.error(res.data.message);
                        } else {
                            alert(res.data.message);
                        }
                    }
                })
                .catch((err) => {
                    console.error("Login API Error:", err);
                    const message = err.response?.data?.message || "Đã có lỗi xảy ra. Vui lòng thử lại!";
                    if (this.$toast) {
                        this.$toast.error(message);
                    } else {
                        alert(message);
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                });
        }
    }
}
</script>
<style>
@import './index.css';
</style>
