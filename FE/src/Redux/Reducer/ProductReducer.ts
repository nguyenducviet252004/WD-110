// src/redux/productSlice.ts
import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';

// src/types/interfaces.ts
export interface Color {
    id: string;             
    name_color: string;       
    hex_color?: string;       
}

export interface Size {
    id: string;            
    size: string;             
}

export interface Category {
    id: string;               
    name: string;             
}

export interface Gallery {
    id: number;              
    product_id: number;       
    image_path: string;       
}

export interface Product {
    id: string;               
    name: string;             
    import_price: number;     
    price: number;            
    description: string;      
    avatar: string;           
    colors: Color[];          
    sizes: Size[];            
    galleries: Gallery[];     
    category: Category;       
    quantity: number;        
    sell_quantity: number;    
    view: number;            
}
export interface ApiResponse {
    products: Product[];      
    all_colors: Color[];      
    all_sizes: Size[];      
}

interface ProductState {
    products: Product[];
    loading: boolean;
    error: string | null;
}

const initialState: ProductState = {
    products: [],
    loading: false,
    error: null,
};

export const fetchProducts = createAsyncThunk('products/fetchProducts', async (): Promise<Product[]> => {
    const response = await fetch('http://127.0.0.1:8000/api/products');
    if (!response.ok) {
        throw new Error('Failed to fetch products');
    }
    const data: ApiResponse = await response.json();
    return data.products;
});

const ProductReducer = createSlice({
    name: 'products',
    initialState,
    reducers: {},
    extraReducers: (builder) => {
        builder
            .addCase(fetchProducts.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            .addCase(fetchProducts.fulfilled, (state, action) => {
                state.loading = false;
                state.products = action.payload;
            })
            .addCase(fetchProducts.rejected, (state, action) => {
                state.loading = false;
                state.error = action.error.message || 'Something went wrong';
            });
    },
});
export default ProductReducer.reducer;
