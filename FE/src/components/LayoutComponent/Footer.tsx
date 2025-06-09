import React from "react";
import "./Footer.css";

const Footer = () => {
  return (
    <footer className="footer">
      <div className="footer-column">
        <h3>THỂ LOẠI</h3>
        <ul>
          <li>Áo đội tuyển</li>
          <li>Áo câu lạc bộ</li>
          <li>Áo training</li>
        </ul>
      </div>
      <div className="footer-column">
        <h3>TRỢ GIÚP</h3>
        <ul>
          <li>Đặt hàng</li>
          <li>Phản hồi</li>
          <li>Giao hàng</li>
          <li>Câu hỏi</li>
        </ul>
      </div>
      <div className="footer-column">
        <h3>LIÊN LẠC</h3>
        <p>Bạn có câu hỏi nào cho cửa hàng không?</p>
        <p>Nếu cần sự trợ giúp vui lòng liên hệ</p>
        <p>0388888888</p>
      </div>
      <div className="footer-column">
        <h3>BẢN TIN</h3>
        <input type="email" placeholder="email@example.com" />
        <button>ĐẶT MUA</button>
      </div>
    </footer>
  );
};

export default Footer;
