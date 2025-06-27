import React, { useEffect, useState } from 'react';
import { Table, Spin, Alert, Button, Modal, Input } from 'antd';
import { useDispatch, useSelector } from 'react-redux';
import { RootState, useAppDispatch } from '../../Redux/store';
import { fetchOrderHistory } from '../../Redux/Reducer/OrderHistoryReducer';

const { TextArea } = Input;

const OrderHistoryComponent: React.FC = () => {
  const dispatch = useAppDispatch();

  const userData = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')!) : null;
  const userId = userData ? userData.id : null;

  const { orders, loading, error } = useSelector((state: RootState) => state.OrderHistoryReducer);

  const [currentPage, setCurrentPage] = useState(1); // Lưu trang hiện tại
  const [isModalVisible, setIsModalVisible] = useState(false); // Trạng thái modal
  const [cancelReason, setCancelReason] = useState(''); // Lý do hủy
  const [selectedOrderId, setSelectedOrderId] = useState<number | null>(null); // Đơn hàng đã chọn

  useEffect(() => {
    if (userId) {
      dispatch(fetchOrderHistory(userId));
    }
  }, [dispatch, userId]);

  if (loading) {
    return <Spin size="large" />;
  }

  if (error) {
    return <Alert message="Error" description={error} type="error" />;
  }

  const handleCancel = (orderId: number) => {
    setSelectedOrderId(orderId); // Lưu lại orderId để dùng khi gửi lý do hủy
    setIsModalVisible(true); // Mở modal
  };

  const handleOk = () => {
    if (selectedOrderId && cancelReason) {
      // Xử lý lý do hủy và cập nhật trạng thái đơn hàng
      console.log(`Hủy đơn hàng ${selectedOrderId} vì lý do: ${cancelReason}`);
      // Cập nhật trạng thái đơn hàng (gửi API hoặc cập nhật Redux store)
      setIsModalVisible(false); // Đóng modal
      setCancelReason(''); // Reset lý do hủy
      setSelectedOrderId(null); // Reset orderId
    } else {
      alert('Vui lòng nhập lý do hủy!');
    }
  };

  const handleCancelModal = () => {
    setIsModalVisible(false); // Đóng modal
    setCancelReason(''); // Reset lý do hủy
    setSelectedOrderId(null); // Reset orderId
  };

  const columns = [
    {
      title: 'STT',
      key: 'stt',
      render: (_: any, __: any, index: number) => {
        return (currentPage - 1) * 5 + index + 1; // Tính STT theo trang
      },
    },
    {
      title: 'Tên Sản Phẩm',
      key: 'productNames',
      render: (record: any) => (
        <ul>
          {record.orderDetails.map((detail: any) => (
            <li key={detail.productId}>{detail.productName}</li>
          ))}
        </ul>
      ),
    },
    {
      title: 'Giá Tiền',
      key: 'productPrices',
      render: (record: any) => (
        <ul>
          {record.orderDetails.map((detail: any) => (
            <li key={detail.productId}>
              {detail.price.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}
            </li>
          ))}
        </ul>
      ),
    },
    {
      title: 'Số Lượng',
      key: 'quantity',
      render: (record: any) => {
        const totalQuantity = record.orderDetails.reduce((sum: number, detail: any) => sum + detail.quantity, 0);
        return totalQuantity;
      },
    },
    {
      title: 'Tổng tiền',
      dataIndex: 'totalAmount',
      key: 'totalAmount',
      render: (text: number) => {
        return text.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
      },
    },
    {
      title: 'Địa Chỉ',
      dataIndex: 'shipAddress',
      key: 'shipAddress',
      render: (text: { shipAddress: string }) => text.shipAddress,
    },
    {
      title: 'Số Điện Thoại',
      dataIndex: 'shipAddress',
      key: 'phoneNumber',
      render: (text: { phoneNumber: string }) => text.phoneNumber,
    },
    {
      title: 'Hành Động',
      key: 'action',
      render: (record: any) => (
        <Button onClick={() => handleCancel(record.orderId)}>
          Hủy Đơn
        </Button>
      ),
    },
  ];

  return (
    <div className="container order_history">
      <Table
        dataSource={orders}
        columns={columns}
        rowKey="orderId"
        pagination={{
          current: currentPage,
          pageSize: 5,
          total: orders.length,
          onChange: (page) => {
            setCurrentPage(page);
          },
        }}
      />

      {/* Modal hủy đơn hàng */}
      <Modal
        title="Lý Do Hủy"
        visible={isModalVisible}
        onOk={handleOk}
        onCancel={handleCancelModal}
      >
        <TextArea
          rows={4}
          value={cancelReason}
          onChange={(e) => setCancelReason(e.target.value)}
          placeholder="Nhập lý do hủy đơn hàng"
        />
      </Modal>
    </div>
  );
};

export default OrderHistoryComponent;
