import Ig from "../../assets/imgs/page/homepage1/th1.jpg";
import IgOne from "../../assets/imgs/page/homepage1/th2.jpg";
import IgThree from "../../assets/imgs/page/homepage1/th3.jpg";
import IgFour from "../../assets/imgs/page/homepage1/th4.jpg";
import IgTwo from "../../assets/imgs/page/homepage1/th5.jpg";
import IgFive from "../../assets/imgs/page/homepage1/th6.webp";
import { useEffect, useState } from "react";
import api from "../../Axios/Axios";
import axios from "axios";
const ContactForm: React.FC = () => {
  const [qr, setQr] = useState<any>();
  useEffect(() => {
    const GetQuangCao = async () => {
      try {
        const { data } = await axios.get(
          `http://127.0.0.1:8000/api/logobanner/${5}`
        );
        setQr(data);
      } catch (error) {
        console.log(error);
      }
    };
    GetQuangCao();
  }, []);
  return (
    <div className="section block-blog-single block-contact">
      <div className="container-1190">
        <div className="box-form-contact">
          <h3 className="font-4xl-bold mb-40">Liên hệ</h3>
          <div className="row">
            <div className="col-lg-6">
              {/* QR */}
              <div
                style={{
                  border: "1px solid rgb(190,155,218)",
                  borderLeft: "8px solid rgb(190,155,218)",
                  borderRadius: "8px",
                  display: "flex",
                  justifyContent: "center",
                }}
              >
                <div>
                  <img src={qr?.image} alt="" width={"300px"} />
                </div>
              </div>
            </div>
            <div className="col-lg-6">
              <div className="box-contact-right">
                <h4 className="font-2xl-bold mb-10">
                  Bạn đang cần hỗ trợ hoặc trò chuyện với bộ phận bán hàng?
                </h4>
                <p className="font-md mb-40">
                  Nếu bạn cần hỗ trợ về thẻ 110 Store hiện có, vui lòng gửi
                  email tới: lienhe@110store.vn Để nói chuyện với ai đó trong
                  nhóm bán hàng của chúng tôi, vui lòng nói chuyện với chuyên
                  gia
                </p>
                <h4 className="font-2xl-bold mb-10">Địa chỉ</h4>
                <p className="font-md">
                  <strong className="font-md-bold"> 110 Store</strong>
                  <br />
                  Số 22, ngõ 20/4 phố Nghĩa Đô
                  <br />
                  Cầu Giấy, Hà Nội
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
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
    </div>
  );
};

export default ContactForm;
