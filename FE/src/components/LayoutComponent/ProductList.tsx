import React from "react";
import "./style.css"

function ProductList() {
  return (
    <section className="bg0 p-t-23 p-b-140">
      <div className="container">
        <div className="p-b-10">
          <h3 className="ltext-103 cl5">Sản phẩm</h3>
        </div>
        <div className="row isotope-grid">
          {[1, 2, 3, 4].map((i) => (
            <div
              key={i}
              className="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item"
            >
              <div className="block2">
                <div className="block2-pic hov-img0">
                  <img src={`/images/product-0${i}.jpg`} alt={`Product ${i}`} />
                  <a
                    href="#"
                    className="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04"
                  >
                    Xem nhanh
                  </a>
                </div>
                <div className="block2-txt flex-w flex-t p-t-14">
                  <div className="block2-txt-child1 flex-col-l">
                    <a
                      href="#"
                      className="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6"
                    >
                      Tên sản phẩm {i}
                    </a>
                    <span className="stext-105 cl3">XX VND</span>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

export default ProductList;
