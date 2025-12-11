<?php
include __DIR__ . "/config.php";
date_default_timezone_set('Asia/Ho_Chi_Minh');

function upload_file(array $fileElement, string $destDir = 'uploads'): ?string {
    if (!isset($fileElement['name']) || $fileElement['name'] === '') return null;
    if ($fileElement['error'] !== UPLOAD_ERR_OK) return null;

    $fullDir = __DIR__ . '/' . trim($destDir, '/');
    if (!is_dir($fullDir)) mkdir($fullDir, 0755, true);

    $ext = pathinfo($fileElement['name'], PATHINFO_EXTENSION);
    $ext = preg_replace('/[^a-zA-Z0-9]/', '', $ext);

    $filename = time() . '_' . bin2hex(random_bytes(5)) . ($ext ? ".$ext" : "");

    if (move_uploaded_file($fileElement['tmp_name'], "$fullDir/$filename")) return $filename;
    return null;
}


/* ----------------------------------------
   NHẬN PRODUCT ID
---------------------------------------- */
$product_id = intval($_POST['product_id'] ?? 0);
if ($product_id <= 0) die("Không có PRODUCT ID");


/* ----------------------------------------
   NHẬN DỮ LIỆU PRODUCT
---------------------------------------- */
$product_name = $_POST['product_name'] ?? '';
$price        = $_POST['price'] ?? '';
$material     = $_POST['material'] ?? '';
$weight       = $_POST['weight'] ?? '';
$size         = $_POST['size'] ?? '';
$note         = $_POST['note'] ?? '';

if (!$product_name || !$price || !$material || !$weight || !$size) {
    die("Thiếu dữ liệu product!");
}

$conn->begin_transaction();

try {

/* ----------------------------------------
   UPDATE PRODUCT
---------------------------------------- */
$stmt = $conn->prepare("
    UPDATE dtb_product SET 
        product_name=?, price=?, material=?, weight=?, size=?, note=?
    WHERE id=?
");

$stmt->bind_param("ssssssi", 
    $product_name,
    $price,
    $material,
    $weight,
    $size,
    $note,
    $product_id
);
$stmt->execute();
$stmt->close();


/* ----------------------------------------
   XÓA COMMENT CŨ
---------------------------------------- */
$conn->query("DELETE FROM dtb_product_comment WHERE product_id = $product_id");


/* ----------------------------------------
   THÊM COMMENT (CÓ GIỮ ẢNH CŨ)
---------------------------------------- */
$commentUserNames = $_POST['comment_user_name'] ?? [];
$commentNotes     = $_POST['comment_note'] ?? [];
$commentIsLikes   = $_POST['comment_is_like'] ?? [];
$commentDates     = $_POST['comment_last_date'] ?? [];

$oldAvatars       = $_POST['old_comment_avatar'] ?? [];
$oldImages        = $_POST['old_comment_product_image'] ?? [];

$avtFiles         = $_FILES['comment_avata'] ?? null;
$imgFiles         = $_FILES['comment_product_image'] ?? null;

if (!empty($commentUserNames)) {

    $stmtC = $conn->prepare("
        INSERT INTO dtb_product_comment 
        (product_id, avatar, user_name, comment, product_image, is_like, last_date, create_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($commentUserNames as $i => $userName) {

        // ===== AVATAR =====
        if (!empty($avtFiles['name'][$i])) {
            $avatar = upload_file([
                'name'     => $avtFiles['name'][$i],
                'type'     => $avtFiles['type'][$i],
                'tmp_name' => $avtFiles['tmp_name'][$i],
                'error'    => $avtFiles['error'][$i],
                'size'     => $avtFiles['size'][$i],
            ], 'uploads/comments');
        } else {
            $avatar = $oldAvatars[$i] ?? null;
        }

        // ===== PRODUCT IMAGE =====
        if (!empty($imgFiles['name'][$i])) {
            $pImage = upload_file([
                'name'     => $imgFiles['name'][$i],
                'type'     => $imgFiles['type'][$i],
                'tmp_name' => $imgFiles['tmp_name'][$i],
                'error'    => $imgFiles['error'][$i],
                'size     '=> $imgFiles['size'][$i],
            ], 'uploads/comments');
        } else {
            $pImage = $oldImages[$i] ?? null;
        }

        $is_like    = intval($commentIsLikes[$i] ?? 0);
        $last_date  = $commentDates[$i] ?? '';
        $created_at = date("Y-m-d H:i:s");

        // FIX TYPE — đúng chuẩn
        $stmtC->bind_param(
            "issssiss",
            $product_id,
            $avatar,
            $userName,
            $commentNotes[$i],
            $pImage,
            $is_like,
            $last_date,
            $created_at
        );

        $stmtC->execute();
    }

    $stmtC->close();
}


/* ----------------------------------------
   CONTENT (GIỮ ẢNH CŨ)
---------------------------------------- */
$conn->query("DELETE FROM dtb_product_images_content WHERE product_id = $product_id");

$contentTitles = $_POST['content_title'] ?? [];
$contentBodies = $_POST['content_body'] ?? [];
$oldContentImg = $_POST['old_content_images'] ?? [];
$contentFiles  = $_FILES['content_image'] ?? null;

if (!empty($contentTitles)) {

    $stmtCt = $conn->prepare("
        INSERT INTO dtb_product_images_content (product_id, images, title, note, create_at)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($contentTitles as $i => $title) {

        if (!empty($contentFiles['name'][$i])) {
            $file = upload_file([
                'name'     => $contentFiles['name'][$i],
                'type'     => $contentFiles['type'][$i],
                'tmp_name' => $contentFiles['tmp_name'][$i],
                'error'    => $contentFiles['error'][$i],
                'size'     => $contentFiles['size'][$i],
            ], 'uploads/content');
        } else {
            $file = $oldContentImg[$i] ?? null;
        }

        $stmtCt->bind_param(
            "issss",
            $product_id,
            $file,
            $title,
            $contentBodies[$i],
            date("Y-m-d H:i:s")
        );

        $stmtCt->execute();
    }

    $stmtCt->close();
}


/* ----------------------------------------
   SLIDE (GIỮ ẢNH CŨ)
---------------------------------------- */
$conn->query("DELETE FROM dtb_product_images_slide WHERE product_id = $product_id");

$oldSlideImg = $_POST['old_slide_image'] ?? [];
$slideFiles  = $_FILES['slide_image'] ?? null;

$stmtSl = $conn->prepare("
    INSERT INTO dtb_product_images_slide (product_id, images, create_at)
    VALUES (?, ?, ?)
");

foreach ($slideFiles['name'] as $i => $name) {

    if ($name !== '') {
        $img = upload_file([
            'name'     => $slideFiles['name'][$i],
            'type'     => $slideFiles['type'][$i],
            'tmp_name' => $slideFiles['tmp_name'][$i],
            'error'    => $slideFiles['error'][$i],
            'size'     => $slideFiles['size'][$i],
        ], 'uploads/slide');
    } else {
        $img = $oldSlideImg[$i] ?? null;
    }

    $stmtSl->bind_param("iss", $product_id, $img, date("Y-m-d H:i:s"));
    $stmtSl->execute();
}

$stmtSl->close();


/* ----------------------------------------
   COMMIT
---------------------------------------- */
$conn->commit();

echo "<script>
    alert('Cập nhật sản phẩm thành công!');
    window.location.href='product.php';
</script>";
exit;


} catch (Exception $e) {

    $conn->rollback();
    die("Lỗi cập nhật:<br>" . $e->getMessage());
}

?>
