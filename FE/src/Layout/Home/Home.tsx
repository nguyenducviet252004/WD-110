import BannerComponent from "../../components/HomeComponent/BannerComponent";
import CategoriesSlider from "../../components/HomeComponent/CategoriesSlider";
import New from "../../components/HomeComponent/New";
import NewProduct from "../../components/HomeComponent/NewProducts";
import ProductWithCategories from "../../components/HomeComponent/ProductWithCategories";
import ShopByCategory from "../../components/HomeComponent/ShopByCategory";
import TrendingProduct from "../../components/HomeComponent/TrendingProduct";

const Home: React.FC = () => {
  return (
    <>
      <main className="main">
        <BannerComponent />
        {/* <TrendingProduct /> */}
        {/* <NewProduct/>
        <CategoriesSlider /> */}
        <ProductWithCategories />
        {/* <ShopByCategory /> */}
        <New />
      </main>
    </>
  );
};

export default Home;
