import React, { useState, useEffect } from "react";
import "./Header.css";
import { Link, useNavigate } from "react-router-dom";

function Header() {
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const timer = setTimeout(() => setLoading(false), 1000); // 1 giây
    return () => clearTimeout(timer);
  }, []);

  const handleLogoClick = () => {
    navigate("/");
  };
if (loading) return <div>Loading...</div>
  return (
    <header className="header">
      <div className="logo">
        {loading ? (
          <img
            src="/images/logo-loading.gif"
            alt="Loading..."
            style={{ cursor: "pointer" }}
            onClick={handleLogoClick}
          />
        ) : (
          <Link to={"/"}>
            <img src="/images/logo.png" alt="Logo" />
          </Link>
        )}
      </div>
      <nav>
        <ul>
          <li>
            <a href="#">Trang chủ</a>
          </li>
          <li>
            <a href="#">Cửa hàng</a>
          </li>
          <Link to={"/cart"}>
            <li>
              <a href="#">Thanh toán</a>
            </li>
          </Link>
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
