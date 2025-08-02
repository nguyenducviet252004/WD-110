<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo trạng thái đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #4CAF50;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin: 10px 0;
        }

        .order-id {
            font-weight: bold;
            color: #FF5722;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            margin-top: 20px;
            color: #777;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Thông báo về đơn hàng của bạn</h2>
        <p>Chào bạn,</p>
        <p>Chúng tôi xin thông báo rằng đơn hàng của bạn với mã đơn hàng: <span class="order-id">#{{ $order->id }}</span> đã được duyệt và chuyển sang trạng thái <strong>"Đã duyệt"</strong>.</p>
        <p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi. Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi.</p>
        <p>Trân trọng!</p>
        <div class="footer">
            <p>Đội ngũ hỗ trợ khách hàng của chúng tôi</p>
            <p><a href="mailto:support@shop.com">Liên hệ với chúng tôi</a></p>
        </div>
    </div>

</body>

</html>
