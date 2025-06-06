import React from "react";
import Header from "./components/Header/Header";
import Banner from "./components/Banner/Banner";
import "./App.css";
import HomePage from "./pages/HomePage";
import Footer from "./components/Footer/Footer";

function App() {
  return (
    <div className="App">
      <Header />
      <Banner />
      <HomePage />
      <Footer />
    </div>
  );
}

export default App;
