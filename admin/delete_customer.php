<?php
include __DIR__ . '/config.php';

$data = json_decode(file_get_contents('php://input'), true);
if(!$data || !isset($data['id'])){
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$id = intval($data['id']);

// Xóa mềm: cập nhật deleted_at
$stmt = $conn->prepare("UPDATE dtb_customer SET delete_at = NOW() WHERE id = ?");
$stmt->bind_param("i", $id);

if($stmt->execute()){
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
