import React from "react";
import "./Header.css";
import logo from "../../assets/snapedit_1749109524248.png";

function Header() {
  return (
    <header className="header">
      <section className="logo">
        <img className="logo1" src={logo} alt="Banner Bóng Động Lực" />
      </section>
      <nav>
        <ul>
          <li>
            <a href="#">Trang chủ</a>
          </li>
          <li>
            <a href="#">Cửa hàng</a>
          </li>
          <li>
            <a href="#">Thanh toán</a>
          </li>
          <li>
            <a href="#">Bài viết</a>
          </li>
          <li>
            <a href="#">Về tôi</a>
          </li>
          <li>
            <a href="#">Liên hệ</a>
          </li>
        </ul>
      </nav>
      <div className="icons">🔍 🛒 🤍</div>
    </header>
  );
}

export default Header;
