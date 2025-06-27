import React, { useEffect, useState } from "react";
import { Link, useParams, useNavigate, useLocation } from "react-router-dom";
import { notification, Modal, message } from "antd";
import { useSelector } from "react-redux";
import { RootState, useAppDispatch } from "../../Redux/store";
import { fetchCart } from "../../Redux/Reducer/CartReducer";
import { postShipAddress } from "../../Redux/Reducer/ShipAddressReducer";
import api from "../../Axios/Axios";
import axios from "axios";
import { postOrder } from "../../Redux/Reducer/OrderReducer";
import { fetchVouchers } from "../../Redux/Reducer/VoucherReducer";
import { fetchPaymentStatus } from "../../Redux/Reducer/OrderReducer";
import QR from "../../assets/imgs/qr.jpg";
import {
  EnvironmentFilled,
  EnvironmentOutlined,
  EnvironmentTwoTone,
} from "@ant-design/icons";
import { Switch } from "antd";
import "./checkout.css";

const CheckoutComponent: React.FC = () => {
  const dispatch = useAppDispatch();
  const nav = useNavigate();
  const { userId } = useParams<{ userId: string }>();
  const cart = useSelector((state: RootState) => state.cart);
  const payment_url = useSelector(
    (state: RootState) => state.order.payment_url
  );
  const paymentMethod = 2;
  const vouchers = useSelector(
    (state: RootState) => state.voucherReducer.vouchers
  );

  const paymentStatus = useSelector(
    (state: RootState) => state.order.payment_status
  );
  const paymentMessage = useSelector(
    (state: RootState) => state.order.payment_message
  );

  const [totalPrice, setTotalPrice] = useState(0);
  const [recipientName, setRecipientName] = useState("");
  const [email, setEmail] = useState("");
  const [phoneNumber, setPhoneNumber] = useState("");
  const [shipAddress, setShipAddress] = useState("");
  const [paymentMethodId, setPaymentMethodId] = useState<number>(0);
  const [voucherId, setVoucherId] = useState<number | null>(null);
  const [isVoucher, setVouchers] = useState<any[]>([]);
  const [selectedVoucher, setSelectedVoucher] = React.useState(null);
  const [discount, setDiscount] = React.useState(0);
  const [loading, setLoading] = useState(true);
  const [isAddress, setIsAddress] = useState<any>();
  const [showModal, setShowModal] = useState<boolean>(false);
  const [isSwitchOn, setIsSwitchOn] = useState(false);

  const navigate = useNavigate();
  const location = useLocation();
  useEffect(() => {
    if (userId) {
      dispatch(fetchCart(Number(userId)));
    }
    dispatch(fetchVouchers());
  }, [dispatch, userId]);

  const subtotal =
    cart?.items?.reduce(
      (total: number, item: any) => total + item.price * item.quantity,
      0
    ) || 0;

  useEffect(() => {
    const appliedDiscount = voucherId
      ? Number(
          vouchers.find((voucher) => voucher.id === voucherId)?.discount_value
        ) || 0
      : 0;
    setDiscount(appliedDiscount);
    setTotalPrice(subtotal - appliedDiscount);
  }, [cart?.items, voucherId, vouchers]);

  const formatCurrency = (amount: string | number): string => {
    const numberAmount =
      typeof amount === "string" ? parseFloat(amount) : amount;
    return numberAmount.toLocaleString("vi-VN", {
      style: "currency",
      currency: "VND",
    });
  };

  const getVouchers = async () => {
    try {
      const response = await api.get("/vouchers");
      setVouchers(response.data.vouchers);
    } catch (error) {
      console.log(error);
    } finally {
      setLoading(false);
    }
  };

  const getAddress = async () => {
    try {
      const response = await api.get("/address");
      setIsAddress(response.data);
    } catch (error) {
      console.log(error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    getVouchers();
    getAddress();
  }, []);

  console.log("tát cả voi chừo", isAddress);

  const handleModalClose = () => {
    setShowModal(false);
  };

  const onChange = (checked: boolean) => {
    setIsSwitchOn(checked);
    if (!isAddress) {
      message.error("Bạn chưa cập nhật thông tin cá nhân !");
      return;
    }
    if (checked && isAddress) {
      setRecipientName(isAddress.data.recipient_name);
      setPhoneNumber(isAddress.data.phone_number);
      setShipAddress(isAddress.data.ship_address);
    } else {
      setRecipientName("");
      setPhoneNumber("");
      setShipAddress("");
    }
  };

  const handlePaymentSuccess = async (e: React.FormEvent) => {
    e.preventDefault();

    if (
      !recipientName ||
      !email ||
      !phoneNumber ||
      !shipAddress ||
      !paymentMethodId
    ) {
      notification.error({
        message: "Thông tin không đầy đủ",
        description: "Vui lòng điền đầy đủ thông tin vào các trường bắt buộc.",
      });
      return;
    }

    const user = JSON.parse(localStorage.getItem("user") || "{}");
    const userId = user.id ? user.id.toString() : "";
    const addressData = {
      user_id: userId,
      recipient_name: recipientName,
      is_default: true,
      ship_address: shipAddress,
      phone_number: phoneNumber,
    };

    const shipAddressResponse = await dispatch(
      postShipAddress(addressData)
    ).unwrap();

    // Thanh toán
    const orderData = {
      user_id: userId,
      total_amount: totalPrice,
      ship_address_id: shipAddressResponse.id,
      phone_number: phoneNumber,
      subtotal: subtotal,
      totalPrice,
      voucher_id: voucherId,
      status: 1,
      ship_method: 1,
      payment_method: paymentMethodId,
    };

    try {
      // Gửi yêu cầu thanh toán lên API (ví dụ sử dụng Redux action `postOrderPayment`)
      await dispatch(postOrder(orderData));
      if (payment_url && paymentMethod === 2) {
      }
      notification.success({
        message: "Thanh toán thành công",
        description:
          "Cảm ơn bạn đã thanh toán. Đơn hàng của bạn đang được xử lý.",
      });

      // Đóng modal sau khi thanh toán thành công
      setShowModal(false);
    } catch (error: any) {
      const errorMessage = error?.data?.message;
      notification.error({
        message: "Lỗi khi thanh toán",
        description: errorMessage,
        className: "order-sai",
      });
    }
  };

  const handlePlaceOrder = async (e: React.FormEvent) => {
    e.preventDefault();

    if (
      !recipientName ||
      // !email ||
      !phoneNumber ||
      !shipAddress ||
      !paymentMethodId
    ) {
      notification.error({
        message: "Thông tin không đầy đủ",
        description: "Vui lòng điền đầy đủ thông tin vào các trường bắt buộc.",
        className: "validate-thieu",
      });
      return;
    }

    const fetchCartItems = async () => {
      try {
        const token = localStorage.getItem("token");
        const user = localStorage.getItem("user");

        let parsedUser;
        try {
          parsedUser = JSON.parse(user!);
        } catch (error) {
          console.error("Lỗi khi phân tích dữ liệu người dùng:", error);
        }

        if (parsedUser && parsedUser.user) {
          const userId = parsedUser.user.id;

          const response = await axios.get(
            `http://127.0.0.1:8000/api/carts/${userId}`,
            {
              headers: {
                Authorization: `Bearer ${token}`,
              },
            }
          );
          const total = response.data.cart_items.length;
        } else {
          console.error("Không tìm thấy thông tin người dùng.");
        }
      } catch (error) {
        console.error("Lỗi khi lấy dữ liệu giỏ hàng:", error);
      }
    };

    const user = JSON.parse(localStorage.getItem("user") || "{}");
    const userId = user.user.id;
    const addressData = {
      user_id: userId,
      recipient_name: recipientName,
      is_default: true,
      ship_address: shipAddress,
      phone_number: phoneNumber,
    };

    try {
      const shipAddressResponse = await dispatch(
        postShipAddress(addressData)
      ).unwrap();

      const orderData = {
        user_id: userId,
        total_amount: totalPrice,
        ship_address_id: shipAddressResponse.id,
        phone_number: phoneNumber,
        subtotal: subtotal,
        voucher_id: voucherId,
        totalPrice,
        status: 1,
        ship_method: 1,
        payment_method: paymentMethodId,
      };

      const resultAction = await dispatch(postOrder(orderData)).unwrap();
      if (paymentMethodId === 2 && resultAction.payment_url) {
        window.location.href = resultAction.payment_url;
      } else {
      }
      if (paymentMethodId === 1) {
        window.location.href = "/order-success";
      }

      localStorage.removeItem("cartItems");
    } catch (error: any) {
      const errorMessage = error?.data?.message;
      notification.error({
        message: "Lỗi khi thanh toán",
        description: errorMessage,
        className: "order-sai",
      });
    }
  };

  useEffect(() => {
    if (location.search) {
      dispatch(fetchPaymentStatus(location.search));
    }
  }, [location.search, dispatch]);
  useEffect(() => {
    if (paymentStatus) {
      if (paymentStatus === "true") {
        nav("/thank");
      } else {
      }
    }
  }, [paymentStatus, paymentMessage]);

  const handleVoucherChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
    const voucherId = Number(event.target.value);
    setVoucherId(voucherId);

    const selectedVoucher = isVoucher.find((vou) => vou.id === voucherId);
    if (selectedVoucher) {
      setDiscount(selectedVoucher.discount_value || 0);
    } else {
      setDiscount(0);
    }
  };

  useEffect(() => {
    setTotalPrice(subtotal - discount);
  }, [subtotal, discount]);

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
    <main className="main">
      <form onSubmit={handlePlaceOrder}>
        <section className="section block-blog-single block-cart">
          <div className="container">
            <div className="top-head-blog">
              <div className="text-center">
                <h2 className="font-4xl-bold">Thanh toán</h2>
                <div className="breadcrumbs d-inline-block">
                  <ul>
                    <li>
                      <Link to="/">Trang chủ</Link>
                    </li>
                    <li>
                      <Link to="/shop">Cửa hàng</Link>
                    </li>
                    <li>
                      <Link to="/checkout">Thanh toán</Link>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div className="box-table-cart box-form-checkout">
              <div className="row">
                <div className="col-lg-7">
                  <h4
                    className="font-2xl-bold mb-25"
                    style={{
                      fontFamily: "Raleway",
                      fontSize: "28px",
                      fontWeight: "700",
                      marginBottom: "10px",
                    }}
                  >
                    Thông tin thanh toán
                  </h4>
                  {isAddress && (
                    <div className="button-address">
                      <EnvironmentOutlined />
                      <span style={{ marginLeft: "5px" }}>
                        Địa chỉ mặc định của bạn
                      </span>
                      <Switch
                        size="small"
                        style={{ marginLeft: "10px" }}
                        defaultChecked={false}
                        onChange={onChange}
                      />
                    </div>
                  )}
                  <div>
                    <div className="col-lg-6" style={{ width: "100%" }}>
                      <div className="form-group">
                        <label
                          htmlFor=""
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "17px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Họ tên
                        </label>
                        <input
                          className="form-control name-pla"
                          type="text"
                          placeholder="*Nhập họ tên của bạn"
                          name="recipient_name"
                          value={recipientName}
                          onChange={(e) => setRecipientName(e.target.value)}
                        />
                      </div>
                    </div>
                    <div className="col-lg-6" style={{ width: "100%" }}>
                      <div className="form-group name-pla">
                        <label
                          htmlFor=""
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "17px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Số điện thoại
                        </label>
                        <input
                          className="form-control name-pla"
                          type="text"
                          placeholder="*Nhập số điện thoại"
                          name="phone_number"
                          value={phoneNumber}
                          onChange={(e) => setPhoneNumber(e.target.value)}
                        />
                      </div>
                    </div>
                    <div className="col-lg-6" style={{ width: "100%" }}>
                      <div className="form-group">
                        <label
                          htmlFor=""
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "17px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Địa chỉ
                        </label>
                        <input
                          className="form-control name-pla"
                          type="text"
                          name="ship_address"
                          placeholder="*Nhập địa chỉ của bạn"
                          value={shipAddress}
                          onChange={(e) => setShipAddress(e.target.value)}
                          onBlur={() => {
                            // Kiểm tra nếu địa chỉ không rỗng và có ít nhất 5 ký tự
                            if (shipAddress.trim().length < 5) {
                              alert("Địa chỉ phải có ít nhất 5 ký tự.");
                            }

                            // Kiểm tra định dạng địa chỉ
                            const addressRegex =
                              /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9\s,.-]+$/;
                            if (!addressRegex.test(shipAddress)) {
                              alert(
                                "Địa chỉ không hợp lệ. Địa chỉ phải chứa chữ cái và số."
                              );
                            }
                          }}
                        />
                      </div>
                    </div>

                    <div className="col-lg-12">
                      <div className="form-group">
                        <label
                          style={{
                            fontFamily: "Raleway",
                            fontSize: "17px",
                            fontWeight: "600",
                            marginBottom: "10px",
                          }}
                        >
                          *Chọn phương thức thanh toán
                        </label>
                        <select
                          style={{ marginTop: "5px", fontSize: "14px" }}
                          name="paymentMethod"
                          className="form-control"
                          value={paymentMethodId || ""}
                          onChange={(e) =>
                            setPaymentMethodId(Number(e.target.value))
                          }
                        >
                          <option disabled value="" className="name-pla">
                            Chọn phương thức thanh toán*
                          </option>
                          <option value={1}>
                            COD (thanh toán khi nhận hàng)
                          </option>
                          <option value={2}>Thanh toán online</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col-lg-5">
                  <div className="box-total-checkout">
                    <div className="head-total-checkout">
                      <span className="font-xl-bold">Ảnh</span>
                      <span className="font-xl-bold">Tên</span>
                      <span className="font-xl-bold">Số lượng</span>
                      <span className="font-xl-bold">Giá</span>
                    </div>
                    {cart?.items && cart.items.length > 0 ? (
                      cart.items.map((item) => (
                        <div key={item.id} className="box-list-item-checkout">
                          <div className="item-checkout">
                            <span className="title-item">
                              <img
                                src={`http://127.0.0.1:8000/storage/${item.avatar}`}
                                width={"70px"}
                                alt={item.product_name}
                              />
                            </span>
                            <span className="title-item">
                              {item.product_name}
                            </span>
                            <span className="num-item">x{item.quantity}</span>
                            <span className="price-item font-md-bold">
                              {(item.price * item.quantity).toLocaleString(
                                "vi",
                                { style: "currency", currency: "VND" }
                              )}
                            </span>
                          </div>
                        </div>
                      ))
                    ) : (
                      <div>No items in your cart</div>
                    )}
                    {/* Chọn mã giảm giá */}
                    <div className="box-footer-checkout">
                      <div className="form-group">
                        {isVoucher?.length > 0 && (
                          <div className="mb-3">
                            <select
                              className="chon-vou"
                              onChange={handleVoucherChange}
                              value={voucherId || ""}
                            >
                              <option value="" disabled>
                                Chọn mã giảm giá
                              </option>
                              {isVoucher.map((voucher) => (
                                <option key={voucher.id} value={voucher.id}>
                                  {voucher.code} - Giảm{" "}
                                  {formatCurrency(voucher.discount_value)}
                                </option>
                              ))}
                            </select>
                          </div>
                        )}

                        <div className="item-checkout justify-content-between">
                          <span className="font-xl-bold">Tạm tính</span>
                          <span className="font-md-bold">
                            {formatCurrency(subtotal)}
                          </span>
                        </div>
                        <div className="item-checkout justify-content-between">
                          <span className="font-sm">Phí ship</span>
                          <span className="font-md-bold">
                            {/* {(30000).toLocaleString("vi-VN", {
                              style: "currency",
                              currency: "VND",
                            })} */}
                            Free
                          </span>
                        </div>
                        {isVoucher?.length > 0 && (
                          <div className="item-checkout justify-content-between">
                            <span className="font-sm">Mã giảm giá</span>
                            <span className="font-md-bold">
                              {discount > 0 ? formatCurrency(discount) : "0"}
                            </span>
                          </div>
                        )}
                        <div className="item-checkout justify-content-between">
                          <span className="font-xl-bold">Tổng cộng</span>
                          <span
                            className="font-md-bold"
                            style={{ fontSize: "17px" }}
                          >
                            {formatCurrency(subtotal - discount)}
                          </span>
                        </div>
                      </div>
                      <button
                        type="submit"
                        className="btn btn-brand-1-xl-bold w-100 font-md-bold"
                      >
                        Thanh toán
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </form>
    </main>
  );
};

export default CheckoutComponent;
