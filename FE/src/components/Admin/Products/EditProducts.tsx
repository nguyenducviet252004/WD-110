import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import type { IProducts } from "../../../types/interface";
import { getProductById, updateProduct } from "../../../servise/productApi";

const EditProducts = () => {
  const { id } = useParams<{ id: string }>();
  const [form, setForm] = useState<IProducts | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchProduct = async () => {
      setLoading(true);
      setError(null);
      try {
        if (!id) throw new Error("No product id");
        const response = await getProductById(Number(id));
        setForm(response);
      } catch (error) {
        setError(
          error instanceof Error ? error.message : "Error fetching product"
        );
      } finally {
        setLoading(false);
      }
    };
    fetchProduct();
  }, [id]);

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>
  ) => {
    if (!form) return;
    const { name, value } = e.target;
    setForm({
      ...form,
      [name]:
        name === "price_regular" || name === "views" ? Number(value) : value,
    });
  };

  const handleUpdateProduct = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!form || !id) return;
    setLoading(true);
    setError(null);
    try {
      // Chuẩn bị FormData cho API backend
      const formData = new FormData();
      Object.entries(form).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          formData.append(key, value as string | Blob);
        }
      });
      await updateProduct(Number(id), formData as unknown as IProducts);
      alert("Product updated successfully");
    } catch (error) {
      setError(
        error instanceof Error ? error.message : "Error updating product"
      );
    } finally {
      setLoading(false);
    }
  };

  if (loading) return <div>Loading...</div>;
  if (error) return <div className="text-red-500">{error}</div>;
  if (!form) return null;

  return (
    <div>
      <h1 className="text-2xl font-bold mb-4">Edit Product</h1>
      <form onSubmit={handleUpdateProduct}>
        <input
          type="text"
          name="name"
          value={form.name || ""}
          onChange={handleChange}
          placeholder="Product Name"
          required
        />
        <input
          type="number"
          name="price_regular"
          value={form.price_regular || 0}
          onChange={handleChange}
          placeholder="Regular Price"
          required
        />
        <textarea
          name="description"
          value={form.description || ""}
          onChange={handleChange}
          placeholder="Description"
          required
        ></textarea>
        <input
          type="text"
          name="thumb_image"
          value={form.thumb_image || ""}
          onChange={handleChange}
          placeholder="Thumbnail Image URL"
          required
        />
        <input
          type="text"
          name="category"
          value={
            typeof form.category === "object"
              ? form.category?.name || ""
              : form.category || ""
          }
          onChange={handleChange}
          placeholder="Category"
          required
        />
        <input
          type="text"
          name="slug"
          value={form.slug || ""}
          onChange={handleChange}
          placeholder="Slug"
          required
        />
        <input
          type="text"
          name="sku"
          value={form.sku || ""}
          onChange={handleChange}
          placeholder="SKU"
          required
        />
        <textarea
          name="content"
          value={form.content || ""}
          onChange={handleChange}
          placeholder="Content"
        ></textarea>
        <input
          type="number"
          name="views"
          value={form.views || 0}
          onChange={handleChange}
          placeholder="Views"
        />
        <button type="submit">Update Product</button>
      </form>
    </div>
  );
};

export default EditProducts;
