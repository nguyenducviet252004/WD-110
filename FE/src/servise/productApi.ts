import axios from "axios";
import type { IProducts } from "../types/interface";
import type { IProductVariant } from "../types/variant";

const BASE_URL = "http://localhost:8000/api";

// Products
export const getAllProducts = async () => {
  const response = await axios.get(`${BASE_URL}/products`);
  return response.data.data;
};

export const getProductById = async (id: number) => {
  const response = await axios.get(`${BASE_URL}/products/${id}`);
  return response.data.data;
};

export const createProduct = async (data: IProducts) => {
  const response = await axios.post(`${BASE_URL}/products`, data, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return response.data.data;
};

export const updateProduct = async (id: number, data: IProducts) => {
  const response = await axios.put(`${BASE_URL}/products/${id}`, data, {
    headers: { "Content-Type": "multipart/form-data" },
  });
  return response.data.data;
};

export const deleteProduct = async (id: number) => {
  const response = await axios.delete(`${BASE_URL}/products/${id}`);
  return response.data;
};

// Product Variants
export const getProductVariants = async (
  productId: number
): Promise<IProductVariant[]> => {
  const response = await axios.get(
    `${BASE_URL}/products/${productId}/variants`
  );
  return response.data.data;
};

export const createProductVariant = async (
  productId: number,
  data: FormData
): Promise<IProductVariant> => {
  const response = await axios.post(
    `${BASE_URL}/products/${productId}/variants`,
    data,
    {
      headers: { "Content-Type": "multipart/form-data" },
    }
  );
  return response.data.data;
};

export const updateProductVariant = async (
  productId: number,
  variantId: number,
  data: FormData
): Promise<IProductVariant> => {
  const response = await axios.put(
    `${BASE_URL}/products/${productId}/variants/${variantId}`,
    data,
    {
      headers: { "Content-Type": "multipart/form-data" },
    }
  );
  return response.data.data;
};

export const deleteProductVariant = async (
  productId: number,
  variantId: number
): Promise<{ status: string; message?: string }> => {
  const response = await axios.delete(
    `${BASE_URL}/products/${productId}/variants/${variantId}`
  );
  return response.data;
};
