# 💻 پلتفرم هوشمند آنلاین - بخش فرانت‌اند (Frontend Client)

این مخزن شامل کد بیس سمت فرانت‌اند پلتفرم هوشمند آنلاین است که با استفاده از اکوسیستم مدرن **React** و فریم‌ورک قدرتمند **Next.js** به همراه **TypeScript** توسعه یافته است. طراحی رابط کاربری این پروژه با رویکرد Mobile-First به کمک **Tailwind CSS** انجام شده و لایه‌های امنیتی، هدایت کاربر و مدیریت استیت در بالاترین سطح استاندارد پیاده‌سازی شده‌اند.

---

## 🛠️ تک‌استک تخصصی (Frontend Tech Stack)

* **Core Framework:** Next.js 14+ (App Router) & React.js
* **Language:** TypeScript (توسعه تایپ‌پذیری امن و ماژولار)
* **Styling:** Tailwind CSS (Utility-First CSS) & Lucide React (Icons)
* **State Management:** Redux Toolkit (مدیریت استیت‌های عمومی و وضعیت کاربر) & Context API
* **Data Fetching:** Axios (به همراه Interceptorها جهت مدیریت توکن‌ها و خطاهای HTTP)
* **Form Management & Validation:** Formik & Yup

---

## 🔐 احراز هویت، سطوح دسترسی و هدایت کاربران (Auth, Authz & Redirection)

1. **احراز هویت مبتنی بر توکن (Token-Based Authentication):** پیاده‌سازی کامل چرخه Login، Register و Logout با استفاده از توکن‌های JWT صادر شده از سمت بک‌اند (Laravel). توکن‌ها به صورت امن مدیریت شده و با هر درخواست اپراتور، به صورت خودکار از طریق Axios Interceptors در هدر درخواست قرار می‌گیرند.
2. **مدیریت سطوح دسترسی (Authorization & Role Management):** کنترل دسترسی کاربران به بخش‌های مختلف پلتفرم بر اساس نقش‌ها (Roles) و مجوزها (Permissions) که از API دریافت و در دیتای سراسری استور ذخیره می‌شوند.
3. **محافظت از مسیرها و هدایت هوشمند (Route Guarding & Redirection):** 
   * بهره‌گیری از **Next.js Middleware** برای بررسی وضعیت سشن کاربر در لایه سرور پیش از رندر شدن صفحه.
   * هدایت خودکار کاربران احراز هویت نشده (Guest) از صفحات محافظت‌شده (مانند Dashboard) به صفحه Login.
   * جلوگیری از دسترسی کاربران لاگین‌شده به صفحات احراز هویت (مانند Login/Register) و ردایرکت مستقیم آن‌ها به پنل کاربری.
4. **سشن‌های پایدار (Persistent Sessions):** همگام‌سازی وضعیت احراز هویت کاربر با Redux Store به طوری که با Refresh کردن صفحه، سشن کاربر از بین نرود.

---

## ✨ ویژگی‌های فنی کلیدی (Key Technical Features)

* **رندرینگ ترکیبی (SSR & CSR):** بهره‌گیری از قابلیت‌های Server-Side Rendering برای صفحات عمومی جهت بهبود سئو (SEO) و Client-Side Rendering برای پنل‌های تعاملی.
* **مدیریت استیت یکپارچه:** استفاده از Redux Toolkit برای همگام‌سازی داده‌های پروفایل کاربر، سیستم‌های ماژولار و دیتای سراسری.
* **اتصال به سرویس‌های هوش مصنوعی:** طراحی کامپوننت‌های تعاملی (تکست‌باکس‌ها و ماژول‌های چت) برای برقراری ارتباط بلادرنگ با APIهای هوش مصنوعی متصل به بک‌اند.
* **فرم‌های هوشمند و بهینه:** پیاده‌سازی فرم‌های ورود و ثبت‌نام با Formik و اعمال Validation Schemaهای پویا با Yup.

---

## 📂 ساختار پوشه‌بندی پروژه (Architecture & Folder Structure)

```text
src/
├── app/                  # Next.js App Router (Pages & Layouts)
├── components/           # کامپوننت‌های عمومی و اتمیک
├── context/              # Context API برای استیت‌های سبک
├── hooks/                # Custom Hooks اختصاصی (مانند useAuth)
├── middleware.ts         # مدیریت ردایرکت‌ها و محافظت از مسیرها (Route Guards)
├── services/             # کانفیگ Axios و متدهای ارتباط با API (Auth Services)
├── store/                # Redux Toolkit (Auth Slices & App Store)
├── types/                # اینترفیس‌ها و تایپ‌های منسجم TypeScript
└── utils/                # توابع کمکی و فرمت‌کننده‌ها (Helpers)
