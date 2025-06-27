import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";
import { Link } from "react-router-dom";
import { message } from "antd";
import { useState, useEffect } from "react";
import { IProduct } from "../../types/cart-item";
import api from "../../Axios/Axios";
const TrendingProduct: React.FC = () => {
  const [products, setProducts] = useState<IProduct[]>([]);
  const GetProductCategory = async () => {
    try {
      const { data } = await api.get(`/products`);
      console.log(data);

      setProducts(data.products);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  const trendingProducts = products.filter(
    (product) => product.categories.name === "Sản phẩm thịnh hành"
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
              Sản phẩm thịnh hành
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
      <section className="section block-section-4">
        <div className="container">
          <div className="box-section-4">
            <div className="row">
              <div className="col-lg-6">
                <div className="box-collection wow animate__animated animate__fadeIn">
                  <div className="box-collection-info">
                    <h4 className="heading-4 mb-15">Girls Apparels</h4>
                    <p className="font-md neutral-900 mb-35">
                      Get an extra 50% discount on premium
                      <br className="d-none d-lg-block" />
                      quality baby clothes. Shop now!
                    </p>
                    <Link
                      className="btn btn-brand-1 text-uppercase"
                      to={"/product"}
                    >
                      Shop Now
                    </Link>
                  </div>
                  {/* <div className="star-bg-2"><img src={StarTwo} alt="Kidify" /></div> */}
                </div>
              </div>
              <div className="col-lg-6">
                <div className="box-collection box-collection-2 wow animate__animated animate__fadeIn">
                  <div className="box-collection-info">
                    <h4 className="heading-4 mb-15">Hot Branch</h4>
                    <p className="font-md neutral-900 mb-35">
                      New Brand Fasion on this Summer.
                      <br className="d-none d-lg-block" />
                      Sale off up to 35%
                    </p>
                    <Link
                      className="btn btn-brand-1 text-uppercase"
                      to={"/product"}
                    >
                      Shop Now
                    </Link>
                  </div>
                  {/* <div className="star-bg-1"><img src={Star} alt="Kidify" /></div> */}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
};

export default TrendingProduct;
