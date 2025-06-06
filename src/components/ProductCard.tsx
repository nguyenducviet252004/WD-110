import React from "react";
type Product = {
  id: number;
  name: string;
  price: number;
};

type ProductCardProps = {
  product: Product;
};
const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
  return (
    <div className="border rounded-lg shadow hover:shadow-xl transition overflow-hidden">
      <img
        src={product.image}
        alt={product.name}
        className="w-full h-52 object-cover"
      />
      <div className="p-4">
        <h3 className="text-lg font-semibold text-gray-800">{product.name}</h3>
        <p className="text-red-500 font-bold mt-2">
          {product.price.toLocaleString()}₫
        </p>
        <button className="mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Mua ngay
        </button>
      </div>
    </div>
  );
};

export default ProductCard;
