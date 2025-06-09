import React from "react";

import "./App.css";

import { BrowserRouter, Route, Routes } from "react-router-dom";
import CartItem from "./components/CartComponents/CartItem";
import HomePage from "./pages/HomePage";


function App() {
  return (
      <BrowserRouter>
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/cart" element={<CartItem />} />
        {/* <Route path="/product-list" element={<ProductList />} />
        <Route path="/product-detail/:id" element={<ProductDetail />} />
        <Route path="/checkout" element={<Checkout />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} /> */}
</Routes>
      </BrowserRouter>
     
   
  );
}

export default App;
