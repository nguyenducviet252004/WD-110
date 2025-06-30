import React from "react";
import Header from "../components/LayoutComponent/Header";
import Banner from "../components/ProductComponent/Banner";
import ProductList from "../components/ProductComponent/ProductList";
import Footer from "../components/LayoutComponent/Footer";

function Home() {
  const filters = {
    size: null,
    color: null,
    category: null,
    priceRange: null,
  };
  return (
    <>
      <Header />
      <Banner />
      <ProductList filters={filters} />
      <Footer />
    </>
  );
}

export default Home;
