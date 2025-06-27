import React, { useState, useEffect } from "react";
import Header from "./components/LayoutComponent/Header";
import Footer from "./components/LayoutComponent/Footer";
import AppRoutes from "./Routes/Route";
import SvgLoading from "./assets/imgs/template/favicon.svg";

function App() {
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => {
      setLoading(false);
    }, 1000);

    return () => clearTimeout(timer);
  }, []);

  return (
    <div className="App">
      {loading && (
        <div id="preloader-active">
          <div className="preloader d-flex align-items-center justify-content-center">
            <div className="preloader-inner position-relative">
              <div className="page-loading text-center">
                <div className="page-loading-inner">
                  <div />
                  <div />
                  <div />
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
      {!loading && (
        <>
          <Header />
          <main className="main">
            <AppRoutes />
          </main>
          <Footer />
        </>
      )}
    </div>
  );
}

export default App;
