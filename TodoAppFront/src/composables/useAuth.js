import axios from '../axios'; // Your Axios instance
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

export function useAuth() {
    const user = ref(null);
    const errors = ref(null);
    const successMessage = ref(null);
    const router = useRouter();

    const getUser = async () => {
        try {
            const response = await axios.get('/api/user', {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                }
            });
            user.value = response.data; // Assume user object includes 'name' field
        } catch (error) {
            console.error('Failed to fetch user', error);
            user.value = null;
        }
    };

    // Check if token exists and get the user on mounted
    onMounted(async () => {
        const token = localStorage.getItem('authToken');
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            await getUser(); // Fetch the user if token is available
        }
    });

    const login = async (credentials) => {
        try {
            errors.value = null; // Reset errors
            successMessage.value = null; // Reset success message

            const response = await axios.post('/api/login', credentials);
            const token = response.data.access_token;
            // Handle successful login
            localStorage.setItem('authToken', token);
            await getUser(); // Fetch user data if needed
            router.push('/dashboard');

        } catch (error) {
            console.error('Login Error:', error.response ? error.response.data : error.message);

            if (error.response && error.response.status === 403) {
                // errors.value = ['Please verify your email address.'];
                // Redirect to the verification URL
                window.location.href = error.response.data.redirect_url || '/verify-email'; // Fallback URL
            } else if (error.response && error.response.data && error.response.data.message) {
                errors.value = error.response.data.message; // Use the message from the response
            } else {
                errors.value = ['An error occurred. Please try again later.'];
            }
        }
    };

    // Handle registration
    const register = async (userData) => {
        errors.value = null; // Reset errors
        try {
            const response = await axios.post('/api/register', userData);
            const token = response.data.access_token;
            if (token) {
                localStorage.setItem('authToken', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            }
            // Fetch user after registration
            await getUser();

            // Redirect to the email verification component
            router.push('/verify-email');
        } catch (error) {
            console.error(error);
            if (error.response && error.response.data && error.response.data.message) {
                errors.value = error.response.data.message;
            } else {
                errors.value = ['An error occurred. Please try again later.'];
            }
        }
    };

    // Handle email verification
    const verifyEmail = async (verificationCode) => {
        errors.value = null; // Reset errors
        successMessage.value = null; // Reset success message

        try {
            const response = await axios.post('/api/verify-email', { verification_code: verificationCode });
            successMessage.value = 'Email verified successfully!'; // Success message
            router.push('/dashboard'); // Redirect after verification
        } catch (error) {
            console.error(error);
            if (error.response && error.response.data && error.response.data.message) {
                errors.value = error.response.data.message; // Use the message from the response
            } else {
                errors.value = ['An error occurred. Please try again later.'];
            }
        }
    };

    // Handle logout
    const logout = async () => {
        try {
            await axios.post('/api/logout', {}, {
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                }
            });
            user.value = null;
            localStorage.removeItem('authToken');
            axios.defaults.headers.common['Authorization'] = '';
            router.push('/login');
        } catch (error) {
            console.error('Failed to logout', error);
        }
    };

    // Handle forgot password
    const forgotPassword = async (email) => {
        errors.value = null; // Reset errors
        successMessage.value = null; // Reset success message
        try {
            await axios.post('/api/forgot-password', { email });
            successMessage.value = 'A password reset link has been sent to your email address.';
        } catch (error) {
            console.error(error);
            if (error.response && error.response.data && error.response.data.message) {
                errors.value = error.response.data.message;
            } else {
                errors.value = ['An error occurred. Please try again later.'];
            }
        }
    };

    // Handle password reset
    const resetPassword = async (data) => {
        errors.value = null;
        successMessage.value = null;

        try {
            const { email, token, password, password_confirmation } = data;
            await axios.post('/api/reset-password', { email, token, password, password_confirmation });
            successMessage.value = 'Password has been reset successfully. You can now log in with your new password.';
            router.push('/login');
        } catch (error) {
            if (error.response && error.response.status === 400) {
                const errorMessage = error.response.data.message || 'An error occurred';

                // if (errorMessage.includes('expired')) {
                //     router.push({ name: 'ResetExpired' }); // Redirect to expired link component
                // } else {
                //     errors.value = errorMessage;  // Show the error message for invalid token
                // }
            } else {
                errors.value = 'An error occurred. Please try again.';
            }
        }
    };

    return {
        user,
        errors,
        successMessage,
        getUser,
        login,
        register,
        verifyEmail,  // Expose the verifyEmail method
        logout,
        forgotPassword,
        resetPassword
    };
}
