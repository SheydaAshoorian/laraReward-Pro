"use client";
import React, { useEffect, useState } from "react";
import Link from "next/link";
import { useDispatch, useSelector } from "react-redux";
import {
  Container,
  Grid,
  Card,
  CardMedia,
  CardContent,
  CardActions,
  Typography,
  Button,
  Box,
  Badge,
  IconButton,
  CircularProgress,
  Alert,
} from "@mui/material";
import ShoppingCartIcon from "@mui/icons-material/ShoppingCart";
import { addToCart } from "@/store/slices/cartSlice";
import API from "@/services/api"; 

export default function ProductsPage() {
  const dispatch = useDispatch();
  const totalQuantity = useSelector((state) => state.cart.totalQuantity);

  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        setLoading(true);
        const response = await API.get("/products");
        setProducts(response.data); 
        setLoading(false);
      } catch (err) {
        setError("خطا در دریافت لیست محصولات از سرور لاراول.");
        setLoading(false);
      }
    };

    fetchProducts();
  }, []);

  const handleAddToCart = (product) => {
    dispatch(addToCart(product));
  };

  if (loading) {
    return (
      <Box
        sx={{
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          height: "80vh",
        }}
      >
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return (
      <Container maxWidth="md" sx={{ mt: 4 }}>
        <Alert severity="error">{error}</Alert>
      </Container>
    );
  }

  return (
    <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
      <Box
        sx={{
          display: "flex",
          justifyContent: "space-between",
          alignItems: "center",
          mb: 4,
          pb: 2,
          borderBottom: "1px solid #e0e0e0",
        }}
      >
        <Typography variant="h4" component="h1" sx={{ fontWeight: "bold" }}>
          کاتالوگ جوایز و محصولات واقعی
        </Typography>
       
        <Link href="/cart" style={{ textDecoration: "none" }}>
          <IconButton color="primary" aria-label="cart">
            <Badge badgeContent={totalQuantity} color="error">
              <ShoppingCartIcon fontSize="large" />
            </Badge>
          </IconButton>
        </Link>
      </Box>

      {products.length === 0 && (
        <Typography variant="body1" align="center">
          هیچ محصولی در دیتابیس پیدا نشد.
        </Typography>
      )}

      <Grid container spacing={3}>
        {products.map((product) => (
          <Grid size={{ xs: 12, sm: 6, md: 3 }} key={product.id}>
            <Card
              sx={{
                height: "100%",
                display: "flex",
                flexDirection: "column",
                borderRadius: 2,
                boxShadow: 2,
              }}
            >
              <CardMedia
                component="img"
                height="200"
                image={product.image || "https://via.placeholder.com/500"}
                alt={product.name}
              />
              <CardContent sx={{ flexGrow: 1 }}>
                <Typography
                  gutterBottom
                  variant="h6"
                  component="h2"
                  sx={{ fontWeight: "bold", fontSize: "1.1rem" }}
                >
                  {product.name}
                </Typography>
                <Typography
                  variant="body1"
                  color="text.secondary"
                  sx={{ color: "primary.main", fontWeight: "bold" }}
                >
                  {Number(product.price).toLocaleString("fa-IR")} امتیاز / تومان
                </Typography>
              </CardContent>
              <CardActions sx={{ p: 2, pt: 0 }}>
                <Button
                  fullWidth
                  variant="contained"
                  onClick={() => handleAddToCart(product)}
                  sx={{ borderRadius: 1.5 }}
                >
                  افزودن به سبد خرید
                </Button>
              </CardActions>
            </Card>
          </Grid>
        ))}
      </Grid>
    </Container>
  );
}
