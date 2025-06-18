import React, { useEffect, useState } from "react";
import type { IProducts } from "../../../types/interface";
import { allProductsService } from "../../../servise/fetch";
import axios from "axios";
import { Link } from "react-router-dom";

const ProductsAdmin = () => {
  const [products, setProducts] = useState<IProducts[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const getAll = async () => {
      try {
        setLoading(true);
        const response = await allProductsService.getAllProducts();
        setProducts(response);
      } catch (err) {
        setError("Failed to fetch products");
      } finally {
        setLoading(false);
      }
    };
    getAll();
  }, []);

  if (loading) {
    return <div>Loading...</div>;
  }
  if (error) {
    return <div>Error: {error}</div>;
  }

  const deleteProduct = async (id: number) => {
    try {
      const response = await axios.delete(
        `http://localhost:3001/products/${id}`
      );
      if (response.status === 200) {
        setProducts(products.filter((product: IProducts) => product.id !== id));
      }
    } catch (err) {
      setError("Failed to delete product");
    }
  };

  const handleDelete = (id: number) => {
    if (window.confirm("Are you sure you want to delete this product?")) {
      deleteProduct(id);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-2xl font-bold mb-4">Products Admin</h1>
      <Link
        to={"/admin/create"}
        className="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        <input type="button" value="Create" />
      </Link>
      <table className="min-w-full bg-white border border-gray-200">
        <thead>
          <tr>
            <th className="py-2 px-4 border-b">ID</th>
            <th className="py-2 px-4 border-b">Name</th>
            <th className="py-2 px-4 border-b">Price</th>
            <th className="py-2 px-4 border-b">Category</th>
            <th className="py-2 px-4 border-b">Slug</th>
            <th className="py-2 px-4 border-b">SKU</th>
            <th className="py-2 px-4 border-b">Sale Price</th>
            <th className="py-2 px-4 border-b">Description</th>
            <th className="py-2 px-4 border-b">Content</th>
            <th className="py-2 px-4 border-b">Views</th>
            <th className="py-2 px-4 border-b">Created At</th>
            <th className="py-2 px-4 border-b">Thumbnail</th>
            <th className="py-2 px-4 border-b">Actions</th>
          </tr>
        </thead>

        <tbody>
          {products.map((product: IProducts) => (
            <tr key={product.id}>
              <td className="py-2 px-4 border-b">{product.id}</td>
              <td className="py-2 px-4 border-b">{product.name}</td>
              <td className="py-2 px-4 border-b">${product.price_regular}</td>
              <td className="py-2 px-4 border-b">{product.category}</td>
              <td className="py-2 px-4 border-b">{product.slug}</td>
              <td className="py-2 px-4 border-b">{product.sku}</td>
              <td className="py-2 px-4 border-b">
                {product.price_sale ? `$${product.price_sale}` : "N/A"}
              </td>
              <td className="py-2 px-4 border-b">{product.description}</td>
              <td className="py-2 px-4 border-b">{product.content}</td>
              <td className="py-2 px-4 border-b">{product.views}</td>
              <td className="py-2 px-4 border-b">{product.created_at}</td>
              <td className="py-2 px-4 border-b">{product.thumb_image}</td>
              <td className="py-2 px-4 border-b">
                <Link to={`/admin/edit/${product.id}`}>
                  <button className="text-blue-500 hover:underline">
                    Edit
                  </button>
                </Link>
                <button
                  className="text-red-500 hover:underline"
                  onClick={() => handleDelete(product.id)}
                >
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ProductsAdmin;
