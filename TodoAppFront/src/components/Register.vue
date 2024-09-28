<script setup>
import { ref } from 'vue';
import { useAuth } from '../composables/useAuth';

const { register, errors } = useAuth();
const credentials = ref({ name: '', email: '', password: '' });
const passwordVisible = ref(false); // State for password visibility

const togglePasswordVisibility = () => {
    passwordVisible.value = !passwordVisible.value; // Toggle visibility
};

const submit = () => {
    register(credentials.value);
};
</script>

<template>
    <div class="main d-flex justify-content-center align-items-center">
        <div class="section d-flex justify-content-center align-items-center">
            <div class="auth-section mt-5">
                <h3 class="text-secondary text-center mt-5">Sign up</h3>
                <form @submit.prevent="submit" class="p-3">
                    <div class="m-3 position-relative">
                        <img class="auth-icon" src="/assets/icons/user.png" alt="">
                        <input
                            v-model="credentials.name"
                            class="form-control input-area"
                            type="text"
                            placeholder="Name">
                    </div>
                    <div class="m-3 position-relative">
                        <img class="auth-icon" src="/assets/icons/email.png" alt="">
                        <input
                            v-model="credentials.email"
                            class="form-control input-area"
                            type="text"
                            placeholder="Email address">
                    </div>
                    <div class="m-3 position-relative">
                        <img class="auth-icon" src="/assets/icons/password.png" alt="">
                        <input
                            v-model="credentials.password"
                            class="form-control input-area"
                            :type="passwordVisible ? 'text' : 'password'"
                            placeholder="Password">
                        <span
                            class="toggle-password"
                            @click="togglePasswordVisibility">
                            <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn sign-btn me-3">Sign up</button>
                    </div>
                    <p class="error" v-if="errors">{{ errors }}</p>
                    <div class="d-flex justify-content-center mt-4">
                        <p class="text-secondary me-2">Already have an account?</p>
                        <router-link class="auth-text text-decoration-none" to="/login">Sign in</router-link>
                    </div>
                </form>
            </div>
            <div class="image-section"></div>
        </div>
    </div>
</template>

<style scoped>
.position-relative {
    position: relative;
}

.toggle-password {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
}

.toggle-password i {
    font-size: 18px; /* Adjust size as needed */
}
</style>
