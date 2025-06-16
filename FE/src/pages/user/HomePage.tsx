import React from "react";
import { Link } from "react-router-dom";

const HomePage = () => {
  return (
    <div>
      {/* Top Bar */}
      <div className="bg-black text-white text-sm py-2 px-4 text-center">
        Miễn phí vận chuyển cho đơn từ 1tr VNĐ
      </div>

      {/* Navbar */}
      <nav className="bg-white shadow flex justify-between items-center px-6 py-4">
        <div className="text-xl font-bold text-gray-800">LOGO</div>
        <ul className="hidden md:flex gap-6 text-sm">
          <li>
            <Link to ={"/"} className="hover:text-blue-500">
            <a href="#" className="hover:text-blue-500">
              Trang Chủ
            </a></Link>
          </li>
          <li>
            <Link to={"/shop"} className="hover:text-blue-500">
              <a href="#" className="hover:text-blue-500">
                Sản Phẩm
              </a>
            </Link>
          </li>
          <li>
            <Link to={"/blog"} className="hover:text-blue-500">
              <a href="#" className="hover:text-blue-500">
                Blog
              </a>
            </Link>
          </li>
          <li>
            <Link to={"/about"} className="hover:text-blue-500">
              <a href="#" className="hover:text-blue-500">
                Về Chúng Tôi
              </a>
            </Link>
          </li>
        </ul>
      </nav>

      {/* Slider */}
      <div
        className="w-full h-[400px] bg-cover bg-center"
        style={{
          backgroundImage:
            "url('https://source.unsplash.com/featured/?fashion')",
        }}
      >
        <div className="flex items-center justify-center h-full bg-black/30">
          <div className="text-center text-white">
            <h1 className="text-4xl font-bold mb-4">Bộ sưu tập mới</h1>
            <button className="bg-white text-black px-4 py-2 rounded hover:bg-gray-200">
              Mua ngay
            </button>
          </div>
        </div>
      </div>

      {/* Products Section */}
      <section className="py-12 px-4 max-w-6xl mx-auto">
        <h2 className="text-2xl font-bold mb-8">Sản phẩm nổi bật</h2>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
          {Array.from({ length: 4 }).map((_, idx) => (
            <div
              key={idx}
              className="border p-4 rounded shadow hover:shadow-lg transition"
            >
              <img
                src={`https://source.unsplash.com/300x300/?clothes,${idx}`}
                alt="Product"
                className="mb-2"
              />
              <p className="font-semibold">Áo thời trang</p>
              <p className="text-red-500">199.000đ</p>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
};

export default HomePage;
