// store/categorySlice.ts
import { createSlice, createAsyncThunk, PayloadAction } from '@reduxjs/toolkit';
import axios from 'axios';

export interface Category {
    id: number;
    name: string;
}

interface Colors {
    id: string;
    name_color: string;
    hex_color: string;
}

interface Sizes {
    id: string;
    size: string;
}

export interface Product {
    id: string;
    name: string;
    import_price: number;
    price: number;
    description: string;
    avatar: string
    colors: Colors[];
    sizes: Sizes[];
}

interface CategoryState {
    categories: Category[];
    products: Product[];
    loading: boolean;
    activeTab: string;
}

const initialState: CategoryState = {
    categories: [],
    products: [],
    loading: false,
    activeTab: 'all',
};

export const fetchCategories = createAsyncThunk('categories/fetch', async () => {
    const response = await axios.get<Category[]>('http://127.0.0.1:8000/api/categories');
    return response.data;
});

export const fetchProductsByCategory = createAsyncThunk(
    'products/fetchByCategory',
    async (categoryId: number) => {
        const response = await axios.get<Product[]>(`http://127.0.0.1:8000/api/products/category/${categoryId}`);
        return response.data;
    }
);

const categorySlice = createSlice({
    name: 'category',
    initialState,
    reducers: {
        setActiveTab(state, action: PayloadAction<string>) {
            state.activeTab = action.payload;
        },
    },
    extraReducers: (builder) => {
        builder
            .addCase(fetchCategories.fulfilled, (state, action) => {
                state.categories = action.payload;
            })
            .addCase(fetchProductsByCategory.fulfilled, (state, action) => {
                state.products = action.payload;
            });
    },
});

export const { setActiveTab } = categorySlice.actions;
export default categorySlice.reducer;
