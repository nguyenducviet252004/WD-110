import ProductCard from "./ProductCard";

const sampleProducts = [
  {
    id: 1,
    name: "Áo tuyển Việt Nam",
    price: 280000,
    image: "/images/vietnam.jpg",
  },
  {
    id: 2,
    name: "Áo Nhật Bản",
    price: 320000,
    image: "/images/japan.jpg",
  },
  {
    id: 3,
    name: "Áo Brazil",
    price: 350000,
    image: "/images/brazil.jpg",
  },
];

const ProductList = () => {
  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      {sampleProducts.map((product) => (
        <ProductCard key={product.id} product={product} />
      ))}
    </div>
  );
};

export default ProductList;
