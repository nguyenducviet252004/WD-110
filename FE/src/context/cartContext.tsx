import React, {
  createContext,
  useContext,
  useState,
  useEffect,
  ReactNode,
} from "react";
import axios from "axios";
// import { log } from "console";

// Định nghĩa kiểu dữ liệu của giỏ hàng
interface CartItem {
  id: number;
  product_id: number;
  cart_id: number;
  avatar: string;
  product_name: string;
  quantity: number;
  price: number;
  size_id: number;
  color_id: number;
  total: number;
  product: {
    id: number;
    name: string;
    avatar: string;
    price: number;
  };
}

interface CartContextType {
  totalQuantity: number;
  fetchCartItems: () => void;
}

const CartContext = createContext<CartContextType | undefined>(undefined);

export const CartProvider = ({ children }: { children: ReactNode }) => {
  const [totalQuantity, setTotalQuantity] = useState<number>(0);

  // Hàm lấy dữ liệu giỏ hàng từ API
  const fetchCartItems = async () => {
    try {
      const token = localStorage.getItem("token");
      const user = localStorage.getItem("user");

      let parsedUser;
      try {
        parsedUser = JSON.parse(user!);
      } catch (error) {
        console.error("Lỗi khi phân tích dữ liệu người dùng:", error);
      }

      if (parsedUser && parsedUser.user) {
        const userId = parsedUser.user.id;

        const response = await axios.get(
          `http://127.0.0.1:8000/api/carts/${userId}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        const total = response.data.cart_items.length;
        setTotalQuantity(total);
      } else {
        console.error("Không tìm thấy thông tin người dùng.");
      }
    } catch (error) {
      console.error("Lỗi khi lấy dữ liệu giỏ hàng:", error);
    }
  };

  useEffect(() => {
    fetchCartItems();
  }, []);

  return (
    <CartContext.Provider value={{ totalQuantity, fetchCartItems }}>
      {children}
    </CartContext.Provider>
  );
};

export const useCart = (): CartContextType => {
  const context = useContext(CartContext);
  if (context === undefined) {
    throw new Error("useCart phải được sử dụng trong CartProvider");
  }
  return context;
};
