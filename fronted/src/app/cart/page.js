"use client";
import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { useRouter } from "next/navigation";
import {
  Container,
  Typography,
  Box,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Button,
  IconButton,
  Avatar,
} from "@mui/material";
import AddIcon from "@mui/icons-material/Add";
import RemoveIcon from "@mui/icons-material/Remove";
import DeleteIcon from "@mui/icons-material/Delete";
import ArrowBackIcon from "@mui/icons-material/ArrowBack";
import { addToCart, removeFromCart, clearCart } from "@/store/slices/cartSlice";

const StyledTableCell = ({ children, ...props }) => (
  <TableCell
    align="right"
    sx={{ fontWeight: "bold", fontSize: "0.95rem" }}
    {...props}
  >
    {children}
  </TableCell>
);

export default function CartPage() {
  const dispatch = useDispatch();
  const router = useRouter();

  const { items, totalAmount, totalQuantity } = useSelector(
    (state) => state.cart,
  );

  const handleIncrement = (item) => {
    dispatch(addToCart(item));
  };

  const handleDecrement = (id) => {
    dispatch(removeFromCart(id));
  };

const handleCheckout = async () => {
  try {
    const orderData = {
      items: items.map((item) => ({
        id: item.id,
        quantity: item.quantity,
      })),
    };

    const response = await API.post("/orders", orderData);

    if (response.data.success) {
      alert(response.data.message || "سفارش با موفقیت ثبت شد!");

      dispatch(clearCart());

      if (response.data.payment_url) {
        window.location.href = response.data.payment_url;
      } else {
        router.push("/products");
      }
    }
  } catch (err) {
    const errorMessage =
      err.response?.data?.message ||
      "خطایی در سیستم رخ داد. لطفاً مطمئن شوید لاگین هستید.";

    if (err.response?.data?.error_dev) {
      console.error("Laravel Dev Error:", err.response.data.error_dev);
    }

    alert(errorMessage);
  }
};

  if (items.length === 0) {
    return (
      <Container maxWidth="md" sx={{ mt: 8, textAlign: "center" }}>
        <Paper elevation={2} sx={{ p: 5, borderRadius: 2 }}>
          <Typography variant="h5" gutterBottom sx={{ fontWeight: "bold" }}>
            سبد خرید شما در حال حاضر خالی است! 🛒
          </Typography>
          <Button
            variant="contained"
            sx={{ mt: 3 }}
            onClick={() => router.push("/products")}
          >
            بازگشت به کاتالوگ محصولات
          </Button>
        </Paper>
      </Container>
    );
  }

  return (
    <Container maxWidth="md" sx={{ mt: 4, mb: 4 }}>
      <Box
        sx={{
          display: "flex",
          justifyContent: "space-between",
          alignItems: "center",
          mb: 4,
        }}
      >
        <Typography variant="h4" component="h1" sx={{ fontWeight: "bold" }}>
          سبد خرید شما
        </Typography>
        <Button
          startIcon={<ArrowBackIcon sx={{ ml: 1, mr: 0 }} />}
          onClick={() => router.push("/products")}
        >
          ادامه خرید
        </Button>
      </Box>

      <TableContainer
        component={Paper}
        elevation={3}
        sx={{ borderRadius: 2, mb: 3 }}
      >
        <Table aria-label="cart table">
          <TableHead sx={{ backgroundColor: "grey.100" }}>
            <TableRow>
              <StyledTableCell align="right">تصویر</StyledTableCell>
              <StyledTableCell align="right">نام محصول</StyledTableCell>
              <StyledTableCell align="center">تعداد</StyledTableCell>
              <StyledTableCell align="right">قیمت واحد</StyledTableCell>
              <StyledTableCell align="right">قیمت کل</StyledTableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {items.map((item) => (
              <TableRow
                key={item.id}
                sx={{ "&:last-child td, &:last-child th": { border: 0 } }}
              >
                <TableCell align="right">
                  <Avatar
                    src={item.image}
                    alt={item.name}
                    variant="rounded"
                    sx={{ width: 56, height: 56 }}
                  />
                </TableCell>
                <TableCell align="right" sx={{ fontWeight: "bold" }}>
                  {item.name}
                </TableCell>
                <TableCell align="center">
                  <Box
                    sx={{
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                      gap: 1,
                    }}
                  >
                    <IconButton
                      size="small"
                      color="primary"
                      onClick={() => handleIncrement(item)}
                    >
                      <AddIcon fontSize="small" />
                    </IconButton>
                    <Typography
                      sx={{
                        fontWeight: "bold",
                        minWidth: 20,
                        textAlign: "center",
                      }}
                    >
                      {item.quantity.toLocaleString("fa-IR")}
                    </Typography>
                    <IconButton
                      size="small"
                      color="error"
                      onClick={() => handleDecrement(item.id)}
                    >
                      {item.quantity === 1 ? (
                        <DeleteIcon fontSize="small" />
                      ) : (
                        <RemoveIcon fontSize="small" />
                      )}
                    </IconButton>
                  </Box>
                </TableCell>
                <TableCell align="right">
                  {Number(item.price).toLocaleString("fa-IR")} امتیاز
                </TableCell>
                <TableCell
                  align="right"
                  sx={{ color: "primary.main", fontWeight: "bold" }}
                >
                  {Number(item.totalPrice).toLocaleString("fa-IR")} امتیاز
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>

      <Paper
        elevation={3}
        sx={{
          p: 3,
          borderRadius: 2,
          display: "flex",
          flexDirection: "column",
          gap: 2,
        }}
      >
        <Box sx={{ display: "flex", justifyContent: "space-between" }}>
          <Typography variant="body1">تعداد کل کالاها:</Typography>
          <Typography variant="body1" sx={{ fontWeight: "bold" }}>
            {totalQuantity.toLocaleString("fa-IR")} کالا
          </Typography>
        </Box>
        <Box
          sx={{
            display: "flex",
            justifyContent: "space-between",
            borderTop: "1px solid #e0e0e0",
            pt: 2,
          }}
        >
          <Typography variant="h6">مجموع مبالغ فاکتور:</Typography>
          <Typography
            variant="h6"
            color="primary.main"
            sx={{ fontWeight: "bold" }}
          >
            {totalAmount.toLocaleString("fa-IR")} امتیاز / تومان
          </Typography>
        </Box>
        <Button
          variant="contained"
          size="large"
          fullWidth
          onClick={handleCheckout}
          sx={{ mt: 2, py: 1.5, fontSize: "1.1rem", borderRadius: 1.5 }}
        >
          تایید و ثبت نهایی سفارش
        </Button>
      </Paper>
    </Container>
  );
}
