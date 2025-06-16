import axios from "axios";

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
//products
const getAllProducts = async () => {
  try {
    const response = await axios.get("http://localhost:3001/products");
    return response.data;
  } catch (error) {
    console.error("Error fetching all products:", error);
    throw error;
  }
};
export const getAllService = {
  getAllProducts,
};
