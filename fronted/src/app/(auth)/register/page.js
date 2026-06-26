"use client";
import React, { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useRouter } from "next/navigation";
import Link from "next/link";
import {
  Box,
  Button,
  TextField,
  Typography,
  Container,
  Paper,
  Alert,
  CircularProgress,
  Grid,
} from "@mui/material";
import { authStart, authSuccess, authFailure } from "@/store/slices/authSlice";
import API from "@/services/api";

export default function RegisterPage() {
  const dispatch = useDispatch();
  const router = useRouter();
  const { loading, error } = useSelector((state) => state.auth);

  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    dispatch(authStart());

    if (formData.password !== formData.password_confirmation) {
      dispatch(authFailure("رمز عبور و تکرار آن با هم مطابقت ندارند."));
      return;
    }

    try {
      const response = await API.post("/register", {
        name: formData.name,
        email: formData.email,
        password: formData.password,
        password_confirmation: formData.password_confirmation,
      });

      dispatch(
        authSuccess({
          user: response.data.user,
          token: response.data.token,
        }),
      );

      localStorage.setItem("token", response.data.token);

      alert("ثبت‌نام با موفقیت انجام شد!");
      router.push("/products");
    } catch (err) {
      const errorMessage =
        err.response?.data?.message ||
        "خطایی در ثبت‌نام رخ داد. لطفاً دوباره تلاش کنید.";
      dispatch(authFailure(errorMessage));
    }
  };

  return (
    <Container component="main" maxWidth="xs">
      <Box
        sx={{
          marginTop: 6,
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
        }}
      >
        <Paper
          elevation={3}
          sx={{ padding: 4, width: "100%", borderRadius: 2 }}
        >
          <Typography
            component="h1"
            variant="h5"
            align="center"
            gutterBottom
            sx={{ fontWeight: "bold" }}
          >
            عضویت در سیستم وفاداری
          </Typography>

          {error && (
            <Alert severity="error" sx={{ mb: 2 }}>
              {error}
            </Alert>
          )}

          <Box
            component="form"
            onSubmit={handleSubmit}
            noValidate
            sx={{ mt: 1 }}
          >
            <TextField
              margin="normal"
              required
              fullWidth
              id="name"
              label="نام و نام خانوادگی"
              name="name"
              autoFocus
              value={formData.name}
              onChange={handleChange}
            />
            <TextField
              margin="normal"
              required
              fullWidth
              id="email"
              label="آدرس ایمیل"
              name="email"
              autoComplete="email"
              value={formData.email}
              onChange={handleChange}
            />
            <TextField
              margin="normal"
              required
              fullWidth
              name="password"
              label="رمز عبور"
              type="password"
              id="password"
              value={formData.password}
              onChange={handleChange}
            />
            <TextField
              margin="normal"
              required
              fullWidth
              name="password_confirmation"
              label="تکرار رمز عبور"
              type="password"
              id="password_confirmation"
              value={formData.password_confirmation}
              onChange={handleChange}
            />
            <Button
              type="submit"
              fullWidth
              variant="contained"
              disabled={loading}
              sx={{ mt: 3, mb: 2, padding: 1.2, fontSize: "1rem" }}
            >
              {loading ? (
                <CircularProgress size={24} color="inherit" />
              ) : (
                "ثبت نام"
              )}
            </Button>
            // کد اصلاح‌شده و استاندارد:
            {/* کد کاملاً اصلاح‌شده و سازگار با سیستم جدید گرید */}
            <Grid container sx={{ justifyContent: "flex-end" }}>
              <Grid
                size={{ xs: 12 }}
                sx={{ display: "flex", justifyContent: "flex-end" }}
              >
                <Link href="/login" style={{ textDecoration: "none" }}>
                  <Typography
                    variant="body2"
                    sx={{
                      color: "primary.main",
                      "&:hover": { textDecoration: "underline" },
                    }}
                  >
                    قبلاً ثبت‌نام کرده‌اید؟ وارد شوید
                  </Typography>
                </Link>
              </Grid>
            </Grid>
          </Box>
        </Paper>
      </Box>
    </Container>
  );
}
