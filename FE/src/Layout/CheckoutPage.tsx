import React from "react";
import Header from "../components/LayoutComponent/Header";
import Footer from "../components/LayoutComponent/Footer";

const CheckoutPage = () => {
  return (
    <div>
      <Header />
      <img
        src="https://cdn.sforum.vn/sforum/wp-content/uploads/2022/11/hinh-nen-may-tinh-world-cup-2022-7.jpg"
        alt="Banner"
        style={{ width: "100%", height: "30%" }}
      />
      <h1>Thanh toán</h1>
      <Footer />
    </div>
  );
};

export default CheckoutPage;
