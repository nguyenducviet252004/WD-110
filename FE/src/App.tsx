import React, { useEffect, useState } from 'react';
import Header from './components/LayoutComponent/Header';
import Footer from './components/LayoutComponent/Footer';
import AppRoute from './router/Route';

const App = () => {
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => {
      setLoading(false);
    }, 1000);
    return () => clearTimeout(timer);
  }, []);

  return (
    <div>
      {loading ? (
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
      ) : (
        <>
          <Header />
          <main className="main">
            <AppRoute />
          </main>
          <Footer />
        </>
      )}
    </div>
  );
};

export default App;