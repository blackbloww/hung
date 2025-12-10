<?php
include __DIR__ . "/config.php";
date_default_timezone_set('Asia/Ho_Chi_Minh');

//
// --- Helper upload file (SỬA LẠI — CHUẨN NHẤT) ---
//
function upload_file(array $fileElement, string $destDir = 'uploads'): ?string {

    if (!isset($fileElement['name']) || $fileElement['name'] === '') {
        return null;
    }

    if ($fileElement['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // Đảm bảo đường dẫn đầy đủ nằm trong admin/uploads
    $fullDir = __DIR__ . '/' . trim($destDir, '/');

    if (!is_dir($fullDir)) {
        mkdir($fullDir, 0755, true);
    }

    $originalName = basename($fileElement['name']);
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $ext = preg_replace('/[^a-zA-Z0-9]/', '', $ext);

    $filename = time() . '_' . bin2hex(random_bytes(5)) . ($ext ? '.' . $ext : '');
    $target = $fullDir . '/' . $filename;

    if (move_uploaded_file($fileElement['tmp_name'], $target)) {
        return $filename;
    }

    return null;
}


// =============================
// NHẬN DỮ LIỆU PRODUCT
// =============================
$product_name = $_POST['product_name'] ?? '';
$price        = $_POST['price'] ?? '';
$material     = $_POST['material'] ?? '';
$weight       = $_POST['weight'] ?? '';
$size         = $_POST['size'] ?? '';
$note         = $_POST['note'] ?? '';
$create_at    = date("Y-m-d H:i:s");

if ($product_name === "" || $price === "" || $material === "" || $weight === "" || $size === "") {
    die("Vui lòng nhập đầy đủ thông tin!");
}

// Chuẩn hóa giá
// $priceNumeric = preg_replace('/[^0-9]/', '', $price);
// if ($priceNumeric === '') $priceNumeric = 0;

$conn->begin_transaction();

try {
// =============================
// LƯU PRODUCT
// =============================
$sql = "INSERT INTO dtb_product (product_name, price, material, weight, size, note, create_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss",
    $product_name,
    $price,
    $material,
    $weight,
    $size,
    $note,
    $create_at
);

if (!$stmt->execute()) {
    die("Lỗi khi lưu product: " . $stmt->error);
}

$product_id = $stmt->insert_id;
$stmt->close();



// =============================
// COMMENT
// =============================
$commentUserNames = $_POST['comment_user_name'] ?? [];
$commentIsLikes   = $_POST['comment_is_like'] ?? [];
$commentLastDates = $_POST['comment_last_date'] ?? [];
$commentNotes     = $_POST['comment_note'] ?? [];
$avtFiles         = $_FILES['comment_avata'] ?? null;
$imgFiles         = $_FILES['comment_product_image'] ?? null;

if (!empty($commentUserNames)) {

    $sqlC = "INSERT INTO dtb_product_comment 
            (product_id, avatar, user_name, comment, product_image, is_like, last_date, create_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtC = $conn->prepare($sqlC);

    foreach ($commentUserNames as $i => $userName) {

        $avatar = null;
        if ($avtFiles && $avtFiles['name'][$i] !== '') {
            $single = [
                'name' => $avtFiles['name'][$i],
                'type' => $avtFiles['type'][$i],
                'tmp_name' => $avtFiles['tmp_name'][$i],
                'error' => $avtFiles['error'][$i],
                'size' => $avtFiles['size'][$i],
            ];
            $avatar = upload_file($single, 'uploads/comments');
        }

        $pImage = null;
        if ($imgFiles && $imgFiles['name'][$i] !== '') {
            $single = [
                'name' => $imgFiles['name'][$i],
                'type' => $imgFiles['type'][$i],
                'tmp_name' => $imgFiles['tmp_name'][$i],
                'error' => $imgFiles['error'][$i],
                'size' => $imgFiles['size'][$i],
            ];
            $pImage = upload_file($single, 'uploads/comments');
        }

        $is_like    = intval($commentIsLikes[$i] ?? 0);
        $last_date  = $commentLastDates[$i] ?? '';
        $noteC      = $commentNotes[$i] ?? '';
        $created    = date("Y-m-d H:i:s");

        $stmtC->bind_param(
            "issssiss",
            $product_id,
            $avatar,
            $userName,
            $noteC,
            $pImage,
            $is_like,
            $last_date,
            $created
        );

        $stmtC->execute();
    }

    $stmtC->close();
}



// =============================
// CONTENT
// =============================
$contentTitles = $_POST['content_title'] ?? [];
$contentBodies = $_POST['content_body'] ?? [];
$contentFiles  = $_FILES['content_image'] ?? null;

if (!empty($contentTitles)) {

    $sqlCt = "INSERT INTO dtb_product_images_content 
             (product_id, images, title, note, create_at)
             VALUES (?, ?, ?, ?, ?)";

    $stmtCt = $conn->prepare($sqlCt);

    foreach ($contentTitles as $i => $title) {

        $imageFile = null;
        if ($contentFiles && $contentFiles['name'][$i] !== '') {
            $single = [
                'name' => $contentFiles['name'][$i],
                'type' => $contentFiles['type'][$i],
                'tmp_name' => $contentFiles['tmp_name'][$i],
                'error' => $contentFiles['error'][$i],
                'size' => $contentFiles['size'][$i],
            ];
            $imageFile = upload_file($single, 'uploads/content');
        }

        $body    = $contentBodies[$i] ?? '';
        $created = date("Y-m-d H:i:s");

        $stmtCt->bind_param("issss",
            $product_id,
            $imageFile,
            $title,
            $body,
            $created
        );

        $stmtCt->execute();
    }

    $stmtCt->close();
}



// =============================
// SLIDE
// =============================
$slideFiles = $_FILES['slide_image'] ?? null;

if ($slideFiles && !empty($slideFiles['name'])) {

    $sqlSl = "INSERT INTO dtb_product_images_slide (product_id, images, create_at)
              VALUES (?, ?, ?)";

    $stmtSl = $conn->prepare($sqlSl);

    foreach ($slideFiles['name'] as $i => $val) {

        if ($val === '') continue;

        $single = [
            'name' => $slideFiles['name'][$i],
            'type' => $slideFiles['type'][$i],
            'tmp_name' => $slideFiles['tmp_name'][$i],
            'error' => $slideFiles['error'][$i],
            'size' => $slideFiles['size'][$i],
        ];

        $file = upload_file($single, 'uploads/slide');
        $created = date("Y-m-d H:i:s");

        $stmtSl->bind_param("iss", $product_id, $file, $created);
        $stmtSl->execute();
    }

    $stmtSl->close();
}



// =============================
// KẾT THÚC — TRẢ VỀ product.php
// =============================
$conn->commit();
$conn->close();

echo "<script>
    alert('Lưu sản phẩm thành công!');
    window.location.href='product.php';
</script>";
exit;

} catch (Exception $e) {

    // ============================================================
    // LỖI → ROLLBACK
    // ============================================================
    $conn->rollback();

    die("Đã xảy ra lỗi và dữ liệu đã được rollback:<br>" . $e->getMessage());
}

?>
