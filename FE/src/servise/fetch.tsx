import axios from "axios";
import type { IProducts } from "../types/interface";

const getCartData = async (cartId: string) => {
  try {
    const response = await axios.get(
      `http://localhost:3001/cart/${cartId}`
    );
    return response.data;
  } catch (error) {
    console.error("Error fetching cart data:", error);
    throw error;
  }
};

export const cartService = {
  getCartData,
};

const getAllCartItems = async () => {
  try {
    const response = await axios.get("http://localhost:3001/cart");
    return response.data;
  } catch (error) {
    console.error("Error fetching all cart items:", error);
    throw error;
  }
};

export const allCartItemsService = {
  getAllCartItems,
};
const getAllProducts = async () => {
  try {
    const response = await axios.get("http://localhost:3001/products");
    return response.data;
  } catch (error) {
    console.error("Error fetching all products:", error);
    throw error;
  }
}
export const allProductsService = {
  getAllProducts,
};
const getProdcutById = async (id: number) => { 
  try {
    const response = await axios.get(`http://localhost:3001/products/${id}`);
    return response.data;
  } catch (error) {
    console.error("Error fetching product by ID:", error);
    throw error;
  }
} 
export const productByIdService = {
  getProdcutById,
};
const updateProducts = async (id: number, data: IProducts) => {
  try {
    const response = await axios.put(`http://localhost:3001/products/${id}`, data);
    return response.data;
  } catch (error) {
    console.error("Error updating product:", error);
    throw error;
  }
}
export const updateProductService = {
  updateProducts,
};
const createProduct = async (data: IProducts) => {
  try {
    const response = await axios.post("http://localhost:3001/products", data);
    return response.data;
  } catch (error) {
    console.error("Error creating product:", error);
    throw error;
  }
} 
export const createProductService = {
  createProduct,
};
const deleteProduct = async (id: number) => {
  try {
    const response = await axios.delete(`http://localhost:3001/products/${id}`);
    return response.data;
  } catch (error) {
    console.error("Error deleting product:", error);
    throw error;
  }
}
export const deleteProductService = {
  deleteProduct,
};

