import React from "react";
import ProductList from "../components/ProductList/ProductList";
import Header from "../components/LayoutComponent/Header";
import Banner from "../components/LayoutComponent/Banner";
import Footer from "../components/LayoutComponent/Footer";

const HomePage = () => {
  return (
    <div>
      <Header />
      <Banner />
      <div className="max-w-7xl mx-auto px-4 py-8">
        <h2 className="text-2xl font-bold mb-4 text-gray-800">
          Sản phẩm nổi bật
        </h2>
        <ProductList />
      </div>
       <Footer />
    </div>
  );
};
      
      
     

export default HomePage;
