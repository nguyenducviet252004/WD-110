import { useEffect, useState } from "react";
import axios from "axios";

interface Category {
  id: number;
  name: string;
}

export default function AdminCategories() {
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    axios.get("http://127.0.0.1:8000/api/categories")
      .then(res => {
        setCategories(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Không thể tải danh sách danh mục");
        setLoading(false);
      });
  }, []);

  const handleDelete = (id: number) => {
    if (!window.confirm("Bạn chắc chắn muốn xóa danh mục này?")) return;
    axios.delete(`http://127.0.0.1:8000/api/categories/${id}`)
      .then(() => setCategories(categories.filter(c => c.id !== id)))
      .catch(() => alert("Xóa danh mục thất bại!"));
  };

  if (loading) return <div>Đang tải...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div>
      <h2>Quản lý Danh mục</h2>
      <table border={1} cellPadding={8}>
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          {categories.map(c => (
            <tr key={c.id}>
              <td>{c.id}</td>
              <td>{c.name}</td>
              <td>
                <button onClick={() => handleDelete(c.id)}>Xóa</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {/* Thêm form thêm/sửa danh mục nếu muốn */}
    </div>
  );
}