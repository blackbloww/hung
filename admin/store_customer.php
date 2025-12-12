<?php
// store_customer.php

// 1. Kết nối database
include __DIR__ . '/config.php'; // hoặc file config kết nối DB của bạn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. Lấy dữ liệu từ form, dùng filter để bảo vệ
    $product_id    = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
    $tel           = isset($_POST['tel']) ? trim($_POST['tel']) : '';
    $address       = isset($_POST['address']) ? trim($_POST['address']) : '';
    $status        = 1;
    // 4. Lưu vào database
   $stmt = $conn->prepare("INSERT INTO dtb_customer (product_id, customer_name, tel, address, status, create_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isssi", $product_id, $customer_name, $tel, $address, $status );


    if ($stmt->execute()) {
        // 5. Thành công
        $product_link = "/" . $product_id;
        echo "<script>
            alert('Bạn đã đặt hàng thành công! Chúng tôi sẽ sớm liên hệ tới bạn.');
            window.location.href='{$product_link}';
        </script>";
        // Có thể redirect về trang cảm ơn
        // header("Location: /thank_you.php");
        // exit;
    } else {
        // Lỗi SQL
        echo "<p style='color:red;'>Đặt hàng thất bại: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Nếu truy cập trực tiếp
    header("Location: /");
    exit;
}
