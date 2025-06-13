import React from "react";
import "./HomePage.css";
import { Link, Route } from "react-router-dom";
import Header from "../components/LayoutComponent/Header";
import Footer from "../components/LayoutComponent/Footer";
const products = [
  {
    id: 1,
    name: "Bóng Động Lực",
    price: "200.000₫",
    image:
      "https://www.sporter.vn/wp-content/uploads/2017/06/Vn-tottenham-trang-2024-1.jpg",
  },
  {
    id: 2,
    name: "Bóng Cobra",
    price: "250.000₫",
    image:
      "https://www.sporter.vn/wp-content/uploads/2017/06/Ao-bong-da-mu-trang-2526-0.jpg",
  },
  {
    id: 3,
    name: "Bóng Galaxy",
    price: "300.000₫",
    image:
      "https://www.sporter.vn/wp-content/uploads/2017/06/Vn-man-city-xanh-bien-2024-1.jpg",
  },
  {
    id: 3,
    name: "Bóng Galaxy",
    price: "300.000₫",
    image:
      "https://www.sporter.vn/wp-content/uploads/2017/06/Vn-man-city-xanh-bien-2024-1.jpg",
  },
];

function HomePage() {
  return (
    <div>
      <Header />

      {/* Banner */}
      <div>
        <div>
          <img
            src="https://aobongda.vn/wp-content/uploads/2024/03/banner-2.jpg"
            alt="Banner"
            style={{ width: "100%", height: "auto" }}
          />
        </div>
      </div>
      {/* menu */}
      <div>
        <div className="menu-container">
          <Link to="/category/women" className="category-card">
            <h2>Áo World Cup</h2>
            <p>Thời trang mùa thu</p>
            <img src="" alt="Women" />
          </Link>

          <Link to="/category/women" className="category-card">
            <h2>Áo World Cup</h2>
            <p>Thời trang mùa thu</p>
            <img src="/img/women.png" alt="Women" />
          </Link>

          <Link to="/category/hats" className="category-card">
            <h2>Áo Câu lạc bộ</h2>
            <p>Hiện đại</p>
            <img src="/img/hat.png" alt="Hat" />
          </Link>
        </div>
      </div>
      {/* sản phẩm */}
      <div>
        <section style={styles.productsSection}>
          <h2 style={styles.sectionTitle}>Sản phẩm nổi bật</h2>
          <div style={styles.productGrid}>
            {products.map((product) => (
              <div key={product.id} style={styles.productCard}>
                <img
                  src={product.image}
                  alt={product.name}
                  style={styles.productImage}
                />
                <h3>{product.name}</h3>
                <p style={styles.price}>{product.price}</p>
                <button style={styles.button}>Mua ngay</button>
              </div>
            ))}
          </div>
        </section>
      </div>

      {/* Footer */}
      <Footer />
    </div>
  );
}

const styles: { [key: string]: React.CSSProperties } = {
  productsSection: {
    padding: "40px 20px",
    textAlign: "center",
  },
  sectionTitle: {
    marginBottom: "30px",
  },
  productGrid: {
    display: "grid",
    gridTemplateColumns: "repeat(auto-fit, minmax(200px, 1fr))",
    gap: "30px",
    maxWidth: "1200px",
    margin: "0 auto",
  },
  productCard: {
    border: "1px solid #ddd",
    padding: "20px",
    borderRadius: "8px",
    textAlign: "center",
  },
  productImage: {
    width: "100%",
    height: "200px",
    objectFit: "cover",
    marginBottom: "10px",
  },
  price: {
    fontWeight: "bold",
    color: "#333",
  },
  button: {
    marginTop: "10px",
    padding: "10px 20px",
    background: "#333",
    color: "#fff",
    border: "none",
    borderRadius: "5px",
    cursor: "pointer",
  },
};

export default HomePage;
