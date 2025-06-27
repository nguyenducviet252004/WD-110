import { createSlice, createAsyncThunk, PayloadAction } from '@reduxjs/toolkit';
import axios from 'axios';

// Định nghĩa kiểu cho phương thức thanh toán
interface PaymentMethod {
    id: number; // Hoặc kiểu khác nếu id không phải là number
    name: string;
}

// Định nghĩa kiểu cho state
interface PaymentMethodState {
    methods: PaymentMethod[];
    loading: boolean;
    error: string | null;
}

// Khởi tạo state ban đầu
const initialState: PaymentMethodState = {
    methods: [],
    loading: false,
    error: null,
};

export const fetchPaymentMethods = createAsyncThunk<PaymentMethod[], void>(
    'paymentMethod/fetchPaymentMethods',
    async () => {
        const response = await axios.get<PaymentMethod[]>('http://127.0.0.1:8000/api/payment_methods');
        return response.data;
    }
);

// Tạo slice cho payment methods
const paymentMethodSlice = createSlice({
    name: 'paymentMethod',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchPaymentMethods.pending, (state) => {
                state.loading = true;
            })
            .addCase(fetchPaymentMethods.fulfilled, (state, action: PayloadAction<PaymentMethod[]>) => {
                state.loading = false;
                state.methods = action.payload;
            })
            .addCase(fetchPaymentMethods.rejected, (state, action) => {
                state.loading = false;
                state.error = action.error.message || 'Something went wrong'; // Xử lý lỗi
            });
    },
});

// Selector để truy cập phương thức thanh toán
export const selectPaymentMethods = (state: { paymentMethod: PaymentMethodState }) => state.paymentMethod.methods;

// Xuất reducer
export default paymentMethodSlice.reducer;
