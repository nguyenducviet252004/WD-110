export interface IProducts{
    id: number;
    name: string;
    price_regular: number;
    description: string;
    thumb_image: string;
    category: string;
    slug: string;
    sku: string;
    price_sale?: number;
    content: string;
    views: number;
    created_at: string;
}