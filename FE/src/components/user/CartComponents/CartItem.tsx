import { useEffect, useState } from "react";
import type { ICartItem } from "../../../types/cart-item";
import { allCartItemsService } from "../../../servise/fetch";
import "./cart.css";

const CartItem = () => {
  const [items, setItems] = useState<ICartItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const data = await allCartItemsService.getAllCartItems();
        setItems(data);
        setLoading(false);
      } catch (err) {
        setError("Không thể tải dữ liệu giỏ hàng. Vui lòng thử lại sau.");
        setLoading(false);
      }
    };
    fetchData();
  }, []);

  const [address, setAddress] = useState({
    city: "",
    district: "",
    ward: "",
    detail: "",
  });

  const handleQuantityChange = (id: number, delta: number) => {
    setItems((prevItems) =>
      prevItems.map((item) =>
        item.id === id
          ? { ...item, quantity: Math.max(1, item.quantity + delta) }
          : item
      )
    );
  };

  const total = items.reduce(
    (sum, item) => sum + item.price * item.quantity,
    0
  );

  const handleSaveAddress = () => {
    alert("Địa chỉ đã được lưu!");
  };

  if (error) return <div className="error">{error}</div>;
  if (loading) return <div className="loading">Đang tải...</div>;

  return (
    <div className="cart-container">
      {/* Bên trái: Giỏ hàng */}
      <div className="cart-left">
        <h1 className="cart-title">Giỏ hàng</h1>
        {items.map((item) => (
          <div key={item.id} className="cart-item">
            <img src={item.image} alt={item.name} className="item-image" />
            <div className="item-info">
              <p className="item-name">{item.name}</p>
              <p className="item-price">{item.price.toLocaleString()} VND</p>
            </div>
            <div className="item-quantity">
              <button onClick={() => handleQuantityChange(item.id, -1)}>
                −
              </button>
              <input type="text" readOnly value={item.quantity} />
              <button onClick={() => handleQuantityChange(item.id, 1)}>
                +
              </button>
            </div>
            <div className="item-total">
              {(item.price * item.quantity).toLocaleString()} VND
            </div>
          </div>
        ))}

        {/* Mã giảm giá + cập nhật */}
        <div className="cart-discount">
          <input type="text" placeholder="Mã giảm giá" />
          <button>MÃ GIẢM GIÁ</button>
          <button>CẬP NHẬT GIỎ HÀNG</button>
        </div>
      </div>

      {/* Bên phải: Thông tin đơn hàng */}
      <div className="cart-right">
        <h2>Thông tin đơn hàng</h2>
        <div className="order-summary">
          <p>
            Tổng tiền: <span>{total.toLocaleString()} VND</span>
          </p>
          <p>Vận chuyển: Thanh toán khi nhận hàng</p>
        </div>

        <div className="address-form">
          <input
            type="text"
            placeholder="Thành phố"
            value={address.city}
            onChange={(e) => setAddress({ ...address, city: e.target.value })}
          />
          <input
            type="text"
            placeholder="Quận/Huyện"
            value={address.district}
            onChange={(e) =>
              setAddress({ ...address, district: e.target.value })
            }
          />
          <input
            type="text"
            placeholder="Phường/Xã"
            value={address.ward}
            onChange={(e) => setAddress({ ...address, ward: e.target.value })}
          />
          <input
            type="text"
            placeholder="Địa chỉ chi tiết"
            value={address.detail}
            onChange={(e) => setAddress({ ...address, detail: e.target.value })}
          />

          {/* <button className="save-address" onClick={handleSaveAddress}>
            Lưu địa chỉ
          </button> */}
        </div>

        <div className="order-total">
          Tổng: <span>{total.toLocaleString()} VND</span>
        </div>

        <button className="checkout-btn">Thanh toán</button>
      </div>
    </div>
  );
};

export default CartItem;
