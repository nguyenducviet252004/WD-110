import Sale from "../../assets/imgs/page/homepage1/upto60.png";
import Banner from "../../assets/imgs/page/homepage1/bg-section2.png";
import ProductDetail from "../../assets/imgs/page/product/img-1.webp";
import ProductDetailTwo from "../../assets/imgs/page/product/img-2.webp";
import ProductDetailThree from "../../assets/imgs/page/product/img-3.webp";
import ProductDetailFour from "../../assets/imgs/page/product/img4.webp";
import ProductDetailFive from "../../assets/imgs/page/product/img-5.webp";
import ProductDetailSix from "../../assets/imgs/page/product/img-6.webp";
import { Swiper, SwiperSlide } from "swiper/react";
import Star from "../../assets/imgs/template/icons/star.svg";
import api from "../../Axios/Axios";
import { message, Pagination } from "antd";
import { useState, useEffect } from "react";
import { IProduct } from "../../types/cart-item";
import type { PaginationProps } from "antd";
import { Link } from "react-router-dom";
const ProductWithCategories: React.FC = () => {
  const [products, setProducts] = useState<IProduct[]>([]);
  const [Newproducts, setNewProducts] = useState<IProduct[]>([]);
  const [Topproducts, setTopProducts] = useState<IProduct[]>([]);
  const pageSize = 8;
  const [current, setCurrent] = useState(1);

  const onChange: PaginationProps["onChange"] = (page) => {
    console.log(page);
    setCurrent(page);
  };

  const GetProductCategory = async () => {
    try {
      const { data } = await api.get(`/products`);
      setProducts(data.products);
    } catch (error) {
      console.log(error);
    }
  };

  const paginatedProducts = products.slice(
    (current - 1) * pageSize,
    current * pageSize
  );

  const GetNewProducts = async () => {
    try {
      const { data } = await api.get(`/newproduct`);
      setNewProducts(data.products);
    } catch (error) {
      console.log(error);
    }
  };

  const paginatedNewProducts = Newproducts.slice(
    (current - 1) * pageSize,
    current * pageSize
  );

  const GetTopProducts = async () => {
    try {
      const { data } = await api.get(`/topsell`);
      setTopProducts(data.products);
    } catch (error) {
      console.log(error);
    }
  };

  const paginatedTopProducts = Topproducts.slice(
    (current - 1) * pageSize,
    current * pageSize
  );

  // const boyProducts = products.filter(
  //   (product) => product.categories.name === "Nam"
  // );
  // const girlProducts = products.filter(
  //   (product) => product.categories.name === "Nữ"
  // );
  // const kidProducts = products.filter(
  //   (product) => product.categories.name === "Trẻ em"
  // );

  useEffect(() => {
    GetProductCategory();
    GetNewProducts();
    GetTopProducts();
  }, []);

  return (
    <>
      <section className="section block-section-1">
        <div className="container">
          <div className="text-center">
            <p className="font-xl brand-2 wow animate__animated animate__fadeIn">
              <span className="rounded-text">NEW IN STORE</span>
            </p>
            <div className="box-tabs wow animate__animated animate__fadeIn">
              <ul className="nav nav-tabs" role="tablist">
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link active"
                    id="girls-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#girls"
                    type="button"
                    role="tab"
                    aria-controls="girls"
                    aria-selected="true"
                  >
                    Tất cả sản phẩm
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link"
                    id="boys-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#boys"
                    type="button"
                    role="tab"
                    aria-controls="boys"
                    aria-selected="false"
                  >
                    Sản phẩm bán chạy nhất
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className="nav-link"
                    id="accessories-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#accessories"
                    type="button"
                    role="tab"
                    aria-controls="accessories"
                    aria-selected="false"
                  >
                    Sản phẩm mới về
                  </button>
                </li>
              </ul>
            </div>
          </div>
          {/* sản phẩm theo danh mục */}
          <div className="tab-content">
            <div
              className="tab-pane fade show active"
              id="girls"
              role="tabpanel"
              aria-labelledby="girls-tab"
            >
              <div className="row">
                {paginatedProducts.map((product) => (
                  <div
                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                    data-wow-delay=".5s"
                  >
                    <Link to={`/product-detail/${product.id}`}>
                      <div className="cardProduct wow fadeInUp">
                        <div className="cardImage">
                          {/* <label className="lbl-hot">hot</label> */}
                          <a>
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
                            <a>Add to Cart</a>
                          </div>
                        </div>
                        <div className="cardInfo">
                          <h6
                            className="font-md-bold cardTitle"
                            style={{
                              fontFamily: "Raleway",
                              fontWeight: "normal",
                            }}
                          >
                            {product.name}
                          </h6>
                          <p
                            className="font-lg cardDesc"
                            style={{ fontFamily: "Raleway" }}
                          >
                            {" "}
                            {Math.round(product.price ?? 0).toLocaleString(
                              "vi-VN",
                              { style: "currency", currency: "VND" }
                            )}
                          </p>
                        </div>
                      </div>
                    </Link>
                  </div>
                ))}
              </div>
            </div>
            <div
              className="tab-pane fade"
              id="boys"
              role="tabpanel"
              aria-labelledby="boys-tab"
            >
              <div className="row">
                {paginatedTopProducts.map((product) => (
                  <div
                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                    data-wow-delay=".1s"
                  >
                    <Link to={`/product-detail/${product.id}`}>
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
                            <a>Add to Cart</a>
                          </div>
                        </div>
                        <div className="cardInfo">
                          <h6
                            className="font-md-bold cardTitle"
                            style={{
                              fontFamily: "Raleway",
                              fontWeight: "normal",
                            }}
                          >
                            {product.name}
                          </h6>
                          <p
                            className="font-lg cardDesc"
                            style={{ fontFamily: "Raleway" }}
                          >
                            {" "}
                            {Math.round(product.price ?? 0).toLocaleString(
                              "vi-VN",
                              { style: "currency", currency: "VND" }
                            )}
                          </p>
                        </div>
                      </div>
                    </Link>
                  </div>
                ))}
              </div>
            </div>

            <div
              className="tab-pane fade"
              id="accessories"
              role="tabpanel"
              aria-labelledby="children"
            >
              <div className="row">
                {paginatedNewProducts.map((product) => (
                  <div
                    className="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow animate__animated animate__fadeIn"
                    data-wow-delay=".1s"
                  >
                    <Link to={`/product-detail/${product.id}`}>
                      <div className="cardProduct wow fadeInUp">
                        <div className="cardImage">
                          <label className="lbl-hot">new</label>
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
                            <a>Add to Cart</a>
                          </div>
                        </div>
                        <div className="cardInfo">
                          <h6
                            className="font-md-bold cardTitle"
                            style={{
                              fontFamily: "Raleway",
                              fontWeight: "normal",
                            }}
                          >
                            {product.name}
                          </h6>
                          <p
                            className="font-lg cardDesc"
                            style={{ fontFamily: "Raleway" }}
                          >
                            {" "}
                            {Math.round(product.price ?? 0).toLocaleString(
                              "vi-VN",
                              { style: "currency", currency: "VND" }
                            )}
                          </p>
                        </div>
                      </div>
                    </Link>
                  </div>
                ))}
              </div>
            </div>
          </div>
          <nav className="box-pagination" style={{ float: "right" }}>
            <Pagination
              current={current}
              onChange={onChange}
              total={products.length}
              pageSize={pageSize}
            />
          </nav>
        </div>
      </section>
    </>
  );
};

export default ProductWithCategories;
