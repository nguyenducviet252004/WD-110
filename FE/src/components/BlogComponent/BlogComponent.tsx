import Arrow from "../../assets/imgs/template/icons/arrow-sm.svg";
import Blog from "../../assets/imgs/page/blog/blog1.webp";
import BlogTwo from "../../assets/imgs/page/blog/blog2.webp";
import BlogThree from "../../assets/imgs/page/blog/blog3.webp";
import BlogFour from "../../assets/imgs/page/blog/blog4.webp";
import api from "../../Axios/Axios";
import axios from "axios";
import { Link } from "react-router-dom";
import { useState, useEffect } from "react";
import { message, Pagination } from "antd";
import type { PaginationProps } from "antd";
const BlogComponent: React.FC = () => {
  const [blog, setBlog] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const pageSize = 5;
  const [current, setCurrent] = useState(1);

  const onChange: PaginationProps["onChange"] = (page) => {
    console.log(page);
    setCurrent(page);
  };
  useEffect(() => {
    const GetLogo = async () => {
      try {
        const { data } = await api.get(`/blog`);
        setBlog(data);
      } catch (error) {
        console.log(error);
      } finally {
        setLoading(false);
      }
    };
    GetLogo();
  }, []);

  const paginatedBlog = blog.slice(
    (current - 1) * pageSize,
    current * pageSize
  );

  const maxLength = 20;
  const truncateText = (text: any) => {
    if (text.length > maxLength) {
      return text.substring(0, maxLength) + "...";
    }
    return text;
  };

  const maxLengths = 60;
  const truncateText2 = (text: any) => {
    if (text.length > maxLengths) {
      return text.substring(0, maxLengths) + "...";
    }
    return text;
  };

  if (loading)
    return (
      <div>
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
      </div>
    );

  return (
    <>
      <main className="main">
        <section className="section block-shop-head block-blog-head">
          <div className="container">
            <h1 className="font-5xl-bold neutral-900">Tin tức</h1>
            <div className="breadcrumbs">
              <ul>
                <li>
                  <a href="#">Trang chủ</a>
                </li>
                <li>
                  <a href="#">Tin tức</a>
                </li>
              </ul>
            </div>
          </div>
        </section>
        <section className="section content-products">
          <div className="container">
            <div className="box-blog-column">
              <div className="col-1">
                <div className="box-inner-blog-padding">
                  {paginatedBlog.map((b, index) => (
                    <Link to={`/blog-detail/${b.id}`}>
                      <div key={index} className="cardBlogStyle1">
                        <img src={b.image} alt="kidify" />
                        <div className="cardInfo">
                          <h2 className="font-42-bold mb-10">{b.title}</h2>
                          <p className="font-lg">{b.description}</p>
                          <div className="mt-25 text-end">
                            <a className="btn btn-arrow-right" href="#">
                              Xem chi tiết
                              <img src={Arrow} alt="Kidify" />
                              <img
                                className="hover-icon"
                                src={Arrow}
                                alt="Kidify"
                              />
                            </a>
                          </div>
                        </div>
                      </div>
                    </Link>
                  ))}
                  <nav className="box-pagination" style={{ float: "right" }}>
                    <Pagination
                      current={current}
                      onChange={onChange}
                      total={blog.length}
                      pageSize={pageSize}
                    />
                  </nav>
                </div>
              </div>
              {/* Aside Blog */}
              <div className="col-2">
                <div className="sidebar-right">
                  <div
                    className="row"
                    data-masonry='{"percentPosition": true }'
                  >
                    <div className="col-lg-12 col-md-6">
                      <div className="sidebar-border">
                        <h5 className="font-3xl-bold head-sidebar">
                          Bài viết mới nhất
                        </h5>
                        <div className="content-sidebar">
                          <ul className="list-featured-posts">
                            {blog.map((b, index) => (
                              <li key={index}>
                                <Link to={`/blog-detail/${b.id}`}>
                                  <div className="cardFeaturePost">
                                    <div className="cardImage">
                                      <img src={b.image} width={"120px"} />
                                    </div>
                                    <div className="cardInfo">
                                      <span className="lbl-tag-brand">
                                        {truncateText(b.title)}
                                      </span>
                                      <a
                                        className="font-sm-bold link-feature"
                                        href="#"
                                      >
                                        {truncateText2(b.description)}
                                      </a>
                                    </div>
                                  </div>
                                </Link>
                              </li>
                            ))}
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
    </>
  );
};
export default BlogComponent;
