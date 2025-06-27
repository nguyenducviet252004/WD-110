import React, { useEffect } from "react";
import { useParams } from "react-router-dom";
import OrderHistoryComponent from "../../components/OrderHistoryComponent/OrderHistoryComponent";

const OrderHistory: React.FC = () => {
  const { userId } = useParams<{ userId: string }>();

  useEffect(() => {
    if (userId) {
      console.log(`Fetching order history for user ${userId}`);
    }
  }, [userId]);

  return (
    <>
      <OrderHistoryComponent />
    </>
  );
};

export default OrderHistory;
