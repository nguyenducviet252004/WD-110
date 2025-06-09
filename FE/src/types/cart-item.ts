export type ICartItem = {
  id: number;
  name: string;
  price: number;
  quantity: number;
  image: string; // Optional, in case the item does not have an image
};