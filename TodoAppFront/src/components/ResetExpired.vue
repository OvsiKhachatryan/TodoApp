<template>
    <div>
        <h1>Password Reset Link Expired</h1>
        <p>Your password reset link has expired. Please request a new one.</p>
        <router-link to="/forgot-password">Request New Password Reset Link</router-link>
    </div>
</template>

<script>
export default {
    name: 'ResetExpired'
};
</script>