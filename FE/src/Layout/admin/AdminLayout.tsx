import { Outlet, Link } from "react-router-dom";

const AdminLayout = () => {
  return (
    <div className="flex min-h-screen">
      {/* Sidebar */}
      <aside className="w-64 bg-gray-800 text-white p-4 space-y-3">
        <h1 className="text-2xl font-bold mb-6">Admin Panel</h1>
        <nav className="space-y-2">
          <Link to="/admin" className="block hover:text-blue-400">Dashboard</Link>
          <Link to="/admin/products" className="block hover:text-blue-400">Quản lý sản phẩm</Link>
          <Link to="/admin/orders" className="block hover:text-blue-400">Quản lý đơn hàng</Link>
          <Link to="/admin/users" className="block hover:text-blue-400">Quản lý người dùng</Link>
        </nav>
      </aside>
      {/* Main content */}
      <main className="flex-1 p-6 bg-gray-100">
        {/* Header admin */}
        <header className="mb-6 border-b pb-4 flex justify-between items-center">
          <h2 className="text-xl font-semibold">Khu vực quản trị</h2>
          <Link to="/" className="text-blue-600 hover:underline">Về trang người dùng</Link>
        </header>
        <Outlet />
      </main>
    </div>
  );
};

export default AdminLayout;