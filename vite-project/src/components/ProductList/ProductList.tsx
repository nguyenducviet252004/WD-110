import React from "react";
import ProductCard from "../ProductCard";
import "./ProductList.css";

const products = [
  {
    id: 1,
    name: "Áo thể thao",
    price: 166,
    image: "/images/aothethao.jpg",
  },
  {
    id: 2,
    name: "Áo đội tuyển",
    price: 331,
    image: "/images/aodoituyen.jpg",
  },
  {
    id: 3,
    name: "Áo bóng đá",
    price: 250,
    image: "/images/aobongda.jpg",
  },
  {
    id: 4,
    name: "Áo training",
    price: 300,
    image: "/images/aotraining.jpg",
  },
];

const ProductList = () => {
  return (
    <div className="product-list">
      {products.map((product) => (
        <div className="product-item" key={product.id}>
          <img src={product.image} alt={product.name} />
          <h3>{product.name}</h3>
          <p>{product.price} đồng</p>
          <button>♡</button>
        </div>
      ))}
    </div>
  );
};

export default ProductList;
