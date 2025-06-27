import { useState, useEffect } from "react";
// import { Categories } from "../../types/product";
import { IProduct, Size, Color } from "../../types/cart-item";
import api from "../../Axios/Axios";
import { message } from "antd";
import { ArrowRightOutlined } from "@ant-design/icons";
import "./ProductComponent.css";
interface AsideFilterProps {
  setFilters: React.Dispatch<
    React.SetStateAction<{
      category: string | null;
      size: string | null;
      color: string | null;
      priceRange: [number, number] | null;
      brands: string[];
    }>
  >;
}

const AsideFilter: React.FC<AsideFilterProps> = ({ setFilters }) => {
  const [categories, setCategories] = useState<any[]>([]);
  const [sizes, setSizes] = useState<Size[]>([]);
  const [colors, setColors] = useState<Color[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null);
  const [priceRange, setPriceRange] = useState<[number, number] | null>(null);
  const [selectedSize, setSelectedSize] = useState<any | null>(null);
  const [selectedColor, setSelectedColor] = useState<any | null>(null);

  const GetAllCategory = async () => {
    try {
      const { data } = await api.get("/categories");
      setCategories(data);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  const GetAllProducts = async () => {
    try {
      const { data } = await api.get("/products");
      setSizes(data.all_sizes);
      setColors(data.all_colors);
    } catch (error) {
      message.error("Lỗi api !");
    }
  };

  useEffect(() => {
    GetAllCategory();
    GetAllProducts();
  }, []);

  const handleSizeClick = (size: string) => {
    setSelectedSize(size === selectedSize ? null : size);
    setFilters((prev) => ({
      ...prev,
      size: prev.size === size ? null : size,
    }));
  };

  const handleCategoryChange = (categoryName: string) => {
    if (selectedCategory === categoryName) {
      setSelectedCategory(null);
      setFilters((prevFilters) => ({ ...prevFilters, category: null }));
    } else {
      setSelectedCategory(categoryName);
      setFilters((prevFilters) => ({ ...prevFilters, category: categoryName }));
    }
  };

  const handleColorClick = (color: string) => {
    setSelectedColor(color === selectedColor ? null : color);
    setFilters((prev) => ({
      ...prev,
      color: prev.color === color ? null : color,
    }));
  };

  const handlePriceChange = (range: [number, number]) => {
    if (
      priceRange &&
      priceRange[0] === range[0] &&
      priceRange[1] === range[1]
    ) {
      setPriceRange(null);
      setFilters((prev) => ({ ...prev, priceRange: null }));
    } else {
      setPriceRange(range);
      setFilters((prev) => ({ ...prev, priceRange: range }));
    }
  };

  return (
    <div className="col-lg-3 order-lg-first">
      <div className="sidebar-left">
        <div className="box-filters-sidebar">
          <div className="row">
            <div className="col-lg-12 col-md-6">
              <h5
                style={{ fontFamily: "Raleway" }}
                className="font-3xl-bold mt-5"
              >
                Lọc sản phẩm
              </h5>
              <div className="block-filter">
                <h6 style={{ marginBottom: "15px", fontFamily: "Raleway" }}>
                  Danh mục
                </h6>
                <div className="box-collapse">
                  <ul className="list-filter-checkbox">
                    {categories.map((category) => (
                      <li key={category.id}>
                        <label className="cb-container">
                          <input
                            type="checkbox"
                            checked={selectedCategory === category.name}
                            onChange={() => handleCategoryChange(category.name)}
                          />
                          <span
                            style={{ fontFamily: "Raleway" }}
                            className="text-small"
                          >
                            {category.name}
                          </span>
                          <span className="checkmark" />
                        </label>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </div>
            {/* Giá tiền */}
            <div className="col-lg-12 col-md-6">
              <div className="block-filter">
                <h6 style={{ marginBottom: "15px", fontFamily: "Raleway" }}>
                  Giá tiền
                </h6>
                <div className="box-collapse">
                  <ul className="list-filter-checkbox">
                    <li>
                      <label className="cb-container">
                        <input
                          type="checkbox"
                          onChange={() => handlePriceChange([0, 499999])}
                          checked={
                            priceRange?.[0] === 0 && priceRange?.[1] === 499999
                          }
                        />
                        <span className="text-small">Dưới 500.000đ</span>
                        <span className="checkmark" />
                      </label>
                    </li>
                    <li>
                      <label className="cb-container">
                        <input
                          type="checkbox"
                          onChange={() => handlePriceChange([500000, 1000000])}
                          checked={
                            priceRange?.[0] === 500000 &&
                            priceRange?.[1] === 1000000
                          }
                        />
                        <span className="text-small">
                          Từ 500.000đ <ArrowRightOutlined /> 1.000.000đ
                        </span>
                        <span className="checkmark" />
                      </label>
                    </li>
                    <li>
                      <label className="cb-container">
                        <input
                          type="checkbox"
                          onChange={() => handlePriceChange([1000000, 1500000])}
                          checked={
                            priceRange?.[0] === 1000000 &&
                            priceRange?.[1] === 1500000
                          }
                        />
                        <span className="text-small">
                          Từ 1.000.000đ <ArrowRightOutlined /> 1.500.000đ
                        </span>
                        <span className="checkmark" />
                      </label>
                    </li>
                    <li>
                      <label className="cb-container">
                        <input
                          type="checkbox"
                          onChange={() => handlePriceChange([1500000, 2000000])}
                          checked={
                            priceRange?.[0] === 1500000 &&
                            priceRange?.[1] === 2000000
                          }
                        />
                        <span className="text-small">
                          Từ 1.500.000đ <ArrowRightOutlined /> 2.000.000đ
                        </span>
                        <span className="checkmark" />
                      </label>
                    </li>
                    <li>
                      <label className="cb-container">
                        <input
                          type="checkbox"
                          onChange={() =>
                            handlePriceChange([2000000, Infinity])
                          }
                          checked={
                            priceRange?.[0] === 2000000 &&
                            priceRange?.[1] === Infinity
                          }
                        />
                        <span className="text-small">Trên 2.000.000đ</span>
                        <span className="checkmark" />
                      </label>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            {/* Size */}
            <div className="col-lg-12 col-md-6">
              <div className="block-filter" style={{ cursor: "pointer" }}>
                <h6 style={{ marginBottom: "15px", fontFamily: "Raleway" }}>
                  Size
                </h6>
                <div className="box-collapse">
                  <div className="block-size">
                    <div className="list-sizes">
                      {sizes.map((size, index) => (
                        <span
                          style={{ fontFamily: "Raleway" }}
                          key={index}
                          className={selectedSize === size.size ? "active" : ""}
                          onClick={() => handleSizeClick(size.size)}
                        >
                          {size.size}
                        </span>
                      ))}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {/* Màu sắc */}
            <div className="col-lg-12 col-md-6">
              <div className="block-filter">
                <h6 style={{ marginBottom: "15px", fontFamily: "Raleway" }}>
                  Màu sắc
                </h6>
                <div className="box-collapse">
                  <div className="list-color">
                    {colors.map((color) => (
                      <span
                        style={{
                          paddingTop: "7px",
                          cursor: "pointer",
                          paddingBottom: "7px",
                          paddingRight: "8px",
                          paddingLeft: "8px",
                          fontFamily: "Raleway",
                        }}
                        className={
                          selectedColor === color.name_color
                            ? "active"
                            : "filter-color"
                        }
                        key={color.id}
                        onClick={() => handleColorClick(color.name_color)}
                      >
                        <span
                          style={{ fontFamily: "Raleway" }}
                          className="box-color"
                        >
                          {color.name_color}
                        </span>
                      </span>
                    ))}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AsideFilter;
