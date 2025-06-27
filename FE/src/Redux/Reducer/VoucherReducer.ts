import { createAsyncThunk, createSlice, PayloadAction } from "@reduxjs/toolkit";

export interface Voucher {
  id: number;
  code: string;
  type: number;
  discount_value: number;
  description: string;
  discount_min: number;
  max_discount: number;
  min_order_count: number;
  max_order_count: number;
  quantity: number;
  used_times: number;
  start_day: string;
  end_day: string;
  status: number;
  is_active: boolean;
}

interface VoucherState {
  vouchers: Voucher[];
  loading: boolean;
  error: string | null;
}

const initialState: VoucherState = {
  vouchers: [],
  loading: false,
  error: null,
};

export const fetchVouchers = createAsyncThunk<
  Voucher[], // Success payload type
  void, // Argument type (we don't need arguments)
  { rejectValue: string } // Custom error type
>(
  'vouchers/fetchVouchers',
  async (_, { rejectWithValue }) => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        return rejectWithValue('Token is missing');
      }

      const response = await fetch('http://127.0.0.1:8000/api/vouchers', {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });

      if (!response.ok) {
        return rejectWithValue('Failed to fetch vouchers');
      }

      const data = await response.json();
      if (data?.status !== true) {
        return rejectWithValue('Failed to fetch vouchers');
      }

      return data.vouchers; // Assuming the vouchers are in the "vouchers" field of the response
    } catch (error: any) {
      return rejectWithValue(error.message || 'An error occurred');
    }
  }
);

const VoucherReducer = createSlice({
  name: 'voucher',
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(fetchVouchers.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(fetchVouchers.fulfilled, (state, action: PayloadAction<Voucher[]>) => {
        state.loading = false;
        state.vouchers = action.payload;
      })
      .addCase(fetchVouchers.rejected, (state, action) => {
        state.loading = false;
        state.error = action.payload as string;
      });
  },
});

export default VoucherReducer.reducer;
