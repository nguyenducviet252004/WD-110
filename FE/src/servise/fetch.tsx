// import axios from "axios";
// import type { IProducts } from "../types/interface";

// // const getCartData = async (cartId: string) => {
// //   try {
// //     const response = await axios.get(
// //       `http://localhost:3001/cart/${cartId}`
// //     );
// //     return response.data;
// //   } catch (error) {
// //     console.error("Error fetching cart data:", error);
// //     throw error;
// //   }
// // };

// // export const cartService = {
// //   getCartData,
// // };

// // const getAllCartItems = async () => {
// //   try {
// //     const response = await axios.get("http://localhost:3001/cart");
// //     return response.data;
// //   } catch (error) {
// //     console.error("Error fetching all cart items:", error);
// //     throw error;
// //   }
// // };

// // export const allCartItemsService = {
// //   getAllCartItems,
// // };


// // Đổi baseURL cho phù hợp với Laravel backend
// const BASE_URL = "http://localhost:8000/api/products";

// const getAllProducts = async () => {
//   try {
//     const response = await axios.get(BASE_URL);
//     console.log('API products response:', response.data);
//     // Nếu response.data là object chứa mảng, ví dụ { data: [...] }, hãy trả về response.data.data
//     if (Array.isArray(response.data)) {
//       return response.data;
//     } else if (response.data && Array.isArray(response.data.data)) {
//       return response.data.data;
//     } else {
//       // fallback: trả về mảng rỗng nếu không đúng định dạng
//       return [];
//     }
//   } catch (error) {
//     console.error("Error fetching all products:", error);
//     throw error;
//   }
// };

// export const allProductsService = {
//   getAllProducts,
// };

// const getProductById = async (id: number) => {
//   try {
//     const response = await axios.get(`${BASE_URL}/${id}`);
//     return response.data;
//   } catch (error) {
//     console.error("Error fetching product by ID:", error);
//     throw error;
//   }
// };

// export const productByIdService = {
//   getProductById,
// };

// const updateProduct = async (id: number, data: IProducts) => {
//   try {
//     const response = await axios.put(`${BASE_URL}/${id}`, data);
//     return response.data;
//   } catch (error) {
//     console.error("Error updating product:", error);
//     throw error;
//   }
// };

// export const updateProductService = {
//   updateProduct,
// };

// const createProduct = async (data: IProducts) => {
//   try {
//     const response = await axios.post(BASE_URL, data);
//     return response.data;
//   } catch (error) {
//     console.error("Error creating product:", error);
//     throw error;
//   }
// };

// export const createProductService = {
//   createProduct,
// };

// const deleteProduct = async (id: number) => {
//   try {
//     const response = await axios.delete(`${BASE_URL}/${id}`);
//     return response.data;
//   } catch (error) {
//     console.error("Error deleting product:", error);
//     throw error;
//   }
// };

// export const deleteProductService = {
//   deleteProduct,
// };

