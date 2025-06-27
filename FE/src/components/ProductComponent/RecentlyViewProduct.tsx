import ProductOne from "../../assets/imgs/page/homepage1/product1.png";
import ProductFour from "../../assets/imgs/page/homepage1/product4.png";
import Ig from "../../assets/imgs/page/homepage1/th1.jpg";
import IgOne from "../../assets/imgs/page/homepage1/th2.jpg";
import IgThree from "../../assets/imgs/page/homepage1/th3.jpg";
import IgFour from "../../assets/imgs/page/homepage1/th4.jpg";
import IgTwo from "../../assets/imgs/page/homepage1/th5.jpg";
import IgFive from "../../assets/imgs/page/homepage1/th6.webp";
const RecentlyViewProduct: React.FC = () => {
  return (
    <>
      {/* <section className="section block-may-also-like">
                <div className="container">
                    <div className="text-center mb-35">
                        <h4 className="text-uppercase brand-1 brush-bg mb-15">Recently Viewed Products</h4>
                        <p className="font-lg neutral-500">Add these amazing products to your cart</p>
                    </div>
                    <div className="row">
                        <div className="col-lg-3 col-sm-6">
                            <div className="cardProduct wow fadeInUp">
                                <div className="cardImage">
                                    <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={ProductOne} alt="kidify" /><img className="imageHover" src={ProductFour} alt="kidify" /></a>
                                    <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                                    <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                                        <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg></a><a className="btn" href="#">
                                            <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                                <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                            </svg></a><a className="btn" href="#">
                                            <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg></a></div>
                                </div>
                                <div className="cardInfo"><a href="product-single.html">
                                    <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                                    <p className="font-lg cardDesc">$16.00</p>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-3 col-sm-6">
                            <div className="cardProduct wow fadeInUp">
                                <div className="cardImage">
                                    <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={ProductOne} alt="kidify" /><img className="imageHover" src={ProductFour} alt="kidify" /></a>
                                    <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                                    <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                                        <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg></a><a className="btn" href="#">
                                            <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                                <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                            </svg></a><a className="btn" href="#">
                                            <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg></a></div>
                                </div>
                                <div className="cardInfo"><a href="product-single.html">
                                    <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                                    <p className="font-lg cardDesc">$16.00</p>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-3 col-sm-6">
                            <div className="cardProduct wow fadeInUp">
                                <div className="cardImage">
                                    <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={ProductOne} alt="kidify" /><img className="imageHover" src={ProductFour} alt="kidify" /></a>
                                    <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                                    <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                                        <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg></a><a className="btn" href="#">
                                            <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                                <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                            </svg></a><a className="btn" href="#">
                                            <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg></a></div>
                                </div>
                                <div className="cardInfo"><a href="product-single.html">
                                    <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                                    <p className="font-lg cardDesc">$16.00</p>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-3 col-sm-6">
                            <div className="cardProduct wow fadeInUp">
                                <div className="cardImage">
                                    <label className="lbl-hot">hot</label><a href="product-single.html"><img className="imageMain" src={ProductOne} alt="kidify" /><img className="imageHover" src={ProductFour} alt="kidify" /></a>
                                    <div className="button-select"><a href="product-single.html">Add to Cart</a></div>
                                    <div className="box-quick-button"><a className="btn" aria-label="Quick view" data-bs-toggle="modal" data-bs-target="#quickViewModal">
                                        <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg></a><a className="btn" href="#">
                                            <svg className="d-inline-flex align-items-center justify-content-center" width={18} height={18} viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.25 3.75L15.75 5.25M15.75 5.25L14.25 6.75M15.75 5.25H5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M3.75 14.25L2.25 12.75M2.25 12.75L3.75 11.25M2.25 12.75L12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                <path d="M2.25 8.25C2.25 6.59315 3.59315 5.25 5.25 5.25" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                                <path d="M15.75 9.75C15.75 11.4069 14.4069 12.75 12.75 12.75" stroke="#294646" strokeWidth="1.5" strokeLinecap="round" />
                                            </svg></a><a className="btn" href="#">
                                            <svg className="d-inline-flex align-items-center justify-content-center" fill="none" stroke="currentColor" strokeWidth="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg></a></div>
                                </div>
                                <div className="cardInfo"><a href="product-single.html">
                                    <h6 className="font-md-bold cardTitle">Lace Shirt Cut II</h6></a>
                                    <p className="font-lg cardDesc">$16.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section> */}
      <section className="section block-section-10">
        <div className="container">
          <div className="top-head justify-content-center">
            <h4 className="text-uppercase brand-1 wow fadeInDown">
              instagram feed
            </h4>
          </div>
        </div>
        <div className="box-gallery-instagram">
          <div className="box-gallery-instagram-inner">
            <div className="gallery-item wow fadeInLeft">
              <img src={Ig} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInUp">
              <img src={IgTwo} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInUp">
              <img src={IgThree} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInUp">
              <img src={IgFour} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInRight">
              <img src={IgFive} alt="kidify" />
            </div>
            <div className="gallery-item wow fadeInRight">
              <img src={IgOne} alt="kidify" />
            </div>
          </div>
        </div>
      </section>
    </>
  );
};

export default RecentlyViewProduct;
