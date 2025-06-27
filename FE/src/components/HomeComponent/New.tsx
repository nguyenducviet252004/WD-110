import Blog from "../../assets/imgs/page/homepage1/blog1.webp";
import BlogTwo from "../../assets/imgs/page/homepage1/blog2.webp";
import BlogThree from "../../assets/imgs/page/homepage1/blog3.webp";
import Arrow from "../../assets/imgs/template/icons/arrow-hover.svg";
import ArrowHover from "../../assets/imgs/template/icons/arrow-hover.svg";
import Ig from "../../assets/imgs/page/homepage1/blog4.webp";
import IgOne from "../../assets/imgs/page/homepage1/th1.jpg";
import IgThree from "../../assets/imgs/page/homepage1/th2.jpg";
import IgFour from "../../assets/imgs/page/homepage1/th3.jpg";
import IgTwo from "../../assets/imgs/page/homepage1/th4.jpg";
import IgFive from "../../assets/imgs/page/homepage1/th5.jpg";
import Promotion from "../../assets/imgs/template/promotion.png";
import PromotionBanner from "../../assets/imgs/template/promotion-banner.png";
import { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import { message, Pagination } from "antd";
import type { PaginationProps } from "antd";
import axios from "axios";
import "./new.css";
import api from "../../Axios/Axios";
const New: React.FC = () => {
  const [blog, setBlog] = useState<any[]>([]);
  const [blog4, setBlog4] = useState<any>();
  const pageSize = 3;
  const [current, setCurrent] = useState(1);

  const onChange: PaginationProps["onChange"] = (page) => {
    console.log(page);
    setCurrent(page);
  };

  useEffect(() => {
    const GetLogo = async () => {
      try {
        const { data } = await axios.get(`http://127.0.0.1:8000/api/blog`);
        setBlog(data);
      } catch (error) {
        console.log(error);
      }
    };
    const GetQuangCao = async () => {
      try {
        const { data } = await axios.get(
          `http://127.0.0.1:8000/api/logobanner/${4}`
        );
        setBlog4(data);
      } catch (error) {
        console.log(error);
      }
    };
    GetLogo();
    GetQuangCao();
  }, []);

  console.log("Blog 4", blog4);

  const paginatedBlog = blog.slice(
    (current - 1) * pageSize,
    current * pageSize
  );

  const maxLength = 140;
  const truncateText = (text: any) => {
    if (text.length > maxLength) {
      return text.substring(0, maxLength) + "...";
    }
    return text;
  };

  const [isOpen, setIsOpen] = useState(true);
  const closeModal = (e: React.MouseEvent) => {
    e.preventDefault();
    setIsOpen(false);
  };
  return (
    <>
      <section className="section block-section-8">
        <div className="container">
          <div className="text-center">
            <h4 className="text-uppercase brand-1 mb-15 brush-bg wow animate__fadeIn animated">
              Tin tức và sự kiện mới nhất
            </h4>
            <p className="font-lg neutral-500 mb-30 wow animate__animated animate__fadeIn">
              Đừng bỏ lỡ những tin tức khuyến mại tuyệt vời hoặc
              <br className="d-none d-lg-block" />
              các sự kiện sắp tới trong hệ thống cửa hàng của chúng tôi
            </p>
          </div>
          <div className="row">
            {paginatedBlog.map((b, index) => (
              <div
                key={index}
                className="col-lg-4 col-md-6 wow animate__animated animate__fadeIn"
                data-wow-delay="0s"
              >
                <Link to={`/blog-detail/${b.id}`}>
                  <div className="cardBlog wow fadeInUp">
                    <div className="cardImage">
                      {/* <div className="box-date-info">
                                        <div className="box-inner-date">
                                            <div className="heading-6">21</div>
                                            <p className="font-md neutral-900">Jun</p>
                                        </div>
                                    </div> */}
                      <a>
                        <img
                          src={b.image}
                          alt="kidify"
                          style={{ width: "100%", height: "350px" }}
                        />
                      </a>
                    </div>
                    <div className="cardInfo">
                      <a className="cardTitle">
                        <h5 className="font-xl-bold">{b.title}</h5>
                      </a>
                      <p className="cardDesc font-lg neutral-500">
                        {truncateText(b.description)}
                      </p>
                      <a className="btn btn-arrow-right">
                        Xem chi tiết
                        <img src={Arrow} alt="Kidify" />
                        <img
                          className="hover-icon"
                          src={ArrowHover}
                          alt="Kidify"
                        />
                      </a>
                    </div>
                  </div>
                </Link>
              </div>
            ))}
          </div>
          <nav className="box-pagination" style={{ float: "right" }}>
            <Pagination
              current={current}
              onChange={onChange}
              total={blog.length}
              pageSize={pageSize}
            />
          </nav>
        </div>
      </section>
      <section className="section block-section-10">
        <div className="container">
          <div className="top-head justify-content-center">
            <h4 className="text-uppercase brand-1 wow fadeInDown">
              instagram feed
            </h4>
          </div>
        </div>
        <div className="box-gallery-instagram">
          <div className="box-gallery-instagram-inner">
            <div className="gallery-item wow fadeInLeft">
              <img src={Ig} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInUp">
              <img src={IgTwo} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInUp">
              <img src={IgThree} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInUp">
              <img src={IgFour} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInRight">
              <img src={IgFive} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInRight">
              <img src={IgOne} alt="kidify" />
            </div>
          </div>
        </div>
      </section>
      {/* Quảng cáo  */}
      {blog4 && (
        <div>
          {isOpen && (
            <div className="box-popup-newsletter">
              <div className="box-newsletter-overlay" onClick={closeModal} />{" "}
              {/* Overlay có thể đóng modal */}
              <div className="box-newsletter-wrapper">
                <div className="box-newsletter-inner">
                  <a
                    className="btn-close-popup btn-close-popup-newsletter"
                    href="#"
                    onClick={closeModal}
                  >
                    <svg
                      className="icon-16 d-inline-flex align-items-center justify-content-center"
                      fill="#111111"
                      stroke="#111111"
                      width={24}
                      height={24}
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </a>
                  <div className="promotion-content">
                    <div className="block-info-banner">
                      <p className="font-3xl-bold neutral-900 title-line mb-10 wow animate__animated animate__zoomIn">
                        Winter
                      </p>
                      <h2 className="heading-banner mb-10 wow animate__animated animate__shakeX">
                        <span className="text-up">{blog4?.title}</span>
                        <span className="text-under">{blog4?.title}</span>
                      </h2>
                      <h4 className="heading-4 title-line-2 mb-30 wow animate__animated animate__zoomIn">
                        {blog4?.description}
                      </h4>
                      <div className="mt-10">
                        <a
                          className="btn btn-double-border wow animate__animated animate__zoomIn"
                          href="/product"
                        >
                          <span>Mua sắm ngay</span>
                        </a>
                      </div>
                    </div>
                    {/* <div className="promotion-label">
                  <img src={Promotion} alt="Kidify" />
                </div> */}
                    <div className="promotion-banner">
                      <img src={blog4?.image} alt="Kidify" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      )}
    </>
  );
};
export default New;
