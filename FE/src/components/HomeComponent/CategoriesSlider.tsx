// CategoriesSlider.tsx
import React from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Pagination } from "swiper/modules";
import Cat from "../../assets/imgs/page/homepage3/cart1.png";
import CatOne from "../../assets/imgs/page/homepage3/cart2.png";
import CatTwo from "../../assets/imgs/page/homepage3/cart3.jpg";
import CatThree from "../../assets/imgs/page/homepage3/cart4.jpg";
import CatFour from "../../assets/imgs/page/homepage3/cart5.jpg";
import CatFive from "../../assets/imgs/page/homepage3/cart6.jpg";
import CatSix from "../../assets/imgs/page/homepage3/cart7.jpg";
import CatSeven from "../../assets/imgs/page/homepage3/cart8.jpg";
import CatEight from "../../assets/imgs/page/homepage3/cart9.jpg";

const CategoriesSlider: React.FC = () => {
  return (
    <>
      <div className="section block-section block-section-categories-slider wow animate__animated animate__fadeIn">
        <div className="container">
          <div className="box-swiper">
            <Swiper
              modules={[Pagination]} // Sử dụng module Pagination
              slidesPerView={6} // Chỉnh số slide hiển thị trên mỗi trang
              pagination={{ clickable: true }} // Bật pagination
              className="swiper-9-items pb-0"
            >
              {/* Thêm các slide */}
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={Cat} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Kids Toys</a>
                  </div>
                </div>
              </SwiperSlide>
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={CatOne} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Teddy Bear</a>
                  </div>
                </div>
              </SwiperSlide>
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={CatTwo} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Boys</a>
                  </div>
                </div>
              </SwiperSlide>
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={CatThree} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Shoes</a>
                  </div>
                </div>
              </SwiperSlide>
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={CatFour} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Cribs</a>
                  </div>
                </div>
              </SwiperSlide>
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={CatFive} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Wood Toys</a>
                  </div>
                </div>
              </SwiperSlide>
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={CatSix} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Moms</a>
                  </div>
                </div>
              </SwiperSlide>
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={CatSeven} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Baby</a>
                  </div>
                </div>
              </SwiperSlide>
              <SwiperSlide>
                <div className="cardCategory">
                  <div className="cardImage">
                    <a href="#">
                      <img src={CatEight} alt="kidify" />
                    </a>
                  </div>
                  <div className="cardInfo">
                    <a href="#">Cute Collection</a>
                  </div>
                </div>
              </SwiperSlide>
            </Swiper>
            <div className="box-pagination-button">
              <div className="swiper-pagination swiper-pagination-center-bottom swiper-pagination-items-9"></div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default CategoriesSlider;
