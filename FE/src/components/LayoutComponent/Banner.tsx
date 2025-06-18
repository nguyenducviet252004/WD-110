import React from "react";
import "./style.css";

function Banner() {
  return (
    <div className="sec-banner bg0 p-t-80 p-b-50">
      <div className="container">
        <div className="row">
          <div className="col-md-6 col-xl-4 p-b-30 m-lr-auto">
            <div className="block1 wrap-pic-w">
              <img
                src="/img/qua-bong-da-dong-luc_5523_7493_HasThumb_Thumb.webp"
                alt="Banner 1"
              />
              <a
                href="/shop"
                className="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3"
              >
                <div className="block1-txt-child1 flex-col-l">
                  <span className="block1-name ltext-102 trans-04 p-b-8">
                    Nữ
                  </span>
                  <span className="block1-info stext-102 trans-04">
                    Xuân 2025
                  </span>
                </div>
                <div className="block1-txt-child2 p-b-4 trans-05">
                  <div className="block1-link stext-101 cl0 trans-09">
                    Mua ngay
                  </div>
                </div>
              </a>
            </div>
          </div>
          {/* Thêm block khác tương tự nếu muốn */}
        </div>
      </div>
    </div>
  );
}

export default Banner;
