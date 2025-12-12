<?php
    include __DIR__ . '/layouts/header.php';
    include __DIR__ . "/config.php";

    $sql = "SELECT * FROM dtb_product WHERE delete_at IS NULL ORDER BY id DESC";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query lỗi: " . $conn->error);
    }


?>

    <main class="flex">
        <?php
            include __DIR__ . '/layouts/slidebar.php';
        ?>


        <div class="flex-1 p-8 overflow-hidden bg-[#f9fafb]">
            <div class="overflow-auto pb-2">
                <table class="min-w-[130rem] w-full">
                    <tr class="bg-blue-100">
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">STT</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Ngày tạo</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Tên sản phẩm</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Giá gốc</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Giá bán</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Chất liệu</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Trọng lượng</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Kích thước</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Nơi để phù hợp</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Liên kết trang</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold"></td>
                    </tr>

                     <?php
    $stt = 1;
    $result->data_seek(0); // đảm bảo con trỏ bắt đầu từ đầu
    while ($row = $result->fetch_assoc()):
    ?>
                        <tr class="hover:bg-gray-200 bg-white">
                            <td class="!border border-gray-300 px-2 py-2 text-center text-[1.5rem]"><?= $stt++ ?></td>

                            <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                                <?= htmlspecialchars($row['create_at']) ?>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-center text-[1.5rem] text-blue-500 hover:underline">
                            <a href="edit_product.php?id=<?= $row['id'] ?>"> <?= htmlspecialchars($row['product_name']) ?></a>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-right text-[1.5rem]">
                                <?= htmlspecialchars($row['purchase_price']) ?>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-right text-[1.5rem]">
                                <?= htmlspecialchars($row['price']) ?>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-right text-[1.5rem]">
                                <?= htmlspecialchars($row['material']) ?>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-right text-[1.5rem]">
                                <?= htmlspecialchars($row['weight']) ?>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-right text-[1.5rem]">
                                <?= htmlspecialchars($row['size']) ?>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                                <?= nl2br(htmlspecialchars($row['note'])) ?>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-center text-[1.5rem] text-blue-500">
                            <div class="flex items-center gap-2 justify-between">
                                    <a id="link-<?= $row['id'] ?>" href="/<?= $row['id'] ?>" class="hover:underline" target="_blank">
                                        <?php echo "https://{$_SERVER['HTTP_HOST']}/" . $row['id']?>
                                    </a>
                                    <button 
                                        class="bg-green-500 text-white text-xl px-3 py-1 rounded hover:bg-green-700" 
                                        onclick="copyLink('link-<?= $row['id'] ?>')">
                                        Copy
                                    </button>
                                </div>
                            </td>

                            <td class="!border border-gray-300 px-2 py-2 text-center">
                                <form action="delete_product.php" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button class="bg-red-500 text-white text-xl px-3 py-1 rounded hover:bg-red-700">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </main>

    <script>
        function copyLink(linkId) {
            const linkElement = document.getElementById(linkId);
            const text = linkElement.href;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                // Sử dụng Clipboard API hiện đại
                navigator.clipboard.writeText(text).then(showToast).catch(fallbackCopy);
            } else {
                // Fallback cho các trình duyệt cũ
                fallbackCopy();
            }

            function fallbackCopy() {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    showToast();
                } catch (err) {
                    alert('Sao chép thất bại: ' + err);
                }
                document.body.removeChild(textarea);
            }

            function showToast() {
                const toast = document.createElement('div');
                toast.textContent = 'Đã sao chép!';
                toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded shadow-xl font-medium w-[20rem] h-[5rem] text-[1.6rem] content-center text-center';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            }
        }
    </script>

<?php
    include __DIR__ . '/layouts/footer.php';
?>