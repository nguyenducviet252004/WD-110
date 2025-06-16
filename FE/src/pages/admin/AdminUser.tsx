import { useEffect, useState } from "react";
import axios from "axios";

interface User {
  id: number;
  name: string;
  email: string;
  role: string; // 'admin' hoặc 'user'
  is_active: boolean;
}

export default function AdminUsers() {
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    axios.get("http://127.0.0.1:8000/api/users")
      .then(res => {
        setUsers(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Không thể tải danh sách người dùng");
        setLoading(false);
      });
  }, []);

  // Khóa hoặc mở tài khoản
  const handleToggleActive = (id: number, isActive: boolean) => {
    axios.put(`http://127.0.0.1:8000/api/users/${id}`, { is_active: !isActive })
      .then(() => {
        setUsers(users.map(u =>
          u.id === id ? { ...u, is_active: !isActive } : u
        ));
      })
      .catch(() => alert("Cập nhật trạng thái tài khoản thất bại!"));
  };

  if (loading) return <div>Đang tải...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div>
      <h2>Quản lý người dùng</h2>
      <table border={1} cellPadding={8}>
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          {users.map(u => (
            <tr key={u.id}>
              <td>{u.id}</td>
              <td>{u.name}</td>
              <td>{u.email}</td>
              <td>{u.role}</td>
              <td>{u.is_active ? "Hoạt động" : "Đã khóa"}</td>
              <td>
                <button onClick={() => handleToggleActive(u.id, u.is_active)}>
                  {u.is_active ? "Khóa" : "Mở"}
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}