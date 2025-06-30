import About from "../../assets/imgs/page/about/banner.webp";
import Tick from "../../assets/imgs/page/about/tick.png";
import Gallery from "../../assets/imgs/page/about/th1.jpg";
import GalleryTwo from "../../assets/imgs/page/about/th2.jpg";
import GalleryThree from "../../assets/imgs/page/about/th3.jpg";
import GalleryFour from "../../assets/imgs/page/about/th5.jpg";
import GalleryFive from "../../assets/imgs/page/about/th4.jpg";
import Ig from "../../assets/imgs/page/homepage1/th1.jpg";
import IgOne from "../../assets/imgs/page/homepage1/th1.jpg";
import IgThree from "../../assets/imgs/page/homepage1/th2.jpg";
import IgFour from "../../assets/imgs/page/homepage1/th3.jpg";
import IgTwo from "../../assets/imgs/page/homepage1/th4.jpg";
import IgFive from "../../assets/imgs/page/homepage1/th5.jpg";
import Star from "../../assets/imgs/template/icons/star.svg";
import Avatar from "../../assets/imgs/page/homepage2/avatar-review.png";
import { Swiper, SwiperSlide } from "swiper/react";
import { Pagination } from "swiper/modules";
const AboutComponent: React.FC = () => {
  return (
    <>
      <main className="main">
        <section className="section block-blog-single">
          <div className="container">
            <div className="top-head-blog">
              <div className="text-center">
                <h2 className="font-4xl-bold">Giới thiệu về chúng tôi</h2>
                <div className="breadcrumbs d-inline-block">
                  <ul>
                    <li>
                      <a href="#">Trang chủ</a>
                    </li>
                    <li>
                      <a href="#">Giới Thiệu</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div className="feature-image">
              <img src={About} alt="kidify" />
            </div>
            <div className="content-detail">
              <h2>Câu chuyện của chúng tôi</h2>
              <p />
              <p>
                <strong>
                  110 Store được thành lập ngày 21/02/2012, đến nay đã có hơn 11
                  năm trong lĩnh vực phân phối các sản phẩm thể thao trên thị
                  trường. Với đội ngũ nhân viên trẻ, chuyên nghiệp và nhiệt
                  tình, được trang bị đầy đủ kiến thức về sản phẩm, chúng tôi
                  cam kết mang tới sự an tâm về chất lượng và dịch vụ cho mọi
                  sản phẩm. Hệ thống kho hàng chuyên biệt cùng mạng lưới cửa
                  hàng rộng khắp trên toàn quốc, đáp ứng được đầy đủ nhu cầu mua
                  sắm của khách hàng với những sản phẩm độc đáo, sắc nét và chất
                  lượng cao.
                </strong>
              </p>
              <p>
                Sự hài lòng của khách hàng là động lực để 110 Store không ngừng
                phát triển. Với quy trình làm việc chuyên nghiệp, năng động,
                chúng tôi hướng đến xây dựng thương hiệu vững mạnh, trở thành
                lựa chọn lý tưởng của các đại lý phân phối sản phẩm thể thao
                trên cả nước. Chúng tôi tin rằng mọi nỗ lực đều sẽ mang lại
                những giá trị xứng đáng, đồng thời chính là chìa khóa dẫn đến
                thành công.
              </p>
              <div className="box-experiences">
                <div className="row">
                  <div className="col-lg-4">
                    <strong className="font-xl-bold">11 năm</strong>
                    <p className="font-md neutral-500">
                      Chúng tôi có hơn 11 năm kinh nghiệm làm việc.
                    </p>
                  </div>
                  <div className="col-lg-4">
                    <strong className="font-xl-bold">2000+ Nhân viên</strong>
                    <p className="font-md neutral-500">
                      Chúng tôi có hơn 2000 nhân viên làm việc gần bạn.
                    </p>
                  </div>
                  <div className="col-lg-4">
                    <strong className="font-xl-bold">68 Chi nhánh</strong>
                    <p className="font-md neutral-500">
                      Chúng tôi có 68 chi nhánh trên toàn quốc và đang mở rộng
                    </p>
                  </div>
                </div>
              </div>
              <h2>Sứ mệnh của chúng tôi</h2>
              <p>
                110 Store cam kết mang đến đa dạng mẫu mã và các sản phẩm thể
                thao chất lượng, giúp người dùng có trải nghiệm thoải mái và tự
                tin khi sử dụng. Bên cạnh hoạt động phân phối, 110Store còn tích
                cực tham gia tài trợ và các hoạt động cộng đồng, góp phần lan
                tỏa tinh thần thể thao tại Việt Nam và mang lại giá trị thiết
                thực cho xã hội.
              </p>
            </div>
          </div>
          <div className="box-gallery-about">
            <div className="container-1190">
              <h2 className="font-3xl-bold mb-55">
                Phòng trưng bày của chúng tôi
              </h2>
              <div className="box-gallery-list">
                <div className="gallery-main">
                  <a className="glightbox" href={Gallery}>
                    <img src={Gallery} alt="kidify" />
                  </a>
                </div>
                <div className="gallery-sub">
                  <a className="glightbox" href={GalleryTwo}>
                    <img src={GalleryTwo} alt="kidify" />
                  </a>
                  <a className="glightbox" href={GalleryThree}>
                    <img src={GalleryThree} alt="kidify" />
                  </a>
                  <a className="glightbox" href={GalleryFour}>
                    <img src={GalleryFour} alt="kidify" />
                  </a>
                  <a className="glightbox" href={GalleryFive}>
                    <img src={GalleryFive} alt="kidify" />
                  </a>
                </div>
              </div>
            </div>
          </div>
          {/* <div className="box-reviews-about"> */}
          <div className="content-detail mb-20">
            <h2 className="font-3xl-bold">Phong tục hạnh phúc của chúng tôi</h2>
          </div>
          <div className="feature-image mb-0">
            <span className="title-left" />
          </div>
          <div className="container">
            <div className="content-detail">
              <h2>Câu chuyện của chúng tôi</h2>
              <p>
                Đây là bài tập cơ bản nhất mà bạn có thể bỏ qua và có thể thực
                hiện công việc của mình như một công việc khó khăn. Bạn có thể
                bị buộc tội lao động, có phải đối mặt với điều này không phải là
                điều đáng tiếc và sự bất tiện mà bạn có thể gặp phải?
              </p>
            </div>
          </div>
        </section>
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
      </main>
    </>
  );
};
export default AboutComponent;
