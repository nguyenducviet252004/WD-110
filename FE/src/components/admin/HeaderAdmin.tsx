import React from 'react'
import { Link } from 'react-router-dom'

const HeaderAdmin = () => {
  return (
    <header>
      <nav>
        <ul>
          <li><Link to="/">Trang chủ</Link></li>
          <li><Link to="/cart">Giỏ hàng</Link></li>
          <li><Link to="/login">Đăng nhập</Link></li>
          <li><Link to="/admin">Quản trị</Link></li>
        </ul>
      </nav>
    </header>
  )
}

export default HeaderAdmin