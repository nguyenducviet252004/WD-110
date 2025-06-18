import React from "react";
import Header from "../components/LayoutComponent/Header";
import Banner from "../components/LayoutComponent/Banner";
import ProductList from "../components/LayoutComponent/ProductList";
import Footer from "../components/LayoutComponent/Footer";

function Home() {
  return (
    <>
      <Header />
      <Banner />
      <ProductList />
      <Footer />
    </>
  );
}

export default Home;
