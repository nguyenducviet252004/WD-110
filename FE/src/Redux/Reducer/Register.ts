// src/redux/slices/authSlice.ts
import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';

// Định nghĩa kiểu dữ liệu cho state
interface AuthState {
  loading: boolean;
  user: any | null;
  error: string | null;
}

// State khởi tạo
const initialState: AuthState = {
  loading: false,
  user: null,
  error: null,
};

export const registerUser = createAsyncThunk(
  'auth/register', // Tên action
  async (userData: { email: string; username: string; password: string; confirmPassword: string }, { rejectWithValue }) => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData),
      });
      
      if (!response.ok) {
        throw new Error('Đăng ký thất bại');
      }

      const data = await response.json();
      return data; // Dữ liệu trả về từ API
    } catch (error: any) {
      return rejectWithValue(error.message || 'Có lỗi xảy ra');
    }
  }
);

// Tạo slice cho đăng ký
const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {},
  extraReducers: (builder) => {
    builder
      .addCase(registerUser.pending, (state) => {
        state.loading = true;
        state.error = null;
      })
      .addCase(registerUser.fulfilled, (state, action) => {
        state.loading = false;
        state.user = action.payload;
        state.error = null;
      })
      .addCase(registerUser.rejected, (state, action) => {
        state.loading = false;
        state.user = null;
        state.error = action.payload as string;
      });
  },
});

export default authSlice.reducer;
