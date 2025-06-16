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
        <Route element={<AdminLayout />}>
          <Route path="/admin" element={<AdminDashboard />} />
          <Route path="/admin/products" element={<AdminProducts />} />
          <Route path="/admin/orders" element={<AdminOrders />} />
        </Route>
      </Routes>
    </Router>
  );
}

export default App;
