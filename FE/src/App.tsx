import React from "react";
import Header from "./components/user/LayoutComponent/Header";
import Footer from "./components/user/LayoutComponent/Footer";
import AppRoute from "./router/Route";

const App = () => {
  return (
    <div>
      <Header />
      <AppRoute />
      <Footer />
    </div>
  );
};

export default App;
