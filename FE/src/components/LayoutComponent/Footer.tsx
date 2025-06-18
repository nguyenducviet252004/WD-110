import React from "react";
import "./style.css";

function Footer() {
  return (
    <footer className="bg3 p-t-75 p-b-32">
      <div className="container">
        <div className="row">
          <div className="col-sm-6 col-lg-3 p-b-50">
            <h4 className="stext-301 cl0 p-b-30">Danh mục</h4>
            <ul>
              <li className="p-b-10">
                <a href="#" className="stext-107 cl7 hov-cl1 trans-04">
                  Nữ
                </a>
              </li>
              <li className="p-b-10">
                <a href="#" className="stext-107 cl7 hov-cl1 trans-04">
                  Nam
                </a>
              </li>
              <li className="p-b-10">
                <a href="#" className="stext-107 cl7 hov-cl1 trans-04">
                  Giày
                </a>
              </li>
              <li className="p-b-10">
                <a href="#" className="stext-107 cl7 hov-cl1 trans-04">
                  Đồng hồ
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
