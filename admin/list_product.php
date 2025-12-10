<?php
    include __DIR__ . '/layouts/header.php';
    include __DIR__ . "/config.php";

    $sql = "SELECT * FROM dtb_product WHERE delete_at IS NULL ORDER BY id DESC";
    $result = $conn->query($sql);
?>

    <main class="flex">
        <?php
            include __DIR__ . '/layouts/slidebar.php';
        ?>


        <div class="flex-1 p-8 overflow-hidden bg-[#f9fafb]">
            <div class="overflow-auto pb-2">
                <table class="min-w-[70rem]">
                    <tr class="bg-blue-100">
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">STT</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Ngày tạo</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Tên sản phẩm</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Giá</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Chất liệu</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Trọng lượng</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Kích thước</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Ghi chú</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Hành động</td>
                    </tr>

                    <?php
                        $stt = 1;
                        if ($result && $result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                    ?>
                    <tr class="hover:bg-gray-200 bg-white">
                        <td class="!border border-gray-300 px-2 py-2 text-center text-[1.5rem]"><?= $stt++ ?></td>
                        <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                            <?= htmlspecialchars($row['create_at']) ?>
                        </td>
                        <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                            <?= htmlspecialchars($row['product_name']) ?>
                        </td>

                        <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                            <?= htmlspecialchars($row['price']) ?> ₫
                        </td>

                        <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                            <?= htmlspecialchars($row['material']) ?>
                        </td>

                        <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                            <?= htmlspecialchars($row['weight']) ?>
                        </td>

                        <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                            <?= htmlspecialchars($row['size']) ?>
                        </td>

                        <td class="!border border-gray-300 px-2 py-2 text-left text-[1.5rem]">
                            <?= nl2br(htmlspecialchars($row['note'])) ?>
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
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-xl text-gray-500">Không có sản phẩm</td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>

        </div>
    </main>

<?php
    include __DIR__ . '/layouts/footer.php';
?>