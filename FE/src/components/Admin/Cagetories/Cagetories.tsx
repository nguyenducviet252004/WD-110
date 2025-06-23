import { useEffect, useState } from "react";
import { getAllCategories } from "../../../servise/categoryApi";
import type { ICategory } from "../../../types/interface";

const Cagetories = () => {
  const [categories, setCategories] = useState<ICategory[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        setLoading(true);
        const data = await getAllCategories();
        setCategories(data);
      } catch {
        setError("Failed to fetch categories");
      } finally {
        setLoading(false);
      }
    };
    fetchCategories();
  }, []);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      <h1 className="text-2xl font-bold mb-4">Categories Admin</h1>
      <table className="min-w-full bg-white">
        <thead>
          <tr>
            <th className="py-2 px-4 border-b">ID</th>
            <th className="py-2 px-4 border-b">Name</th>
            <th className="py-2 px-4 border-b">Slug</th>
            <th className="py-2 px-4 border-b">Description</th>
            <th className="py-2 px-4 border-b">Created At</th>
          </tr>
        </thead>
        <tbody>
          {categories.map((category) => (
            <tr key={category.id}>
              <td className="py-2 px-4 border-b">{category.id}</td>
              <td className="py-2 px-4 border-b">{category.name}</td>
              <td className="py-2 px-4 border-b">{category.slug}</td>
              <td className="py-2 px-4 border-b">{category.description}</td>
              <td className="py-2 px-4 border-b">
                {new Date(category.created_at).toLocaleDateString()}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default Cagetories;
