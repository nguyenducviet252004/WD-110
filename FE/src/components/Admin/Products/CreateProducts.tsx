import  { useState } from "react";
import type { IProducts } from "../../../types/interface";
import { createProduct } from "../../../servise/productApi";

const CreateProducts = () => {
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  // Function to create a product
  const handleCreateProduct = async (data: Omit<IProducts, "id">) => {
    setLoading(true);
    setError(null);
    try {
      // Chuẩn bị FormData cho API backend
      const formData = new FormData();
      Object.entries(data).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          formData.append(key, value as string | Blob);
        }
      });
      await createProduct(formData as unknown as IProducts);
      alert("Product created successfully");
    } catch (error) {
      setError(
        error instanceof Error ? error.message : "Error creating product"
      );
    } finally {
      setLoading(false);
    }
  };

  return (
    <div>
      <h1 className="text-2xl font-bold mb-4">Create Product</h1>
      {loading && <p>Loading...</p>}
      {error && <p className="text-red-500">{error}</p>}
      <form
        onSubmit={(e) => {
          e.preventDefault();
          const formData = new FormData(e.target as HTMLFormElement);
          const newProduct = {
            name: formData.get("name") as string,
            price_regular: parseFloat(formData.get("price_regular") as string),
            description: formData.get("description") as string,
            thumb_image: formData.get("thumb_image") as string,
            category: formData.get("category") as string,
            slug: formData.get("slug") as string,
            sku: formData.get("sku") as string,
            content: formData.get("content") as string,
            views: 0,
            created_at: new Date().toISOString(),
          };
          handleCreateProduct(newProduct);
        }}
      >
        <input type="text" name="name" placeholder="Product Name" required />
        <input
          type="number"
          name="price_regular"
          placeholder="Regular Price"
          required
        />
        <textarea
          name="description"
          placeholder="Description"
          required
        ></textarea>
        <input
          type="text"
          name="thumb_image"
          placeholder="Thumbnail Image URL"
          required
        />
        <input type="text" name="category" placeholder="Category" required />
        <input type="text" name="slug" placeholder="Slug" required />
        <input type="text" name="sku" placeholder="SKU" required />
        <textarea name="content" placeholder="Content"></textarea>
        <button type="submit">Create Product</button>
      </form>
    </div>
  );
};

export default CreateProducts;
