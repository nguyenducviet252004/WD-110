import React from "react";
import api from "../../Axios/Axios";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination } from "swiper/modules";
import Banner from "../../assets/imgs/page/homepage1/banner1.jpg";
import BannerTwo from "../../assets/imgs/page/homepage1/banner2.webp";
import Sale from "../../assets/imgs/page/homepage1/sale.png";
import Leaf from "../../assets/imgs/page/homepage1/leaf.png";
import Star from "../../assets/imgs/page/homepage1/star.png";
import Arrow from "../../assets/imgs/template/icons/arrow.svg";
import { useEffect, useState } from "react";
const BannerComponent: React.FC = () => {
  const [banner, setBanner] = useState<any>();
  const [banner2, setBanner2] = useState<any>();
  const [bannerData, setBannerData] = useState<any>();
  const [bannerDes, setBannerDes] = useState<any>();
  const [bannerTitle, setBannerTitle] = useState<any>();
  const [bannerDes2, setBannerDes2] = useState<any>();

  useEffect(() => {
    const GetLogo = async () => {
      try {
        const { data } = await api.get(`/logobanner/${2}`);
        setBanner(data.image);
        setBannerTitle(data.title);
        setBannerDes2(data.description);
      } catch (error) {
        console.log(error);
      }
    };
    const GetLogo2 = async () => {
      try {
        const { data } = await api.get(`/logobanner/${3}`);
        setBanner2(data.image);
        setBannerData(data.title);
        setBannerDes(data.description);
      } catch (error) {
        console.log(error);
      }
    };
    GetLogo();
    GetLogo2();
  }, []);

  console.log("Banner", bannerData);

  return (
    <section className="section banner-homepage1">
      <div className="container">
        <div className="box-swiper">
          <Swiper
            modules={[Navigation, Pagination]}
            navigation
            pagination={{ clickable: true }}
            className="swiper-banner pb-0"
          >
            <SwiperSlide>
              <div className="box-banner-home1">
                <div
                  className="box-cover-image wow animate__animated animate__fadeInLeft"
                  style={{ backgroundImage: `url(${banner})` }}
                />
                <div className="box-banner-info">
                  {/* <div className="block-sale wow animate__animated animate__fadeInTop">
                                        <img src={Sale} alt="Kidify" />
                                    </div> */}
                  <div className="blockleaf rotateme">
                    <img src={Leaf} alt="Kidify" />
                  </div>
                  <div className="block-info-banner">
                    <p className="font-3xl-bold neutral-900 title-line mb-10 wow animate__animated animate__zoomIn">
                      {bannerTitle}
                    </p>
                    <h2 className="heading-banner mb-10 wow animate__animated animate__zoomIn">
                      <span className="text-up">{bannerDes2}</span>
                      <span className="text-under">{bannerDes2}</span>
                    </h2>
                    <h4 className="heading-4 title-line-2 mb-30 wow animate__animated animate__zoomIn">
                      Thời trang đá bóng
                    </h4>
                    <div className="text-center mt-10">
                      <a
                        className="btn btn-double-border wow animate__animated animate__zoomIn"
                        href="/product"
                      >
                        <span>Mua sắm ngay</span>
                      </a>
                      {/* <a className="btn btn-arrow-right wow animate__animated animate__zoomIn" href="#">
                                                Learn More
                                                <img src={Arrow} alt="Kidify" />
                                            </a> */}
                    </div>
                  </div>
                </div>
              </div>
            </SwiperSlide>
            <SwiperSlide>
              <div className="box-banner-home1">
                <div
                  className="box-cover-image wow animate__animated animate__fadeInLeft"
                  style={{ backgroundImage: `url(${banner2})` }}
                />
                <div className="box-banner-info wow animate__animated animate__zoomIn">
                  {/* <div className="block-sale wow animate__animated animate__fadeInTop">
                                        <img src={Sale} alt="Kidify" />
                                    </div> */}
                  <div className="blockleaf rotateme">
                    <img src={Star} alt="Kidify" />
                  </div>
                  <div className="block-info-banner">
                    <p className="font-3xl-bold neutral-900 title-line mb-10 wow animate__animated animate__zoomIn">
                      {bannerData}
                    </p>
                    <h2 className="heading-banner mb-10 wow animate__animated animate__zoomIn">
                      <span className="text-up">{bannerDes}</span>
                      <span className="text-under">{bannerDes}</span>
                    </h2>
                    <h4 className="heading-4 title-line-2 mb-30 wow animate__animated animate__zoomIn">
                      Thời trang đá bóng
                    </h4>
                    <div className="text-center mt-10">
                      <a
                        className="btn btn-double-border wow animate__animated animate__zoomIn"
                        href="/product"
                      >
                        <span>Mua sắm ngay</span>
                      </a>
                      {/* <a className="btn btn-arrow-right wow animate__animated animate__zoomIn" href="#">
                                                Learn More
                                        
                                            </a> */}
                    </div>
                  </div>
                </div>
              </div>
            </SwiperSlide>
          </Swiper>
        </div>
      </div>
    </section>
  );
};

export default BannerComponent;
