import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import axios from 'axios';
import api from '../../Axios/Axios';


export interface Order {
  user_id: string | null;
  total_amount: number;
  ship_method: number;
  payment_method: number;
  voucher_id: any;
  ship_address_id: number;
  status: number;
  order_details?: OrderDetail[];
}

export interface OrderDetail {
  product_detail_id: number;
  quantity: number;
  price: number;
  name_product: string;
  size: string;
  color: string;
  total: any;
}

interface OrderState {
  orders: Order[];
  loading: boolean;
  error: string | null;
  payment_url?: string;
  payment_status?: string;  // Thêm trạng thái thanh toán
  payment_message?: string; // Thêm thông điệp thanh toán
}

const initialState: OrderState = {
  orders: [],
  loading: false,
  error: null,
  payment_url: undefined,
  payment_status: undefined,
  payment_message: undefined,
};

export const postOrder = createAsyncThunk<
  { order: Order, payment_url?: string },
  Order,
  { rejectValue: string }
>(
  'order/postOrder',
  async (orderData: Order, { rejectWithValue }) => {
    const token = localStorage.getItem('token');
    if (!token) {
      return rejectWithValue('No token found');
    }
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/orders', orderData, {
        headers: { Authorization: `Bearer ${token}` },
      });
      return { order: response.data, payment_url: response.data.payment_url?.original?.payment_url };
    } catch (error: any) {
      console.error('Error posting order:', error);
      return rejectWithValue(error.response?.data || 'Something went wrong');
    }
  }
);

export const fetchPaymentStatus = createAsyncThunk<
  { status: string, message: string },
  string,
  { rejectValue: string }
>(
  'order/fetchPaymentStatus',
  async (queryParams: string, { rejectWithValue }) => {
    try {
      const response = await axios.get(`http://127.0.0.1:8000/api/payment/result${queryParams}`);
      return { status: response.data.status, message: response.data.message };
    } catch (error: any) {
      console.error('Error fetching payment status:', error);
      return rejectWithValue(error.response?.data || 'Something went wrong');
    }
  }
);

const OrderReducer = createSlice({
  name: 'order',
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(postOrder.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(postOrder.fulfilled, (state, action) => {
        state.loading = false;
        state.orders.push(action.payload.order);
        state.payment_url = action.payload.payment_url;
      })
      .addCase(postOrder.rejected, (state, action) => {
        state.loading = false;
        state.error = action.error.message || 'Đã xảy ra lỗi!';
      })
      .addCase(fetchPaymentStatus.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchPaymentStatus.fulfilled, (state, action) => {
        state.loading = false;
        state.payment_status = action.payload.status;
        state.payment_message = action.payload.message;
      })
      .addCase(fetchPaymentStatus.rejected, (state, action) => {
        state.loading = false;
        state.error = action.error.message || 'Đã xảy ra lỗi!';
      });
  },
});

export default OrderReducer.reducer;
