import React from "react";
import Header from "../components/LayoutComponent/Header";
import Footer from "../components/LayoutComponent/Footer";

const ShopPage = () => {
  return (
    <div>
      <Header />
      <div>
        <img
          src="https://aobongdathietke.vn/wp-content/uploads/2020/12/banner-ao-bong-da-thiet-ke-1400x544.png"
          alt="Banner"
          style={{ width: "100%", height: "30%" }}
        />
      </div>
      <h1>Cửa hàng</h1>
      <Footer />
    </div>
  );
};

export default ShopPage;
