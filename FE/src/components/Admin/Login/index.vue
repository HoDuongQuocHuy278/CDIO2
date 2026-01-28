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

                    <button v-on:click="DangNhap()" type="button" class="btn-login">
                        Sign In
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { createToaster } from "@meforma/vue-toaster";
const toaster = createToaster({ position: "top-right" });
import './index.css'
import baseRequestAdmin from "@/core/baseRequestAdmin";

export default {
    data() {
        return {
            user: {
                so_dien_thoai: '',
                password: ''
            },
            showPassword: false,
        }
    },
    methods: {
        DangNhap() {
            baseRequestAdmin.post('admin/login', this.user)
                .then((res) => {
                    if (res.data.status) {
                        toaster.success(res.data.message)
                        this.user = {};
                        localStorage.setItem('token_admin',res.data.token);
                        this.$router.push('/admin/dashboard');
                    } else {
                        toaster.error(res.data.message);
                    }
                })
                .catch((err) => {
                    const listErr = err.response.data.errors;
                    Object.values(listErr).forEach((error) => {
                            toaster.error(error[0]);
                    });
                });
        }
        
    }
}
</script>