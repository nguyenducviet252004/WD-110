// src/redux/userSlice.ts
import { createSlice, createAsyncThunk, PayloadAction } from '@reduxjs/toolkit';
import axios from 'axios';

interface User {
    id: number;
    fullname: string;
    email: string;
    phone: string;
    address: string;
    birth_day: string;
    avatar: string;
}

interface UserState {
    user: User | null;
    loading: boolean;
    error: string | null;
}

const initialState: UserState = {
    user: null,
    loading: false,
    error: null,
};

// Fetch user data
export const fetchUser = createAsyncThunk('user/fetchUser', async (userId: number) => {
    const response = await axios.get(`http://127.0.0.1:8000/api/user/${userId}`);
    return response.data;
});

// Update user data
export const updateUser = createAsyncThunk(
    'user/updateUser',
    async (
        { userId, userData }: { userId: number; userData: Partial<User> },
        { rejectWithValue }
    ) => {
        try {
            const token = localStorage.getItem('token');
            const response = await axios.put(
                `http://127.0.0.1:8000/api/user/${userId}`,
                userData,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                }
            );
            return response.data;
        } catch (error: any) {
            return rejectWithValue(error.response?.data || 'Failed to update user');
        }
    }
);

const UserReducer = createSlice({
    name: 'user',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            // Fetch user
            .addCase(fetchUser.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(fetchUser.fulfilled, (state, action: PayloadAction<User>) => {
                state.loading = false;
                state.user = action.payload;
            })
            .addCase(fetchUser.rejected, (state, action) => {
                state.loading = false;
                state.error = action.error.message || 'Error fetching user';
            })
            // Update user
            .addCase(updateUser.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(updateUser.fulfilled, (state, action: PayloadAction<User>) => {
                state.loading = false;
                state.user = action.payload; // Cập nhật thông tin người dùng sau khi thành công
            })
            .addCase(updateUser.rejected, (state, action) => {
                state.loading = false;
                state.error = action.payload as string;
            });
    },
});

export default UserReducer.reducer;
