import axios from "axios";

// Tạo một instance của axios
const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
});

// Sử dụng interceptor để chèn token vào mỗi yêu cầu
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers["Authorization"] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default api;
