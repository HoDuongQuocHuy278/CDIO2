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
                        <input type="text" placeholder="Enter your email or username" />
                    </div>

                    <div class="input-box password has-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Enter your password" />
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

                    <button type="button" class="btn-login">
                        Sign In
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