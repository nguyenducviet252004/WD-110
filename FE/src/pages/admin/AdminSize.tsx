import { useEffect, useState } from "react";
import axios from "axios";

interface Size {
  id: number;
  name: string;
}

export default function AdminSizes() {
  const [sizes, setSizes] = useState<Size[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    axios.get("http://127.0.0.1:8000/api/sizes")
      .then(res => {
        setSizes(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Không thể tải danh sách size");
        setLoading(false);
      });
  }, []);

  const handleDelete = (id: number) => {
    if (!window.confirm("Bạn chắc chắn muốn xóa size này?")) return;
    axios.delete(`http://127.0.0.1:8000/api/sizes/${id}`)
      .then(() => setSizes(sizes.filter(s => s.id !== id)))
      .catch(() => alert("Xóa size thất bại!"));
  };

  if (loading) return <div>Đang tải...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div>
      <h2>Quản lý Size</h2>
      <table border={1} cellPadding={8}>
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên size</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          {sizes.map(s => (
            <tr key={s.id}>
              <td>{s.id}</td>
              <td>{s.name}</td>
              <td>
                <button onClick={() => handleDelete(s.id)}>Xóa</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {/* Thêm form thêm/sửa size nếu muốn */}
    </div>
  );
}