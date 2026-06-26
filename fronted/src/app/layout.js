import ThemeRegistry from "@/components/ThemeRegistry";
import StoreProvider from "@/store/StoreProvider";

export const metadata = {
  title: "سیستم پیشرفته وفاداری مشتریان",
  description: "طراحی شده با Next.js 15 و Material UI",
};

export default function RootLayout({ children }) {
  return (
    <html lang="fa" dir="rtl">
      <body>
        <StoreProvider>
          <ThemeRegistry>{children}</ThemeRegistry>
        </StoreProvider>
      </body>
    </html>
  );
}
