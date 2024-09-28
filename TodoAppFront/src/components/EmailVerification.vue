<script setup>
import { ref } from 'vue';
import { useAuth } from '../composables/useAuth';

// Import methods and state from the useAuth composable
const { verifyEmail, errors, successMessage } = useAuth();

// Reactive reference for the verification code
const verificationCode = ref('');

// Function to handle email verification
const verifyUserEmail = async () => {
    await verifyEmail(verificationCode.value); // Send only the verification code
};
</script>

<template>
    <div class="main d-flex justify-content-center align-items-center">
        <div class="section d-flex justify-content-center align-items-center">
            <div class="auth-section mt-5">
                <h3 class="text-secondary text-center mt-5">Verify Your Email</h3>
                <form @submit.prevent="verifyUserEmail" class="p-3">
                    <div class="m-3 position-relative">
                        <img class="auth-icon" src="/assets/icons/check.png" alt="verification code icon">
                        <input
                            v-model="verificationCode"
                            class="form-control input-area"
                            type="text"
                            placeholder="Enter verification code"
                            required
                        >
                    </div>
                    <p class="error" v-if="errors">{{ errors }}</p>
                    <p class="successMessage" v-if="successMessage">{{ successMessage }}</p>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn sign-btn me-3">Verify Email</button>
                    </div>
                </form>
            </div>
            <div class="forgot-image-section"></div>
        </div>
    </div>
</template>

<style scoped>

.forgot-image-section {
    background-image: url('/assets/verification.jpg');
    background-size: cover;
    width: 100%;
    height: 100%;
    border-radius: 10px;
}
</style>
