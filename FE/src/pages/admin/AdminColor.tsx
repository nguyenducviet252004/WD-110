import { useEffect, useState } from "react";
import axios from "axios";

interface Color {
  id: number;
  name: string;
  code: string; // mã màu, ví dụ: #FF0000
}

export default function AdminColors() {
  const [colors, setColors] = useState<Color[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    axios.get("http://127.0.0.1:8000/api/colors")
      .then(res => {
        setColors(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Không thể tải danh sách màu");
        setLoading(false);
      });
  }, []);

  const handleDelete = (id: number) => {
    if (!window.confirm("Bạn chắc chắn muốn xóa màu này?")) return;
    axios.delete(`http://127.0.0.1:8000/api/colors/${id}`)
      .then(() => setColors(colors.filter(c => c.id !== id)))
      .catch(() => alert("Xóa màu thất bại!"));
  };

  if (loading) return <div>Đang tải...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div>
      <h2>Quản lý Màu sắc</h2>
      <table border={1} cellPadding={8}>
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên màu</th>
            <th>Mã màu</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          {colors.map(c => (
            <tr key={c.id}>
              <td>{c.id}</td>
              <td>{c.name}</td>
              <td>
                <span style={{ background: c.code, padding: "0 10px" }}>{c.code}</span>
              </td>
              <td>
                <button onClick={() => handleDelete(c.id)}>Xóa</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
      {/* Thêm form thêm/sửa màu nếu muốn */}
    </div>
  );
}