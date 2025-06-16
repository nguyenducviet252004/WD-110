import { useEffect, useState } from "react";
import axios from "axios";

interface Product {
  id: number;
  name: string;
  price: number;
}

export default function AdminProducts() {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Lấy danh sách sản phẩm
  useEffect(() => {
    axios.get("http://127.0.0.1:8000/api/products")
      .then(res => {
        setProducts(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Không thể tải danh sách sản phẩm");
        setLoading(false);
      });
  }, []);

  // Xóa sản phẩm
  const handleDelete = (id: number) => {
    if (!window.confirm("Bạn chắc chắn muốn xóa sản phẩm này?")) return;
    axios.delete(`http://127.0.0.1:8000/api/products/${id}`)
      .then(() => setProducts(products.filter(p => p.id !== id)))
      .catch(() => alert("Xóa sản phẩm thất bại!"));
  };

  if (loading) return <div>Đang tải...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div>
      <h2>Quản lý sản phẩm</h2>
      <table border={1} cellPadding={8}>
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          {products.map(p => (
            <tr key={p.id}>
              <td>{p.id}</td>
              <td>{p.name}</td>
              <td>{p.price}₫</td>
              <td>
                {/* Thêm nút sửa nếu cần */}
                <button onClick={() => handleDelete(p.id)}>Xóa</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {/* Thêm form thêm/sửa sản phẩm nếu muốn */}
    </div>
  );
}