<template>
    <div class="otp-pass">
        <div class="otp-container">
            <div class="logo">
                <div class="logo-icon">🏋️</div>
                <h2>GymManager</h2>
                <p>Fitness Center Management System</p>
            </div>

            <div class="otp-card">
                <h3 class="text-title">Enter OTP code</h3>
                <span class="sub">Welcome back!</span>

                <form>
                    <div class="input-box has-icon">
                        <i class="fa-regular fa-envelope"></i>
                        <input type="text" placeholder="Enter OTP code" />
                    </div>

                    <div class="lable-text">
                        <p>
                            <span style="color: red">*</span>
                            You need to check your email for the OTP code to confirm
                        </p>
                    </div>
                    
                    <button type="button" class="btn-otp">
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