import React from "react";
import Header from "../components/LayoutComponent/Header";
import Footer from "../components/LayoutComponent/Footer";

const AboutPage = () => {
  return (
    <div>
      <Header />
      <div>
        <img
          src="https://aobongda.vn/wp-content/uploads/2024/03/banner-1.jpg"
          alt="Banner"
          style={{ width: "100%", height: "30%" }}
        />
      </div>
      <h1>Về tôi</h1>
      <Footer />
    </div>
  );
};

export default AboutPage;
