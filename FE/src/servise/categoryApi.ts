import axios from "axios";
import type { ICategory } from "../types/interface";



const BASE_URL = "http://localhost:8000/api";

export const getAllCategories = async (): Promise<ICategory[]> => {
  const response = await axios.get(`${BASE_URL}/categories`);
  return response.data.data;
};

export const getCategoryById = async (id: number): Promise<ICategory> => {
  const response = await axios.get(`${BASE_URL}/categories/${id}`);
  return response.data.data;
};

export const createCategory = async (data: Partial<ICategory>): Promise<ICategory> => {
  const response = await axios.post(`${BASE_URL}/categories`, data);
  return response.data.data;
};

export const updateCategory = async (id: number, data: Partial<ICategory>): Promise<ICategory> => {
  const response = await axios.put(`${BASE_URL}/categories/${id}`, data);
  return response.data.data;
};

export const deleteCategory = async (id: number): Promise<{ status: string; message?: string }> => {
  const response = await axios.delete(`${BASE_URL}/categories/${id}`);
  return response.data;
};
