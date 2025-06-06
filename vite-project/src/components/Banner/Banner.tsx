import React from "react";
import "./Banner.css";
import bannerImage from "../../assets/qua-bong-da-dong-luc_5523_7493_HasThumb_Thumb.webp"; // đổi tên theo ảnh bạn có

function Banner() {
  return (
    <section className="banner">
      <img src={bannerImage} alt="Banner Bóng Động Lực" />
    </section>
  );
}

export default Banner;
