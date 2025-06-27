import React, { useState, useEffect } from "react";
import { message, Pagination } from "antd";
import { IProduct, Size, Color } from "../../types/cart-item";
import { Link } from "react-router-dom";
import type { PaginationProps } from "antd";
import axios from "axios";

interface ProductListProps {
  filters: {
    size: string | null;
    color: string | null;
    category: string | null;
    priceRange: [number, number] | null;
  };
}

const ProductList: React.FC<ProductListProps> = ({ filters }) => {
  const [products, setProducts] = useState<IProduct[]>([]);
  const [filteredProducts, setFilteredProducts] = useState<IProduct[]>([]);
  const [current, setCurrent] = useState(1);
  const [loading, setLoading] = useState(true);
  const pageSize = 6;

  const onChange: PaginationProps["onChange"] = (page) => {
    console.log(page);
    window.scrollTo(0, 0);
    setCurrent(page);
  };

  const GetAllProducts = async () => {
    try {
      const { data } = await axios.get("http://127.0.0.1:8000/api/products");
      setProducts(data.products);
      setFilteredProducts(data.products);
    } catch (error) {
      message.error("Lỗi api!");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    GetAllProducts();
  }, []);

  useEffect(() => {
    const filterData = products.filter((product) => {
      const matchSize = filters.size
        ? product.sizes.some((size: Size) => size.size === filters.size)
        : true;
      const matchColor = filters.color
        ? product.colors.some(
            (color: Color) => color.name_color === filters.color
          )
        : true;
      const matchCategory = filters.category
        ? product.categories.name === filters.category
        : true;
      const matchPrice = filters.priceRange
        ? product.price >= filters.priceRange[0] &&
          product.price <= filters.priceRange[1]
        : true;
      return matchSize && matchColor && matchCategory && matchPrice;
    });

    setFilteredProducts(filterData);
  }, [filters, products]);

  const paginatedProducts = filteredProducts.slice(
    (current - 1) * pageSize,
    current * pageSize
  );

  if (loading)
    return (
      <div>
        <div id="preloader-active">
          <div className="preloader d-flex align-items-center justify-content-center">
            <div className="preloader-inner position-relative">
              <div className="page-loading text-center">
                <div className="page-loading-inner">
                  <div />
                  <div />
                  <div />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );

  return (
    <>
      <div className="col-lg-9 order-lg-last">
        <div className="box-filter-top">
          <div className="number-product">
            <p className="body-p2 neutral-medium-dark">
              Hiển thị {pageSize} trong số {filteredProducts.length} sản phẩm
            </p>
          </div>
        </div>
        <div className="box-product-lists">
          <div className="row">
            {paginatedProducts.map((product) => (
              <div className="col-xl-4 col-sm-6" key={product.id}>
                <div className="cardProduct wow fadeInUp">
                  <div className="cardImage">
                    {/* <label className="lbl-hot">hot</label> */}
                    <Link to={`/product-detail/${product.id}`}>
                      <img
                        className="imageMain"
                        src={product.avatar_url}
                        alt={product.name}
                      />
                      <img
                        className="imageHover"
                        src={product.avatar_url}
                        alt={product.name}
                      />
                    </Link>
                    <div className="button-select">
                      <Link to={`/product-detail/${product.id}`}>
                        Add to Cart
                      </Link>
                    </div>
                  </div>
                  <div className="cardInfo">
                    <Link to={`/product-detail/${product.id}`}>
                      <h6
                        style={{ fontFamily: "Raleway", fontWeight: "normal" }}
                        className="cardTitle"
                      >
                        {product.name}
                      </h6>
                    </Link>
                    <p
                      style={{ fontFamily: "Raleway" }}
                      className="font-lg cardDesc"
                    >
                      {Math.round(product.price).toLocaleString("vi", {
                        style: "currency",
                        currency: "VND",
                      })}
                    </p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
        <nav className="box-pagination" style={{ float: "right" }}>
          <Pagination
            current={current}
            onChange={onChange}
            total={filteredProducts.length}
            pageSize={pageSize}
          />
        </nav>
      </div>
    </>
  );
};

export default ProductList;
