export type ICartItem = {
  id: number;
  name: string;
  price: number;
  quantity: number;
  image: string; // Optional, in case the item does not have an image
};
export interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    stock: number;
}
export interface Order {
    id: number;
    userId: number;
    productIds: number[];
    orderDate: Date;
}
export interface User {
    id: number;
    username: string;
    password: string;
    role: 'admin' | 'user';
}