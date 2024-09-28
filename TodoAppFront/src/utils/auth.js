import axios from '../axios';

export async function isAuthenticated() {
    try {
        const token = localStorage.getItem('authToken'); // Get the token from local storage
        if (!token) return false; // If there's no token, the user is not authenticated

        // Optionally, make an API call to validate the token
        await axios.get('/api/user', {
            headers: {
                'Authorization': `Bearer ${token}`, // Include the token in the request header
            },
        });

        return true; // If the request succeeds, the user is authenticated
    } catch (error) {
        return false; // If there's an error (e.g., 401 Unauthorized), the user is not authenticated
    }
}
