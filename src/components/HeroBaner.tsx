import React from "react";

const HeroBanner = () => {
  return (
    <div
      className="w-full h-64 bg-cover bg-center relative"
      style={{ backgroundImage: "url('/images/banner.jpg')" }}
    >
      <div className="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <h1 className="text-white text-3xl md:text-5xl font-bold">
          Cửa hàng bóng đá Online
        </h1>
      </div>
    </div>
  );
};

export default HeroBanner;
