import React from "react";
import Header from "../components/LayoutComponent/Header";
import Footer from "../components/LayoutComponent/Footer";

const ContactPage = () => {
  return (
    <div>
      <Header />
      <img
        src="https://i.ytimg.com/vi/ymcA0abnsoE/maxresdefault.jpg"
        alt="Banner"
        style={{ width: "100%", height: "30%" }}
      />
      <h1> Liên hệ</h1>
      <Footer />
    </div>
  );
};

export default ContactPage;
