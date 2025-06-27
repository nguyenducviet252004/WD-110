import { Link } from "react-router-dom";

const Maps: React.FC = () => {
  return (
    <>
      <section className="section block-blog-single block-contact">
        <div className="container">
          <div className="top-head-blog">
            <div className="text-center">
              <h2 className="font-4xl-bold">Liên hệ</h2>
              <div className="breadcrumbs d-inline-block">
                <ul>
                  <li>
                    <Link to="/">Trang chủ</Link>
                  </li>
                  <li>
                    <Link to="/contact">Liên hệ</Link>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div className="container-1190">
          <div className="box-map">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m30!1m12!1m3!1d1861.782353884689!2d105.79318873859448!3d21.05009624515116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m15!3e2!4m3!3m2!1d21.0513203!2d105.79231279999999!4m5!1s0x3135ab24e0060115%3A0xaefdda57e38f9a1e!2zMjIgTmcuIDIwLzQsIE5naMSpYSDEkMO0LCBUw6J5IEjhu5MsIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!3m2!1d21.048797099999998!2d105.7967867!4m3!3m2!1d21.050541799999998!2d105.79275419999999!5e0!3m2!1svi!2s!4v1733848050314!5m2!1svi!2s"
              width="600"
              height="450"
            ></iframe>
          </div>
        </div>
      </section>
    </>
  );
};

export default Maps;
