'use client';
import { createTheme } from '@mui/material/styles';

const theme = createTheme({
  direction: 'rtl', // فعال‌سازی جهت راست‌چین در کل کامپوننت‌های MUI
  palette: {
    primary: {
      main: '#1976d2', // رنگ اصلی سیستم
    },
    secondary: {
      main: '#9c27b0',
    },
    background: {
      default: '#f5f5f5', // رنگ پس‌زمینه صفحات
    },
  },
  typography: {
    fontFamily: 'Tahoma, Arial, sans-serif', // تنظیم فونت پیش‌فرض مناسب فارسی
  },
  components: {
    // شخصی‌سازی کامپوننت‌ها در صورت نیاز در سطح کلان
  },
});

export default theme;