export interface IProductVariant {
  id: number;
  product_id: number;
  product_size_id: number;
  product_color_id: number;
  quantity: number;
  image?: string;
  created_at: string;
  updated_at: string;
  size?: { id: number; name: string };
  color?: { id: number; name: string };
}
