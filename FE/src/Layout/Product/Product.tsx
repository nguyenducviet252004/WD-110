import React, { useState, useEffect } from "react";
import AsideFilter from "../../components/ProductComponent/AsideFilter";
import Banner from "../../components/ProductComponent/Banner";
import ProductList from "../../components/ProductComponent/ProductList";
import ProductRelated from "../../components/ProductComponent/RecentlyViewProduct";

interface Filters {
  category: string | null;
  size: string | null;
  color: string | null;
  priceRange: [number, number] | null;
  brands: string[];
}

const Product: React.FC = () => {
  const [filters, setFilters] = useState<Filters>({
    category: null,
    size: null,
    color: null,
    priceRange: null,
    brands: [],
  });

  return (
    <>
      <Banner />
      <section className="section content-products">
        <div className="container">
          <div className="row">
            <AsideFilter setFilters={setFilters} />
            <ProductList filters={filters} />
          </div>
        </div>
        <ProductRelated />
      </section>
    </>
  );
};

export default Product;
