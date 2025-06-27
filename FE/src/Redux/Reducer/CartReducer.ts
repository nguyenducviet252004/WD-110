import { createSlice, createAsyncThunk, PayloadAction } from '@reduxjs/toolkit';
import axios from 'axios';
import { get } from 'http';

// Định nghĩa kiểu dữ liệu cho CartItem
export interface CartItem {
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

interface Cart {
  id: number;
  user_id: number;
  avatar: string;
  created_at: string;
  updated_at: string;
  items: CartItem[];
}

interface CartState {
  cart: Cart | null;
  totalQuantity: number,  // Thêm trường này để lưu tổng số lượng sản phẩm trong giỏ hàng
  items: CartItem[];
  status: 'idle' | 'loading' | 'succeeded' | 'failed';
  error: string | null;
}

const initialState: CartState = {
  totalQuantity: 0,  // Thêm trường này để lưu tổng số lượng sản phẩm trong giỏ hàng
  cart: null,
  items: [],
  status: 'idle',
  error: null,
};

// API actions

// Lấy giỏ hàng
export const fetchCart = createAsyncThunk<CartItem[], number, { rejectValue: string }>(
  'cart/fetchCart',
  async (_, { rejectWithValue }) => {
    try {
      const token = localStorage.getItem('token');
      const user = localStorage.getItem('user');
      
      if (!user) {
        return rejectWithValue('Không tìm thấy thông tin người dùng');
      }

      let parsedUser;
      try {
        parsedUser = JSON.parse(user);
      } catch (error) {
        return rejectWithValue('Dữ liệu người dùng không hợp lệ');
      }

      const userId = parsedUser.user.id;

      if (!token) {
        return rejectWithValue('Token không hợp lệ hoặc không có');
      }

      const response = await fetch(`http://127.0.0.1:8000/api/carts/${userId}`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error('Lỗi khi lấy giỏ hàng');
      }

      const data = await response.json();

      // Giả sử API trả về mảng các CartItem
      return data.cart_items;  // Trả về mảng CartItem
    } catch (error) {
      return rejectWithValue('Lỗi khi lấy giỏ hàng');
    }
  }
);


export const applyVoucher = createAsyncThunk(
  'cart/applyVoucher',
  async (voucherCode: string, { rejectWithValue }) => {
    try {
      const token = localStorage.getItem('token');
      const response = await axios.post(
        'http://127.0.0.1:8000/api/vouchers',
        { code: voucherCode },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      return response.data;
    } catch (error: any) {
      return rejectWithValue(error.response.data);
    }
  }
);

// Thêm sản phẩm vào giỏ hàng (cập nhật size_id và color_id)
export const addToCart = createAsyncThunk<CartItem, { productId: number; quantity: number; sizeId: number; colorId: number }, { rejectValue: { message: string } }>(
  'cart/addToCart',
  async ({ productId, quantity, sizeId, colorId }, { rejectWithValue }) => {
    try {
      const token = localStorage.getItem('token');
      const response = await axios.post(
        'http://127.0.0.1:8000/api/carts',
        { product_id: productId, quantity, size_id: sizeId, color_id: colorId },
        { headers: { Authorization: `Bearer ${token}` } }
       
      );
      return response.data as CartItem;
    } catch (error: any) {
      return rejectWithValue(error.response.data);
    }
  }
);

export const updateCartItem = createAsyncThunk<CartItem, { cartId: number; productId: number; quantity: number; sizeId: number; colorId: number }, { rejectValue: string }>(
  'cart/updateCartItem',
  async ({ cartId, productId, quantity, sizeId, colorId }, { rejectWithValue }) => {
    try {
      const token = localStorage.getItem('token');
      const response = await axios.put(
        `http://127.0.0.1:8000/api/cart/${cartId}/update/${productId}`,
        { quantity, size_id: sizeId, color_id: colorId },
        { headers: { Authorization: `Bearer ${token}` } }
      );

      return response.data.cart_item;
    } catch (error: any) {
      return rejectWithValue(error.response.data.message || 'Error updating cart item');
    }
  }
);

// Slice để quản lý giỏ hàng
const CartReducer = createSlice({
  name: 'cart',
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchCart.pending, (state) => {
        state.status = 'loading';
        state.error = null;
      })
      .addCase(fetchCart.fulfilled, (state, action: PayloadAction<CartItem[]>) => {
        state.status = 'succeeded';
        state.items = action.payload;
        // Tính lại tổng số lượng sản phẩm
        state.totalQuantity = action.payload.reduce((total, item) => total + item.quantity, 0);
      })
      .addCase(fetchCart.rejected, (state, action) => {
        state.status = 'failed';
        state.error = action.payload as string;
      })
      .addCase(addToCart.pending, (state) => {
        state.status = 'loading';
      })
      .addCase(addToCart.fulfilled, (state, action: PayloadAction<CartItem>) => {
        state.status = 'succeeded';
        const existingItem = state.items.find(
          (item) =>
            item.product_id === action.payload.product_id &&
            item.size_id === action.payload.size_id &&
            item.color_id === action.payload.color_id
        );
        if (existingItem) {
          existingItem.quantity = action.payload.quantity;
          existingItem.total = action.payload.total;
        } else {
          state.items.push(action.payload);
        }
        // Tính lại tổng số lượng sau khi thêm sản phẩm vào giỏ
        state.totalQuantity = state.items.reduce((total, item) => total + item.quantity, 0);
      })
      .addCase(addToCart.rejected, (state, action) => {
        state.status = 'failed';
        state.error = action.payload?.message || 'Đã xảy ra lỗi';
      })
      .addCase(updateCartItem.pending, (state) => {
        state.status = 'loading';
      })
      .addCase(updateCartItem.fulfilled, (state, action: PayloadAction<CartItem>) => {
        state.status = 'succeeded';
        const itemIndex = state.items.findIndex(
          (item) =>
            item.product_id === action.payload.product_id &&
            item.size_id === action.payload.size_id &&
            item.color_id === action.payload.color_id
        );
        if (itemIndex !== -1) {
          state.items[itemIndex] = {
            ...state.items[itemIndex],
            quantity: action.payload.quantity,
            total: action.payload.total,
          };
        }
        // Tính lại tổng số lượng sau khi cập nhật sản phẩm trong giỏ
        state.totalQuantity = state.items.reduce((total, item) => total + item.quantity, 0);
        localStorage.setItem('cartItems', JSON.stringify(state.items)); // Cập nhật giỏ hàng vào localStorage
      })
      .addCase(updateCartItem.rejected, (state, action) => {
        state.status = 'failed';
        state.error = action.payload as string;
      });
  },
});

export default CartReducer.reducer;
