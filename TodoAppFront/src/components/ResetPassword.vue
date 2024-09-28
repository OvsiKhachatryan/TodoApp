<script setup>
import { ref } from 'vue';
import { useAuth } from '../composables/useAuth';

const { resetPassword, errors, successMessage } = useAuth();
const password = ref('');
const password_confirmation = ref('');
const token = new URLSearchParams(window.location.search).get('token');
const email = new URLSearchParams(window.location.search).get('email');

const passwordVisible = ref(false); // State for password visibility
const passwordConfirmationVisible = ref(false); // State for confirmation password visibility

const togglePasswordVisibility = () => {
    passwordVisible.value = !passwordVisible.value; // Toggle visibility
};

const togglePasswordConfirmationVisibility = () => {
    passwordConfirmationVisible.value = !passwordConfirmationVisible.value; // Toggle visibility
};

const submit = async () => {
    await resetPassword({
        token,
        password: password.value,
        password_confirmation: password_confirmation.value,
        email
    });

    // Clear the password fields after submission
    password.value = '';
    password_confirmation.value = '';
};
</script>

<template>
    <div class="main d-flex justify-content-center align-items-center">
        <div class="section d-flex justify-content-center align-items-center">
            <div class="auth-section mt-5">
                <h3 class="text-secondary text-center mt-5">Reset Password</h3>
                <form @submit.prevent="submit" class="p-3">
                    <div class="m-3 position-relative">
                        <img class="auth-icon" src="/assets/icons/password.png" alt="">
                        <input
                            v-model="password"
                            class="form-control input-area"
                            :type="passwordVisible ? 'text' : 'password'"
                            placeholder="New Password"
                        >
                        <button
                            type="button"
                            class="toggle-password"
                            @click="togglePasswordVisibility">
                            <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    <div class="m-3 position-relative">
                        <img class="auth-icon" src="/assets/icons/password.png" alt="">
                        <input
                            v-model="password_confirmation"
                            class="form-control input-area"
                            :type="passwordConfirmationVisible ? 'text' : 'password'"
                            placeholder="Confirm Password"
                        >
                        <button
                            type="button"
                            class="toggle-password"
                            @click="togglePasswordConfirmationVisibility">
                            <i :class="passwordConfirmationVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    <input type="hidden" v-model="token" />
                    <input type="hidden" v-model="email" />
                    <p class="error" v-if="errors">{{ errors }}</p>
                    <p class="successMessage" v-if="successMessage">{{ successMessage }}</p>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn sign-btn me-3">Reset Password</button>
                    </div>
                </form>
            </div>
            <div class="forgot-image-section"></div>
        </div>
    </div>
</template>

<style>
/* Add your styles for the toggle button here */
.toggle-password {
    position: absolute;
    right: 10px; /* Adjust position */
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #888; /* Change color as needed */
}
</style>
