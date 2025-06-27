import api from "../../Axios/Axios";
import { useState, useEffect } from "react";
import { useParams } from "react-router-dom";
import "./blog-detail.css";
function BlogDetail() {
  const [blog, setBlog] = useState<any>();
  const { id } = useParams();
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const GetLogo = async () => {
      try {
        const { data } = await api.get(`/blog/${id}`);
        setBlog(data);
      } catch (error) {
        console.log(error);
      } finally {
        setLoading(false)
      }
    };
    GetLogo();
  }, []);

  console.log("KKKK", blog);

  const createdAt = blog?.created_at;

  if (createdAt) {
    const date = new Date(createdAt).toLocaleDateString("vi-VN", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    });

    const time = new Date(createdAt).toLocaleTimeString("vi-VN", {
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      hour12: false,
    });
    const formattedDateTime = `${date} - ${time}`;
  }


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
    <section className="container-detail-blog">
      <div style={{ display: "flex", justifyContent: "center" }}>
        <div>
          <p className="title-blog-detail">{blog?.title}</p>
          <p style={{marginTop:'10px'}}>
            {blog?.created_at &&
              (() => {
                const date = new Date(blog.created_at).toLocaleDateString(
                  "vi-VN",
                  {
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric",
                  }
                );
                const time = new Date(blog.created_at).toLocaleTimeString(
                  "vi-VN",
                  {
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit",
                    hour12: false,
                  }
                );
                return `${date} - ${time}`;
              })()}
          </p>
          <hr className="hr-blog-detail" />
          <p className="des-blog-detail">{blog?.description}</p>
          <img className="img-blog-detail" src={blog?.image} alt="" />
          <p className="content-blog-detail">{blog?.content}</p>
        </div>
      </div>
    </section>
  );
}

export default BlogDetail;
