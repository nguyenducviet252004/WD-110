// src/utils/axiosConfig.ts
import axios from 'axios';

// Tạo một instance Axios với cấu hình mặc định
const axiosInstance = axios.create({
  baseURL: 'http://127.0.0.1:8000/api', // Đặt URL cơ sở cho API của bạn
  headers: {
    'Content-Type': 'application/json',
  },
});

// Thêm interceptor nếu cần
axiosInstance.interceptors.request.use(
  (config) => {
    // Có thể thêm token hoặc làm gì đó trước khi gửi request
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Xuất instance Axios để sử dụng ở nơi khác
export default axiosInstance;
