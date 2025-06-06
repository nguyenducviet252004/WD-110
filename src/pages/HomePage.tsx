import React from "react";
import HeroBanner from "../components/HeroBaner";
import ProductList from "../components/ProductList";

const HomePage = () => {
  return (
    <div>
      <HeroBanner />
      <div className="max-w-7xl mx-auto px-4 py-8">
        <h2 className="text-2xl font-bold mb-4 text-gray-800">
          Sản phẩm nổi bật
        </h2>
        <ProductList />
      </div>
    </div>
  );
};

export default HomePage;
