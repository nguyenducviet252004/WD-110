export interface IProducts{
    id: number;
    name: string;
    price_regular: number;
    description: string;
    thumb_image: string;
    category?: string | ICategory;
    slug: string;
    sku: string;
    price_sale?: number;
    content: string;
    views: number;
    created_at: string;
}
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
export interface ICategory {
  id: number;
  name: string;
  slug: string;
  description: string;
  status?: boolean;
  is_active?: boolean;
  created_at: string;
  updated_at: string;
}