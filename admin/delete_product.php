<?php
    include __DIR__ . "/config.php";
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die("Phương thức không hợp lệ!");
    }

    $id = $_POST['id'] ?? '';

    if ($id === '' || !is_numeric($id)) {
        die("ID sản phẩm không hợp lệ!");
    }

    $id = (int)$id;

    $deleted_at = date("Y-m-d H:i:s");

    $sql = "UPDATE dtb_product SET delete_at = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $deleted_at, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: list_product.php");
        exit();
    } else {
        echo "Lỗi khi xóa mềm: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
?>
