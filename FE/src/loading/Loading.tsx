import { useState } from "react";
import { LoadingOutlined } from "@ant-design/icons";
import { Flex, Spin } from "antd";
import "./loading.css";
function Loading() {
  const [isLoading, setIsLoading] = useState(false);

  return (
    <Flex align="center" gap="middle">
      <Spin indicator={<LoadingOutlined spin />} size="large" />
    </Flex>
  );
}

export default Loading;
