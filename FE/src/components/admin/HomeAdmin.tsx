import { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';


import { Link } from 'react-router-dom';
import { fetchProducts } from '../../features/products/productSlice';
import type { RootState } from '../../Redux/store';
import type { AppDispatch } from '../../Redux/store';
type Product = {
  id: number;
  name: string;
  price: number;
  // add other fields as needed
};

export default function Home() {
  const dispatch: AppDispatch = useDispatch();
  const products = useSelector((state: RootState) => state.products.items) as Product[];

  useEffect(() => {
    dispatch(fetchProducts());
  }, [dispatch, products]);

  return (
    <div>
      <h1>Danh sách áo quần bóng đá</h1>
      <ul>
        {products.map((p: Product) => (
          <li key={p.id}>
            <Link to={`/product/${p.id}`}>{p.name}</Link> - {p.price}₫
          </li>
        ))}
      </ul>
    </div>
  );
}