import React from "react";
import { Link } from "react-router-dom";
const Header = () => {
  return (
    <div>
      <header className="logo" style={styles.header}>
        {/* Logo */}
        <div style={styles.logo}>
          <img
            src="/img/snapedit_1749109524248.png"
            alt="Logo"
            style={{ height: "60px" }}
          />
        </div>

        {/* Menu */}
        <nav style={styles.nav}>
          <Link to="/" style={styles.link}>
            Trang chủ
          </Link>
          <Link to="/shop" style={styles.link}>
            Cửa hàng
          </Link>
          <Link to="/checkout" style={styles.link}>
            Thanh toán
          </Link>
          <Link to="/blog" style={styles.link}>
            Bài viết
          </Link>
          <Link to="/about" style={styles.link}>
            Về tôi
          </Link>
          <Link to="/contact" style={styles.link}>
            Liên hệ
          </Link>
        </nav>

        {/* Icons */}
        <div style={styles.icons}>
          <span role="img" aria-label="search" style={styles.icon}>
            🔍
          </span>
          <span role="img" aria-label="cart" style={styles.icon}>
            🛒
          </span>
          <span role="img" aria-label="heart" style={styles.icon}>
            🤍
          </span>
        </div>
      </header>
    </div>
  );
};
const styles = {
  header: {
    display: "flex",
    alignItems: "center",
    justifyContent: "space-between",
    background: "#999",
    padding: "10px 40px",
  },
  logo: {
    flex: "1",
  },
  nav: {
    flex: "2",
    display: "flex",
    justifyContent: "center",
    gap: "30px",
  },
  link: {
    textDecoration: "none",
    color: "black",
    fontWeight: "bold",
  },
  icons: {
    flex: "1",
    display: "flex",
    justifyContent: "flex-end",
    gap: "20px",
    fontSize: "24px",
  },
  icon: {
    cursor: "pointer",
  },
};

export default Header;
