import { useEffect, useState } from "react";
import axios from "axios";

interface Order {
  id: number;
  user_name: string;
  total: number;
  status: string;
  created_at: string;
  // Thêm các trường khác nếu cần
}

export default function AdminOrders() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Lấy danh sách đơn hàng khi trang được mở
  useEffect(() => {
    axios.get("http://127.0.0.1:8000/api/orders")
      .then(res => {
        setOrders(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Không thể tải danh sách đơn hàng");
        setLoading(false);
      });
  }, []);

  // Hàm cập nhật trạng thái đơn hàng
  const handleUpdateStatus = (id: number, newStatus: string) => {
    axios.put(`http://127.0.0.1:8000/api/orders/${id}`, { status: newStatus })
      .then(() => {
        setOrders(orders.map(order =>
          order.id === id ? { ...order, status: newStatus } : order
        ));
      })
      .catch(() => {
        alert("Cập nhật trạng thái thất bại!");
      });
  };

  if (loading) return <div>Đang tải...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div>
      <h2>Quản lý đơn hàng</h2>
      <table border={1} cellPadding={8}>
        <thead>
          <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          {orders.map(order => (
            <tr key={order.id}>
              <td>{order.id}</td>
              <td>{order.user_name}</td>
              <td>{order.total}₫</td>
              <td>{order.status}</td>
              <td>{order.created_at}</td>
              <td>
                <button onClick={() => handleUpdateStatus(order.id, "Đã xác nhận")}>
                  Xác nhận
                </button>
                <button onClick={() => handleUpdateStatus(order.id, "Đã giao")}>
                  Đã giao
                </button>
                {/* Thêm các nút khác nếu cần */}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}