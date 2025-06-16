import React from "react";
import { Outlet } from "react-router-dom";
import Header from "../../../components/user/LayoutComponent/Header";
import Footer from "../../../components/user/LayoutComponent/Footer";

const UserLayout = () => {
  return (
    <div style={{ display: "flex", flexDirection: "column", minHeight: "100vh" }}>
      <Header />
      <main style={{ flex: 1, background: "#fafafa", padding: "24px 0" }}>
        <Outlet />
      </main>
      <Footer />
    </div>
  );
};

export default UserLayout;