import "./thank.css";
import { DeleteOutlined, CheckOutlined } from "@ant-design/icons";

function ThankYou() {
  return (
    <>
      <section className="thank">
        <div className="thanks">
          <div style={{display:'flex', justifyContent:'center', marginTop:'60px'}}>
            <div className="icon-thank">
              <CheckOutlined
                style={{ fontSize: "30px", color: "rgb(82,196,26)" }}
              />
            </div>
          </div>

          <span className="text-succsess">Thanh toán thành công</span>
          <p className="text-thank">Cảm ơn quý khách đã ủng hộ !</p>
        </div>
      </section>
    </>
  );
}
export default ThankYou;
