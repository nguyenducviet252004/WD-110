import { createAsyncThunk, createSlice } from "@reduxjs/toolkit";
import axios from "axios";

// types.ts
export interface OrderDetail {
    productId: number;
    productName: string;
    quantity: number;
    price: number;
    total: number;
  }
  
  export interface ShipAddress {
    shipAddress: string;
    phoneNumber: string;
  }
  
  export interface Order {
    orderId: number;
    totalAmount: number;
    quantity: number;
    shipAddress: ShipAddress;
    orderDetails: OrderDetail[];
  }
  
  export interface OrderHistoryState {
    orders: Order[];
    loading: boolean;
    error: string | null;
  }

  const initialState: OrderHistoryState = {
    orders: [],
    loading: false,
    error: null,
  };
  
  export const fetchOrderHistory = createAsyncThunk(
    'orderHistory/fetchOrderHistory',
    async (userId: number, { rejectWithValue }) => {
      try {
        const response = await axios.get(`http://localhost:8000/api/order-history/${userId}`);
        console.log(response);
        
        return response.data.data; 
      } catch (error: any) {
        return rejectWithValue(error.response?.data?.message || 'Error fetching order history');
      }
    }
  );
  
  // Slice
  const OrderHistoryReducer = createSlice({
    name: 'orderHistory',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
      builder
        .addCase(fetchOrderHistory.pending, (state) => {
          state.loading = true;
          state.error = null;
        })
        .addCase(fetchOrderHistory.fulfilled, (state, action) => {
          state.loading = false;
          state.orders = action.payload;
        })
        .addCase(fetchOrderHistory.rejected, (state, action) => {
          state.loading = false;
          state.error = action.payload as string;
        });
    },
  });
  
  export default OrderHistoryReducer.reducer;