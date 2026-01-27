<template>
    <div class="forgot-pass">
        <div class="forgot-container">
            <div class="logo">
                <div class="logo-icon">🏋️</div>
                <h2>GymManager</h2>
                <p>Fitness Center Management System</p>
            </div>

            <div class="forgot-card">
                <h3 class="text-title">Reset Password</h3>
                <span class="sub">Welcome back!</span>

                <form>
                    <div class="input-box has-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="text" placeholder="Enter your email address" />
                    </div>

                    <div class="lable-text">
                        <p>
                            <span style="color: red">*</span>
                            You will send a message to set or reset your new password
                        </p>
                    </div>
                    
                    <button type="button" class="btn-forgot">
                        Submit
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