<script setup>
import {ref} from 'vue';
import {useAuth} from '../composables/useAuth'; // Adjust the path to your useAuth composable

const {forgotPassword, errors, successMessage} = useAuth();
const email = ref('');

const submit = async () => {
    const success = await forgotPassword(email.value);
    if (success) {
        email.value = ''; // Clear the email input field on success
    }
};
</script>
<template>
    <div class="main d-flex justify-content-center align-items-center">
        <div class="section d-flex justify-content-center align-items-center">
            <div class="auth-section mt-5">
                <h3 class="text-secondary text-center mt-5">Forgot password</h3>
                <form @submit.prevent="submit" class="p-3">
                    <div class="m-3 position-relative">
                        <img class="auth-icon" src="/assets/icons/email.png" alt="">
                        <input v-model="email" class="form-control input-area" type="text"
                               placeholder="Email address">
                    </div>
                    <p class="error" v-if="errors">{{ errors }}</p>
                    <p v-if="successMessage" class="successMessage">{{ successMessage }}</p>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn sign-btn me-3">Send Reset Link</button>
                    </div>
                </form>
            </div>
            <div class="forgot-image-section"></div>
        </div>
    </div>
</template>

<style scoped>

</style>