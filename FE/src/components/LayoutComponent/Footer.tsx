import React from "react";

function Footer() {
  return (
    <footer className="bg-white text-dark pt-5 pb-3 mt-5 border-top">
      <div className="container">
        <div className="row">
          {/* Thể loại */}
          <div className="col-md-3 mb-4">
            <h5 className="text-uppercase mb-3 fw-bold">THỂ LOẠI</h5>
            <ul className="list-unstyled">
              <li>
                <a href="#" className="text-dark text-decoration-none">
                  Áo câu lạc bộ
                </a>
              </li>
              <li>
                <a href="#" className="text-dark text-decoration-none">
                  Áo đội tuyển quốc gia
                </a>
              </li>
              <li>
                <a href="#" className="text-dark text-decoration-none">
                  Áo trending
                </a>
              </li>
            </ul>
          </div>

          {/* Trợ giúp */}
          <div className="col-md-3 mb-4">
            <h5 className="text-uppercase mb-3 fw-bold">TRỢ GIÚP</h5>
            <ul className="list-unstyled">
              <li>
                <a href="#" className="text-dark text-decoration-none">
                  Đặt hàng
                </a>
              </li>
              <li>
                <a href="#" className="text-dark text-decoration-none">
                  Phản hồi
                </a>
              </li>
              <li>
                <a href="#" className="text-dark text-decoration-none">
                  Giao hàng
                </a>
              </li>
              <li>
                <a href="#" className="text-dark text-decoration-none">
                  Câu hỏi
                </a>
              </li>
            </ul>
          </div>

          {/* Liên lạc */}
          <div className="col-md-3 mb-4">
            <h5 className="text-uppercase mb-3 fw-bold">LIÊN LẠC</h5>
            <p>Bạn có câu hỏi nào cho cửa hàng không?</p>
            <p>Nếu cần sự trợ giúp vui lòng liên hệ</p>
            <p>📞 0123456789</p>
            <div>
              <a href="#" className="text-dark me-3 fs-5">
                📘
              </a>
              <a href="#" className="text-dark me-3 fs-5">
                📸
              </a>
              <a href="#" className="text-dark fs-5">
                📌
              </a>
            </div>
          </div>

          {/* Bản tin */}
          <div className="col-md-3 mb-4">
            <h5 className="text-uppercase mb-3 fw-bold">BẢN TIN</h5>
            <form>
              <div className="mb-2">
                <input
                  type="email"
                  className="form-control"
                  placeholder="110store@gmail.com"
                />
              </div>
              <button
                type="submit"
                className="btn bg-danger-subtle w-100 rounded-pill"
              >
                ĐẶT MUA
              </button>
            </form>
          </div>
        </div>

        {/* Thẻ thanh toán */}
        <div className="text-center mt-4">
          <span className="me-2 fs-4">💳</span>
          <span className="me-2 fs-4">💰</span>
          <span className="fs-4">🏦</span>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
