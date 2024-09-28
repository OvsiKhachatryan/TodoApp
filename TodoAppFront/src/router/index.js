import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import Dashboard from "../components/Dashboard.vue";
import { isAuthenticated } from "../utils/auth.js";
import ForgotPassword from "../components/ForgotPassword.vue";
import ResetPassword from "../components/ResetPassword.vue";
import ResetExpired from "../components/ResetExpired.vue";
import EmailVerification from "../components/EmailVerification.vue";

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login,
        beforeEnter: async (to, from, next) => {
            const isAuth = await isAuthenticated();
            if (isAuth) {
                next('/dashboard'); // Redirect to dashboard if already authenticated
            } else {
                next(); // Allow access to login
            }
        },
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        beforeEnter: async (to, from, next) => {
            const isAuth = await isAuthenticated();
            if (isAuth) {
                next('/dashboard'); // Redirect to dashboard if already authenticated
            } else {
                next(); // Allow access to register
            }
        },
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        beforeEnter: async (to, from, next) => {
            const isAuth = await isAuthenticated();
            if (!isAuth) {
                next('/login'); // Redirect to login if not authenticated
            } else {
                next(); // Allow access to dashboard
            }
        },
    },
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: ForgotPassword
    },
    {
        path: '/reset-password',
        name: 'ResetPassword',
        component: ResetPassword
    },
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/verify-email',
        name: 'EmailVerification',
        component: EmailVerification
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
