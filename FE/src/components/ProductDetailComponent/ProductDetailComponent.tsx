import React, { useEffect, useState } from "react";
import { Navigate, useNavigate, useParams } from "react-router-dom";
// import Star from "../../public/imgs/template/icons/star.svg";
import { useAppDispatch } from "../../Redux/store";
import axios from "axios";
import { notification } from "antd";
import { addToCart } from "../../Redux/Reducer/CartReducer";
import { IProduct } from "../../types/cart-item";
import { message } from "antd";
import { Rate } from "antd";
import api from "../../Axios/Axios";
import { Link } from "react-router-dom";

import {
  MinusOutlined,
  PlusOutlined,
  ShoppingOutlined,
} from "@ant-design/icons";
import "./ProductDetail.css";
const ProductDetailComponent: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const dispatch = useAppDispatch();
  const [product, setProduct] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [productById, setProductById] = useState<IProduct[]>([]);
  const [error, setError] = useState("");
  const [selectedSize, setSelectedSize] = useState("");
  const [selectedColor, setSelectedColor] = useState<string | null>(null);
  const [quantity, setQuantity] = useState<number>(1);
  const [isCate, setIsCate] = useState<number>(0);
  const [selectedIndex, setSelectedIndex] = useState(0);
  const [isLogin, setIsLogin] = useState<boolean>(false);
  const [checkAdmin, setCheckAdmin] = useState<boolean>(false);
  const navigate = useNavigate();

  const convertToVND = (usdPrice: number) => {
    return usdPrice.toLocaleString("vi-VN");
  };

  const handleIncrease = () => {
    setQuantity((prevQuantity) => prevQuantity + 1);
  };

  const user = JSON.parse(localStorage.getItem("user") || "{}");
  console.log("siu", user);

  const handleDecrease = () => {
    setQuantity((prevQuantity) => (prevQuantity > 1 ? prevQuantity - 1 : 1));
  };

  const handleThumbnailClick = (index: number) => {
    setSelectedIndex(index);
  };

  useEffect(() => {
    const fetchProductDetail = async () => {
      try {
        const response = await axios.get(
          `http://localhost:8000/api/products/${id}`
        );
        setProduct(response.data);
      } catch (error) {
        setError("Failed to fetch product details");
      } finally {
        setLoading(false);
      }
    };

    const userz = localStorage.getItem("user");
    const checkadmin = JSON.parse(userz!);

    console.log(checkadmin, "alllll");

    if (userz) {
      try {
        const checkadmin = JSON.parse(userz);
        console.log(checkadmin, "alllll");
        setIsLogin(true);
        if (checkadmin?.user?.role === 2) {
          setCheckAdmin(true);
        } else {
          setCheckAdmin(false);
        }
      } catch (error) {
        console.error("Dữ liệu trong localStorage không hợp lệ", error);
      }
    } else {
      console.warn("Không tìm thấy user trong localStorage");
    }
    fetchProductDetail();
  }, [id]);

  useEffect(() => {
    const GetProductsById = async () => {
      try {
        if (product?.categories?.id) {
          const { data } = await api.get(
            `/categories/${product.categories.id}/products`
          );
          console.log("sản phẩm thoe id", data);
          setProductById(data.products);
        }
      } catch (error) {
        console.log(error);
      }
    };

    GetProductsById();
  }, [product]);

  console.log("chi tiết sản phẩm", productById);

  const handleAddToCart = async () => {
    if (!isLogin) {
      notification.warning({
        message: "Vui lòng đăng nhập để mua hàng !",
        className: "warning-message",
        placement: "bottomRight",
      });
      return;
    }
    if (checkAdmin) {
      notification.warning({
        message: "Admin không được phép mua hàng !",
        className: "warning-message",
        placement: "bottomRight",
      });
      return;
    } else {
      if (!selectedSize || !selectedColor) {
        notification.warning({
          message:
            "Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng!",
        });
      }
      const sizeId = product.sizes.find(
        (size: any) => size.size === selectedSize
      )?.id;
      const colorId = product.colors.find(
        (color: any) => color.name_color === selectedColor
      )?.id;
      try {
        const cartData = {
          productId: product.id,
          quantity,
          sizeId,
          colorId,
        };
        if (quantity > product.quantity && quantity > 1) {
          message.error(
            `Số lượng sản phẩm này chỉ còn ${product.quantity} trong kho !`
          );
          return;
        }
        if (product.quantity > 0) {
          await dispatch(addToCart(cartData));
          notification.success({
            message: "Thêm vào giỏ hàng thành công !",
            placement: "bottomRight",
          });
        } else {
          message.error("Sản phẩm này hiện không còn hàng !");
        }
      } catch (error) {
        console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);
        notification.error({
          message: "Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại!",
        });
      }
    }
  };

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
  if (error) return <div>{error}</div>;
  if (!product) return <div>Product not found.</div>;

  return (
    <>
      <main className="main">
        <div className="section block-shop-head-2 block-breadcrumb-type-1">
          <div className="container">
            <div className="breadcrumbs">
              <ul>
                <li>
                  <a href="#">Trang chủ</a>
                </li>
                <li>
                  <a href="#">Cửa hàng</a>
                </li>
                <li>
                  <a href="#">{product.name}</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <section className="section block-product-content">
          <div className="container">
            <div className="row">
              <div className="col-lg-5 box-images-product-left">
                <div className="detail-gallery">
                  <div className="slider-nav-thumbnails">
                    {product.galleries && product.galleries.length > 0 ? (
                      product.galleries.map((gallery: any, index: any) => (
                        <div
                          key={gallery.id}
                          onClick={() => handleThumbnailClick(index)}
                        >
                          <div className="item-thumb">
                            <img
                              src={`${gallery.image_path}`}
                              alt="Thumbnail"
                            />
                          </div>
                        </div>
                      ))
                    ) : (
                      <p>Không có ảnh trong thư viện.</p>
                    )}
                  </div>
                  <div className="box-main-gallery">
                    <a className="zoom-image glightbox" />
                    <div className="product-image-slider">
                      <figure className="border-radius-10">
                        <a className="glightbox">
                          <img width={"100%"} src={`${product.avatar}`} />
                        </a>
                      </figure>
                    </div>
                  </div>
                </div>
              </div>
              <div className="col-lg-5 box-images-product-middle">
                <div className="box-product-info">
                  {/* <label className="flash-sale-red">Extra 2% off</label> */}
                  <h2
                    style={{ fontFamily: "Raleway", marginBottom: "10px" }}
                    className="font-2xl"
                  >
                    {product.name}
                  </h2>
                  {/* Đánh giá trung bình */}
                  {product.reviews.length > 0 && (
                    <div className="block-rating">
                      <Rate
                        style={{ fontSize: "14px" }}
                        disabled
                        defaultValue={product.average_rating}
                      />
                    </div>
                  )}
                  {/* end */}
                  <span
                    style={{ fontFamily: "Raleway" }}
                    className="font-md neutral-500"
                  >
                    ({product.reviews.length} Reviews - {product.sell_quantity}{" "}
                    Orders)
                  </span>
                  <div className="block-price" style={{ marginTop: "20px" }}>
                    <span
                      style={{ fontFamily: "Raleway", fontSize: "25px" }}
                      className="price-main"
                    >
                      {Math.round(product.price).toLocaleString("vi", {
                        style: "currency",
                        currency: "VND",
                      })}
                    </span>
                  </div>
                  <div className="block-view">
                    <p
                      style={{ fontFamily: "Raleway" }}
                      className="font-md neutral-900"
                    >
                      {product.description}
                    </p>
                  </div>

                  <div className="block-color">
                    <span style={{ fontFamily: "Raleway" }}>Color:</span>
                    <label style={{ fontFamily: "Raleway", marginLeft: "5px" }}>
                      {selectedColor || "Chọn Màu"}
                    </label>
                    <ul className="list-color-detail">
                      {product.colors.map((color: any) => (
                        <button
                          className="button-color"
                          key={color.id}
                          style={{
                            fontFamily: "Raleway",
                            padding: "10px 15px",
                            border:
                              selectedColor === color.name_color
                                ? "1px solid rgb(159,137,219)"
                                : "1px solid gray",
                            borderRadius: "8px",
                            backgroundColor: "white",
                            // background: selectedColor === color.name_color ? 'rgb(159,137,219)' : 'none',
                            margin: "0 5px 0 0",
                            color:
                              selectedColor === color.name_color
                                ? "rgb(159,137,219)"
                                : "black",
                            cursor: "pointer",
                          }}
                          onClick={() => setSelectedColor(color.name_color)}
                        >
                          {color.name_color}
                        </button>
                      ))}
                    </ul>
                  </div>
                  <div className="block-size">
                    <span style={{ fontFamily: "Raleway" }}>Size:</span>
                    <label style={{ fontFamily: "Raleway", marginLeft: "5px" }}>
                      {selectedSize || "Chọn Size"}
                    </label>
                    <div className="list-sizes-detail">
                      {product.sizes.map((size: any) => (
                        <button
                          className="button-size"
                          key={size.id}
                          style={{
                            padding: "10px 15px",
                            border:
                              selectedSize === size.size
                                ? "1px solid rgb(159,137,219)"
                                : "1px solid gray",
                            borderRadius: "8px",
                            backgroundColor: "white",
                            color:
                              selectedSize === size.size
                                ? "rgb(159,137,219)"
                                : "black",
                            margin: "0 5px 0 0",
                            cursor: "pointer",
                          }}
                          onClick={() => setSelectedSize(size.size)}
                        >
                          {size.size}
                        </button>
                      ))}
                    </div>
                  </div>
                  {/* Tình trạng */}
                  <div className="block-size">
                    <span style={{ fontFamily: "Raleway" }}>Tình trạng:</span>
                    {product.quantity > 0 ? (
                      <span
                        style={{
                          fontFamily: "Raleway",
                          fontSize: "21px",
                          color: "rgb(159,134,217)",
                          fontStyle: "italic",
                          marginLeft: "5px",
                        }}
                      >
                        Còn hàng
                      </span>
                    ) : (
                      <span
                        style={{
                          fontFamily: "Raleway",
                          fontSize: "21px",
                          color: "rgb(159,134,217)",
                          fontStyle: "italic",
                        }}
                      >
                        Hết hàng
                      </span>
                    )}
                  </div>
                  <div className="block-quantity">
                    {/* <div className="font-sm neutral-500 mb-15">Quantity</div> */}
                    <div className="box-form-cart">
                      <div className="form-cart">
                        <button
                          style={{
                            border: "1px solid gray",
                            borderRight: "none",
                          }}
                          className="minus"
                          onClick={handleDecrease}
                        >
                          <MinusOutlined />
                        </button>
                        <input
                          className="form-control"
                          type="text"
                          style={{ border: "1px solid gray", fontSize: "18px" }}
                          value={quantity}
                          readOnly
                        />
                        <button
                          style={{
                            border: "1px solid gray",
                            borderLeft: "none",
                          }}
                          className="plus"
                          onClick={handleIncrease}
                        >
                          <PlusOutlined />
                        </button>
                      </div>
                      <button
                        className="css-button-add"
                        onClick={() => handleAddToCart()}
                        disabled={!selectedColor || !selectedSize}
                      >
                        <ShoppingOutlined /> Thêm vào giỏ hàng
                      </button>
                    </div>
                  </div>
                  {/* <div className="box-product-tag d-flex justify-content-between align-items-end">
                                        <div className="box-tag-left">
                                            <p className="font-xs mb-5"><span className="neutral-500">SKU:</span><span className="neutral-900">kid1232568-UYV</span></p>
                                            <p className="font-xs mb-5"><span className="neutral-500">Categories:</span><span className="neutral-900">Girls, Dress</span></p>
                                            <p className="font-xs mb-5"><span className="neutral-500">Tags:</span><span className="neutral-900">fashion, dress, girls, blue</span></p>
                                        </div>
                                        <div className="box-tag-right">
                                            <span className="font-sm">Share:</span>
                                        </div>
                                    </div> */}
                </div>
              </div>
            </div>
            {/* Tab mô tả */}
            <div className="box-detail-product">
              <ul className="nav-tabs nav-tab-product" role="tablist">
                <li className="nav-item" role="presentation">
                  <button
                    style={{ fontFamily: "Raleway" }}
                    className="nav-link active"
                    id="description-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#description"
                    type="button"
                    role="tab"
                    aria-controls="description"
                    aria-selected="true"
                  >
                    Mô tả
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    style={{ fontFamily: "Raleway" }}
                    className="nav-link"
                    id="vendor-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#vendor"
                    type="button"
                    role="tab"
                    aria-controls="vendor"
                    aria-selected="false"
                  >
                    Đánh giá
                  </button>
                </li>
              </ul>
              {/* Tab */}

              <div className="tab-content">
                <div
                  className="tab-pane fade show active"
                  id="description"
                  role="tabpanel"
                  aria-labelledby="description-tab"
                >
                  <span style={{ fontFamily: "Raleway", fontSize: "15px" }}>
                    {product.description}
                  </span>
                </div>
                {/* Đánh giá */}
                <div
                  className="tab-pane fade"
                  id="vendor"
                  role="tabpanel"
                  aria-labelledby="vendor-tab"
                >
                  <div className="table-responsive">
                    {/* Đánh giá */}
                    {product.reviews.map((review: any, index: any) => (
                      <>
                        <section className="layout-rating" key={index}>
                          <div>
                            <img
                              className="img-rating"
                              src={`${review.user_avatar}`}
                              alt=""
                            />
                          </div>
                          <div className="text-rating">
                            <span className="name-user">
                              {review.user_name}
                            </span>
                            {/* {dayjs(review.created_at).format('DD/MM/YYYY HH:mm:ss')} */}
                            <div className="star-ratings">
                              <Rate disabled defaultValue={review.rating} />
                            </div>
                            <p
                              style={{ fontSize: "14px" }}
                              className="content-rating"
                            >
                              {review.comment}
                            </p>
                          </div>
                        </section>
                        <hr className="hr-rating" />
                      </>
                    ))}

                    {/* end */}
                  </div>
                </div>
                {/* end */}
              </div>
            </div>
            {/* Sản phẩm cùng danh mục */}
            <section className="section block-may-also-like recent-viewed">
              <div className="container">
                <div className="top-head justify-content-center">
                  <h4 className="text-uppercase brand-1 brush-bg">
                    Sản phẩm liên quan
                  </h4>
                </div>
                <div className="row">
                  {productById.map((product, index) => (
                    <div className="col-lg-3 col-sm-6">
                      <Link to={`/product-detail/${product.id}`}>
                        <div className="cardProduct wow fadeInUp" key={index}>
                          <div className="cardImage">
                            {/* <label className="lbl-hot">hot</label> */}
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
                          </div>
                          <div className="cardInfo">
                            <h6
                              style={{
                                fontFamily: "Raleway",
                                fontWeight: "normal",
                              }}
                              className=" cardTitle"
                            >
                              {product.name}
                            </h6>
                            <p
                              style={{ fontFamily: "Raleway" }}
                              className="font-lg cardDesc"
                            >
                              {" "}
                              {Math.round(product.price).toLocaleString("vi", {
                                style: "currency",
                                currency: "VND",
                              })}
                            </p>
                          </div>
                        </div>
                      </Link>
                    </div>
                  ))}
                </div>
              </div>
            </section>
          </div>
        </section>
      </main>
    </>
  );
};

export default ProductDetailComponent;
