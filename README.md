# 🚀 پلتفرم هوشمند آنلاین (Smart Web Application)

این پروژه یک پلتفرم تحت وب مدرن و مقیاس‌پذیر است که با معماری تفکیک‌شده (Decoupled Architecture) توسعه یافته است. بخش بک‌اند پروژه با فریم‌ورک **Laravel** به عنوان یک RESTful API مستحکم عمل می‌کند و بخش فرانت‌اند آن با **Next.js** و **TypeScript** به صورت کاملاً داینامیک و بهینه پیاده‌سازی شده است. همچنین این پلتفرم مجهز به ماژول‌های هوش مصنوعی (AI-Powered) جهت خودکارسازی فرآیندها می‌باشد.

---

## 🛠️ تک‌استک و تایتل‌های فنی (Tech Stack)

### بک‌اند (Backend)
* **Framework:** Laravel 10+ / PHP 8.2+
* **Database:** MySQL (طراحی بهینه و ریلیشنال)
* **Architecture:** MVC / RESTful API Design
* **Authentication:** Secure API Authentication (Sanctum/JWT)

### فرانت‌اند (Frontend)
* **Core:** React.js & Next.js (App Router / SSR & CSR)
* **Language:** TypeScript (توسعه کامپوننت‌ها با تایپ‌پذیری امن)
* **Styling:** Tailwind CSS (طراحی کاملاً Responsive و Utility-First)
* **State Management:** Redux Toolkit / Context API
* **Data Fetching:** Axios (مدیریت درخواست‌ها و Interceptorها)
* **Forms & Validation:** Formik & Yup

### ابزارها و هوش مصنوعی (Tools & AI Integration)
* **AI Engine:** Integration of OpenAI API (ChatGPT) / Prompt Engineering
* **Version Control:** Git & GitHub

---

## ✨ ویژگی‌های کلیدی پروژه (Key Features)

1. **معماری ماژولار و تمیز:** تفکیک کامل لایه فرانت‌اند و بک‌اند جهت افزایش سرعت توسعه و مقیاس‌پذیری زیرساخت.
2. **اتصال هوشمند به AI:** مجهز به ماژول پردازش اطلاعات و خودکارسازی تسک‌ها با استفاده از APIهای زبان‌های بزرگ (LLMs).
3. **مدیریت استیت پیشرفته:** هماهنگی کامل داده‌ها میان کامپوننت‌های مختلف فرانت‌اند بدون رندر اضافه، با کمک Redux Toolkit.
4. **فرم‌های بهینه و امن:** پیاده‌سازی سیستم‌های ورود اطلاعات و ثبت‌نام با اعتبارسنجی‌های سخت‌گیرانه سمت فرانت‌اند (Yup) و بک‌اند (Laravel Validation).
5. **پایگاه داده بهینه‌شده:** ساختار ریلیشنال دیتابیس MySQL با کوئری‌های بهینه جهت پاسخ‌گویی سریع به درخواست‌های فرانت‌اند.

---

## 🚀 راه‌اندازی پروژه (Installation & Setup)

### بخش بک‌اند (Laravel)
۱. وارد پوشه بک‌اند شوید، پکیج‌ها را نصب کرده و فایل محیطی را تنظیم کنید:
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
