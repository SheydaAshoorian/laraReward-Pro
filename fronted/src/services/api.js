import axios from "axios";

const API = axios.create({
  baseURL: "http://127.0.0.1:8000/api", // آدرس پیش‌فرض پورت لاراول
  timeout: 10000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// این رهگیر (Interceptor) باعث می‌شود توکن کاربر به صورت خودکار روی تمام ریکوئست‌ها ست شود
API.interceptors.request.use((config) => {
  if (typeof window !== "undefined") {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
  }
  return config;
});

export default API;
