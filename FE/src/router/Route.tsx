import { BrowserRouter as Router, Routes, Route } from "react-router-dom";

import Header from "../components/user/LayoutComponent/Header";
import HomePage from "../pages/user/HomePage";
import ProductDetail from "../pages/user/ProductDetail";
import Login from "../pages/user/Login"; 
import Register from "../pages/user/Register";
import AdminProducts from "../pages/admin/AdminProducts";
import AdminOrders from "../pages/admin/AdminOrders";
import CartPage from "../pages/user/CartPage";
import AdminDashboard from "../pages/admin/AdminDashboard";
import Home from "../Layout/user/Home/Home";
import AdminLayout from "../Layout/admin/AdminLayout";
import AdminUsers from "../pages/admin/AdminUser";
import AdminColors from "../pages/admin/AdminColor";
import AdminSizes from "../pages/admin/AdminSize";
import AdminCategories from "../pages/admin/AdminCagetories";

function App() {
  return (
    <Router>
      <Header />
      <Routes>
        {/* User routes */}
        <Route element={<Home/>}>
           <Route path="/" element={<HomePage />} />
        <Route path="/product/:id" element={<ProductDetail />} />
        <Route path="/cart" element={<CartPage />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
       </Route>

        {/* Admin routes */}
        <Route path="/admin" element={<AdminLayout />}>
          <Route index element={<AdminDashboard />} />
          <Route path="/admin/products" element={<AdminProducts />} />
          <Route path="/admin/orders" element={<AdminOrders />} />
          <Route path="/admin/users" element={<AdminUsers />} />
          <Route path="/admin/color" element={<AdminColors />} />
          <Route path="/admin/size" element={<AdminSizes />} />
          <Route path="/admin/user" element={<AdminUsers />} />
          <Route path="/admin/cagetories" element={<AdminCategories />} />
        </Route>
      </Routes>
    </Router>
  );
}

export default App;
