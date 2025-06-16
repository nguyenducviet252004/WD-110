import { useEffect, useState } from "react";
import axios from "axios";

interface DashboardStats {
  totalProducts: number;
  totalOrders: number;
  totalUsers: number;
  totalRevenue: number;
}

export default function AdminDashboard() {
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    // Gọi API lấy thống kê tổng quan
    axios.get("http://127.0.0.1:8000/api/admin/dashboard")
      .then(res => {
        setStats(res.data);
        setLoading(false);
      })
      .catch(() => {
        setError("Không thể tải dữ liệu tổng quan");
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Đang tải dữ liệu...</div>;
  if (error) return <div>{error}</div>;

  return (
    <div>
      <h2>Thống kê tổng quan</h2>
      <ul>
        <li>Tổng sản phẩm: {stats?.totalProducts}</li>
        <li>Tổng đơn hàng: {stats?.totalOrders}</li>
        <li>Tổng người dùng: {stats?.totalUsers}</li>
        <li>Tổng doanh thu: {stats?.totalRevenue?.toLocaleString()}₫</li>
      </ul>
      {/* Có thể bổ sung thêm biểu đồ, danh sách đơn hàng mới, sản phẩm bán chạy... */}
    </div>
  );
}