import React from "react";
import "./style.css";
import { Link } from "react-router-dom";

function Header() {
  return (
    <header>
      <div className="container-menu-desktop">
        <div className="wrap-menu-desktop">
          <nav className="limiter-menu-desktop container">
            <Link to="/" className="logo">
              <img src="/img/snapedit_1749109524248.png" alt="Logo" />
            </Link>

            <div className="menu-desktop">
              <ul className="main-menu">
                <li className="active-menu">
                  <Link to="/">Trang chủ</Link>
                  <ul className="sub-menu">
                    <li>
                      <Link to="/">Trang chủ 1</Link>
                    </li>
                  </ul>
                </li>
                <li>
                  <Link to="/shop">Cửa hàng</Link>
                </li>
                <li>
                  <Link to="/cart">Thanh toán</Link>
                </li>
                <li>
                  <Link to="/blog">Bài viết</Link>
                </li>
                <li>
                  <Link to="/about">Về tôi</Link>
                </li>
                <li>
                  <Link to="/contact">Liên hệ</Link>
                </li>
              </ul>
            </div>

            <div className="wrap-icon-header flex-w flex-r-m">
              <div className="icon-header-item js-show-modal-search">
                <i className="zmdi zmdi-search"></i>
              </div>
              <div
                className="icon-header-item icon-header-noti js-show-cart"
                data-notify="0"
              >
                <i className="zmdi zmdi-shopping-cart"></i>
              </div>
              <a
                href="#"
                className="icon-header-item icon-header-noti"
                data-notify="0"
              >
                <i className="zmdi zmdi-favorite-outline"></i>
              </a>
            </div>
          </nav>
        </div>
      </div>
    </header>
  );
}

export default Header;
