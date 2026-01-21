<template>
    <div class="login-page">
        <div class="login-container">
            <!-- Logo -->
            <div class="logo">
                <div class="logo-icon">🏋️</div>
                <h2>GymManager</h2>
                <p>Hệ thống quản lý phòng gym</p>
            </div>

            <!-- Card -->
            <div class="login-card">
                <h3 class="text-title">Đăng nhập</h3>
                <span class="sub">Chào mừng bạn quay trở lại!</span>

                <form>
                    <label>Email hoặc tên đăng nhập</label>
                    <div class="input-box has-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="text" placeholder="Nhập email hoặc tên đăng nhập" />
                    </div>


                    <label>Mật khẩu</label>
                    <div class="input-box password has-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Nhập mật khẩu" />
                        <span @click="showPassword = !showPassword">
                            <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </span>
                    </div>


                    <div class="options">
                        <label>
                            <input type="checkbox" />
                            Ghi nhớ đăng nhập
                        </label>
                        <a href="#">Quên mật khẩu?</a>
                    </div>

                    <button type="button" class="btn-login">
                        Đăng nhập
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import './index.css'


export default {
    data() {
        return {
            showPassword: false
        }
    }
}
</script>