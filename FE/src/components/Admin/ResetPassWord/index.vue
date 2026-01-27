<template>
    <div class="reset-pass">
        <div class="reset-container">
            <div class="logo">
                <div class="logo-icon">🏋️</div>
                <h2>GymManager</h2>
                <p>Fitness Center Management System</p>
            </div>

            <div class="reset-card">
                <a href="#" class="btn-back-top">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="text-title">Reset Password</h3>
                <span class="sub">Welcome back!</span>

                <form>
                    <div class="input-box password has-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input :type="showPassword ? 'text' : 'password'" placeholder="New password" />
                        <span @click="showPassword = !showPassword">
                            <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </span>
                    </div>

                    <div class="input-box password has-icon">
                        <i class="fa-solid fa-lock"></i>
                        <input :type="showPassword ? 'text' : 'password'" placeholder="Confirm password" />
                        <span @click="showPassword = !showPassword">
                            <i :class="showPassword ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </span>
                    </div>

                    <div class="lable-text">
                        <p>
                            <span style="color: red">*</span>
                            Please enter your new password and confirm the password once more
                        </p>
                    </div>
                    
                    <button type="button" class="btn-reset">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import './index.css'
export default {

}
</script>