"use client";
import React, { useState } from "react";
import Link from "next/link";
import { useDispatch, useSelector } from "react-redux";
import { useRouter } from "next/navigation"; 
import {
  Box,
  Button,
  TextField,
  Typography,
  Container,
  Paper,
  Alert,
  CircularProgress,
} from "@mui/material";
import { authStart, authSuccess, authFailure } from "@/store/slices/authSlice";
import API from "@/services/api"; 
export default function LoginPage() {
  const dispatch = useDispatch();
  const router = useRouter();
  const { loading, error } = useSelector((state) => state.auth);

  const [formData, setFormData] = useState({ email: "", password: "" });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    dispatch(authStart());

    try {

      const response = await API.post("/login", {
        email: formData.email,
        password: formData.password,
      });


      dispatch(
        authSuccess({
          user: response.data.user,
          token: response.data.token,
        }),
      );


      localStorage.setItem("token", response.data.token);


      router.push("/products");
    } catch (err) {

      const errorMessage =
        err.response?.data?.message || "خطایی در اتصال به سرور رخ داد.";
      dispatch(authFailure(errorMessage));
    }
  };

  return (
    <Container component="main" maxWidth="xs">
      <Box
        sx={{
          marginTop: 8,
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
            ورود به سیستم وفاداری
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
              id="email"
              label="آدرس ایمیل"
              name="email"
              autoComplete="email"
              autoFocus
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
              autoComplete="current-password"
              value={formData.password}
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
                "ورود"
              )}
            </Button>
            <Box sx={{ mt: 3, textAlign: "center" }}>
              <Typography variant="body2" color="text.secondary">
                هنوز حساب کاربری ندارید؟{" "}
                <Link
                  href="/register"
                  style={{
                    textDecoration: "none",
                    fontWeight: "bold",
                    color: "#1976d2",
                  }}
                >
                  همین حالا ثبت‌نام کنید
                </Link>
              </Typography>
            </Box>
          </Box>
        </Paper>
      </Box>
    </Container>
  );
}
