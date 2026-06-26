# 💻 پلتفرم هوشمند آنلاین - بخش فرانت‌اند (Frontend Client)

این مخزن شامل کد بیس سمت فرانت‌اند پلتفرم هوشمند آنلاین است که با استفاده از اکوسیستم مدرن **React** و فریم‌ورک قدرتمند **Next.js** به همراه **TypeScript** توسعه یافته است. طراحی رابط کاربری این پروژه با رویکرد Mobile-First و به کمک **Tailwind CSS** انجام شده و مدیریت داده‌ها و ارتباط با API بک‌اند (Laravel) کاملاً بهینه‌سازی شده است.

---

## 🛠️ تک‌استک تخصصی (Frontend Tech Stack)

* **Core Framework:** Next.js 14+ (App Router) & React.js
* **Language:** TypeScript (توسعه تایپ‌پذیری امن و ماژولار)
* **Styling:** Tailwind CSS (Utility-First CSS) & Lucide React (Icons)
* **State Management:** Redux Toolkit (مدیریت استیت‌های عمومی) & Context API (استیت‌های موضعی)
* **Data Fetching:** Axios (به همراه Interceptorها جهت مدیریت توکن‌های JWT و خطاهای HTTP)
* **Form Management:** Formik
* **Validation:** Yup (اعتبارسنجی پیشرفته لایه فرانت‌اند)

---

## ✨ ویژگی‌های فنی کلیدی (Key Technical Features)

1. **رندرینگ ترکیبی (SSR & CSR):** بهره‌گیری از قابلیت‌های Server-Side Rendering برای صفحات استاتیک/داینامیک جهت بهبود سئو (SEO) و Client-Side Rendering برای بخش‌های تعاملی پنل کاربری.
2. **مدیریت استیت یکپارچه:** استفاده از Redux Toolkit برای همگام‌سازی داده‌های کاربر، سبد خرید/فرم‌ها و استیت‌های سراسری بدون رندرهای اضافه (Re-rendering).
3. **اتصال به سرویس‌های هوش مصنوعی:** طراحی کامپوننت‌های تعاملی (تکست‌باکس‌ها و ماژول‌های چت) برای برقراری ارتباط بلادرنگ با APIهای هوش مصنوعی (LLMs) متصل به بک‌اند.
4. **فرم‌های هوشمند و بهینه:** پیاده‌سازی سیستم‌های ورود، ثبت‌نام و فرم‌های چندمرحله‌ای پیچیده با Formik و اعمال Validation Schemaهای پویا با Yup.
5. **معماری کامپوننت‌محور (Component-Driven):** طراحی کامپوننت‌های اتمیک با قابلیت استفاده مجدد (Atomic Reusable Components) جهت افزایش خوانایی و نگهداری آسان‌تر کدبیس.

---

## 📂 ساختار پوشه‌بندی پروژه (Architecture & Folder Structure)

```text
src/
├── app/                  # Next.js App Router (Pages & Layouts)
├── components/           # کامپوننت‌های عمومی و اتمیک (Button, Input, UI)
│   ├── common/
│   └── modules/          # کامپوننت‌های اختصاصی هر بخش (AI Dashboard, Profile)
├── context/              # Context API برای استیت‌های سبک
├── hooks/                # Custom Hooks اختصاصی برای لاجیک‌های مشترک
├── services/             # کانفیگ Axios و متدهای ارتباط با API لاراول
├── store/                # Redux Toolkit Configuration (Slices & Store)
├── types/                # اینترفیس‌ها و تایپ‌های منسجم TypeScript
└── utils/                # توابع کمکی و فرمت‌کننده‌ها (Helpers)
