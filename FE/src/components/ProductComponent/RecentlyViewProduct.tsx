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
