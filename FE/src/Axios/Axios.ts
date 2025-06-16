import axios from 'axios';

const apiClient = axios.create({
    baseURL: 'http://localhost:8000/api', // Adjust the base URL as needed
    headers: {
        'Content-Type': 'application/json',
    },
});

// Interceptors for request and response
apiClient.interceptors.request.use(
    (config) => {
        // You can add authorization token or other headers here
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

apiClient.interceptors.response.use(
    (response) => {
        return response.data; // Return only the data part of the response
    },
    (error) => {
        // Handle errors globally
        return Promise.reject(error);
    }
);

export default apiClient;