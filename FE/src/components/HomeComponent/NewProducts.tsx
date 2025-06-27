import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";
import { Link } from "react-router-dom";
import { message } from "antd";
import { useState, useEffect } from "react";
import { IProduct } from "../../types/cart-item";
import api from "../../Axios/Axios";
const NewProduct: React.FC = () => {
  const [products, setProducts] = useState<IProduct[]>([]);
  const GetProductCategory = async () => {
    try {
      const { data } = await api.get(`/products`);
      setProducts(data.products);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  const trendingProducts = products.filter(
    (product) => product.categories.name === "Hàng mới về"
  );

  useEffect(() => {
    GetProductCategory();
  }, []);

  return (
    <>
      <section className="section block-section-3">
        <div className="container">
          <div className="top-head">
            <h4 className="text-uppercase brand-1 wow animate__animated animate__fadeIn">
              Hàng mới về
            </h4>
          </div>
          <div className="box-products wow animate__animated animate__fadeIn">
            <Swiper
              navigation
              modules={[Navigation]}
              className="swiper-container"
              slidesPerView={3}
              spaceBetween={30}
            >
              {trendingProducts.map((product) => (
                <SwiperSlide>
                  <div className="cardProduct wow fadeInUp">
                    <div className="cardImage">
                      <label className="lbl-hot">hot</label>
                      <a href="product-single.html">
                        <img
                          className="imageMain"
                          src={product.avatar_url}
                          alt="kidify"
                        />
                        <img
                          className="imageHover"
                          src={product.avatar_url}
                          alt="kidify"
                        />
                      </a>
                      <div className="button-select">
                        <a href="product-single.html">Add to Cart</a>
                      </div>
                      <div className="box-quick-button"></div>
                    </div>
                    <div className="cardInfo">
                      <Link to={`/product-detail/${product.id}`}>
                        <h6 className="font-md-bold cardTitle">
                          {product.name}
                        </h6>
                      </Link>
                      <p className="font-lg cardDesc">
                        {" "}
                        {Math.round(product.price ?? 0).toLocaleString(
                          "vi-VN",
                          { style: "currency", currency: "VND" }
                        )}
                      </p>
                    </div>
                  </div>
                </SwiperSlide>
              ))}
            </Swiper>
          </div>
        </div>
      </section>
    </>
  );
};

export default NewProduct;
