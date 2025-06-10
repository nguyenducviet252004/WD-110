import React from "react";
import { Route, Routes } from "react-router-dom";
import Home from "../Layout/Home/Home";
import Product from "../Layout/Product/Product";
import Blog from "../Layout/Blog/Blog";
import About from "../Layout/About/About";
import Cart from "../Layout/Cart/Cart";
import Foutnd from "../Layout/Found/Foutnd";

const AppRoute = () => {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/product" element={<Product />} />
      <Route path="/blog" element={<Blog />} />
      <Route path="/about" element={<About />} />
      <Route path="/cart" element={<Cart />} />
      <Route path="*" element={<Foutnd />} />
    </Routes>
  );
};

export default AppRoute;