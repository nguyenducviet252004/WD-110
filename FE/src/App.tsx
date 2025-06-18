import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import HomePage from "./pages/HomePage";
import ShopPage from "./Layout/ShopPage";
import BlogPage from "./Layout/BlogPage";
import AboutPage from "./Layout/AboutPage";
import ContactPage from "./Layout/ContactPage";
import CheckoutPage from "./Layout/CheckoutPage";
import ProductsAdmin from "./components/Admin/Products/ProductsAdmin";
import CreateProducts from "./components/Admin/Products/CreateProducts";
import EditProducts from "./components/Admin/Products/EditProducts";

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/shop" element={<ShopPage />} />
        <Route path="/checkout" element={<CheckoutPage />} />
        <Route path="/blog" element={<BlogPage />} />
        <Route path="/about" element={<AboutPage />} />
        <Route path="/contact" element={<ContactPage />} />
        <Route path="/admin/products" element={<ProductsAdmin />} />
        <Route path="/admin/create" element={<CreateProducts />} />
        <Route path="/admin/edit/:id" element={<EditProducts />} />
        {/* Uncomment these routes when the components are ready */}
        {/* <Route path="/admin/categories" element={<div>Categories Admin</div>} />
        {/* <Route path="/admin/orders" element={<div>Orders Admin</div>} />
        <Route path="/admin/users" element={<div>Users Admin</div>} /> */}
      </Routes>
    </Router>
  );
}

export default App;
