import React from "react";

function Footer() {
  return (
    <footer style={styles.footer}>
      <div style={styles.container}>
        <div style={styles.section}>
          <h3>Về chúng tôi</h3>
          <p>
            Chúng tôi cung cấp các sản phẩm chất lượng cao và dịch vụ tận tâm
            cho khách hàng.
          </p>
        </div>
        <div style={styles.section}>
          <h3>Liên hệ</h3>
          <p>Địa chỉ: Quận 1, Hồ Chí Minh</p>
          <p>Email: contact@example.com</p>
          <p>Điện thoại: 0123 456 789</p>
        </div>
        <div style={styles.section}>
          <h3>Theo dõi chúng tôi</h3>
          <p>Facebook | Instagram | Twitter</p>
        </div>
      </div>
      <div style={styles.copyRight}>
        &copy; 2025 Your Store. All rights reserved.
      </div>
    </footer>
  );
}

const styles: { [key: string]: React.CSSProperties } = {
  footer: {
    backgroundColor: "#333",
    color: "#fff",
    padding: "40px 20px",
    marginTop: "50px",
  },
  container: {
    display: "flex",
    justifyContent: "space-between",
    flexWrap: "wrap",
    maxWidth: "1200px",
    margin: "0 auto",
  },
  section: {
    flex: "1",
    minWidth: "200px",
    margin: "10px",
  },
  copyRight: {
    textAlign: "center",
    marginTop: "20px",
    fontSize: "14px",
    color: "#aaa",
  },
};

export default Footer;
