<?php
include __DIR__ . "/config.php";

// 1. Kiểm tra id có tồn tại không
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Lỗi: Không có ID sản phẩm!");
}

// 2. Lấy và validate id
$product_id = $_GET['id'];

// Kiểm tra id có phải số không
if (!is_numeric($product_id)) {
    die("Lỗi: ID sản phẩm không hợp lệ!");
}

// 3. Chuyển thành số nguyên
$product_id = (int)$product_id;

// Kiểm tra id > 0
if ($product_id <= 0) {
    die("Lỗi: ID sản phẩm phải lớn hơn 0!");
}

// 4. Chống SQL Injection (nếu dùng string)
$product_id = mysqli_real_escape_string($conn, $product_id);

// QUERY 1: Lấy thông tin sản phẩm
$sql_product = "SELECT * FROM dtb_product WHERE id = '$product_id'";
$result_product = mysqli_query($conn, $sql_product);
$product = mysqli_fetch_assoc($result_product);

// QUERY 2: Lấy bình luận
$sql_comments = "SELECT * FROM dtb_product_comment 
                 WHERE product_id = '$product_id' ";
$result_comments = mysqli_query($conn, $sql_comments);
$comments = mysqli_fetch_all($result_comments, MYSQLI_ASSOC);


// content

$sql_contents = "SELECT * FROM dtb_product_images_content 
                 WHERE product_id = '$product_id' ";
$result_contents = mysqli_query($conn, $sql_contents);
$contents = mysqli_fetch_all($result_contents, MYSQLI_ASSOC);
// echo var_dump($contents)

// slide

$sql_slides = "SELECT * FROM dtb_product_images_slide 
                 WHERE product_id = '$product_id' ";
$result_slides = mysqli_query($conn, $sql_slides);
$slides = mysqli_fetch_all($result_slides, MYSQLI_ASSOC);
?>


<?php
include __DIR__ . '/layouts/header.php';

?>

<main class="flex">
    <?php
    include __DIR__ . '/layouts/slidebar.php';
    ?>

    <div class="max-w-[100rem] w-full mx-auto py-[5rem]">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-800 text-white rounded-t-2xl shadow-lg p-6">
            <div class="font-bold text-center text-[2rem]">NHẬP THÔNG TIN SẢN PHẨM</div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-b-2xl shadow-xl p-6 overflow-auto h-[80vh]">
            <form id="productForm" action="store_product.php" method="POST" enctype="multipart/form-data">
                <!-- Product  -->
                <div class="space-y-8">
                    <div>
                        <label for="product_name" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                            Tên sản phẩm <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="product_name" name="product_name" required value="<?php echo $product['product_name'] ?>"
                                class="bg-white w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                placeholder="Nhập tên sản phẩm">
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Giá -->
                        <div>
                            <label for="price" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Giá <span
                                    class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="price" name="price" maxlength="64" required
                                    class="w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 transition-all duration-300"
                                    placeholder="Ví dụ: 150.000đ" value="<?php echo $product['price'] ?>">
                                <i
                                    class="fas fa-dollar-sign absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Chất liệu -->
                        <div>
                            <label for="material" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                <i class="fas fa-layer-group text-amber-500 mr-2"></i>Chất liệu
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="material" name="material" maxlength="64"
                                    class="w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 transition-all duration-300"
                                    placeholder="Ví dụ: Sa Thạch - Vân Gỗ Vẽ Tay" value="<?php echo $product['material'] ?>">
                                <i
                                    class="fas fa-palette absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Trọng lượng -->
                        <div>
                            <label for="weight" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                <i class="fas fa-weight text-purple-500 mr-2"></i>Trọng lượng
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="weight" name="weight" maxlength="64"
                                    class="w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 transition-all duration-300"
                                    placeholder="Ví dụ: 3kg" value="<?php echo $product['weight'] ?>">
                                <i
                                    class="fas fa-weight-hanging absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Kích thước -->
                        <div>
                            <label for="size" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                <i class="fas fa-ruler-combined text-teal-500 mr-2"></i>Kích thước
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="size" name="size" maxlength="64"
                                    class="w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 transition-all duration-300"
                                    placeholder="Ví dụ: 38 x 23 x 12 (cm)" value="<?php echo $product['size'] ?>">
                                <i
                                    class="fas fa-expand-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="note" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                            <i class="fas fa-sticky-note text-red-500 mr-2"></i>Nơi để phù hợp
                        </label>
                        <textarea id="note" name="note" rows="5"
                            class="w-full p-4 border border-gray-300 rounded-xl focus:border-blue-200 transition-all duration-300"
                            placeholder="Ví dụ: Phòng khách, Ban Thần Tài, Quầy thu ngân..."><?php echo $product['note'] ?></textarea>

                    </div>
                </div>
                <!-- ///////////////  -->


                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 w-[80%] mx-auto h-[2px] mt-12"></div>
                <span class="font-bold block text-[2rem] text-center mt-16 italic underline">BÌNH LUẬN</span>

                <!-- Comment -->
                <div id="comment-form">
                    <?php if (empty($comments)): ?>
                        <p class="text-gray-500 text-lg">Chưa có bình luận nào</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <div class="bg-gray-100 p-8 rounded-xl space-y-8 mt-8">
                                <div>
                                    <button type="button"
                                        class="remove-form-btn px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition duration-300 flex items-center gap-2 ml-auto">
                                        Xóa
                                    </button>
                                    <label for="comment_avata[]" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                        Ảnh đại diện
                                    </label>
                                    <div class="flex items-center gap-[1rem]">
                                        <input type="file" id="comment_avata[]" name="comment_avata[]" required
                                            class="bg-white max-w-[29.5rem] w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                            placeholder="Nhập tên sản phẩm">
                                        <div class="bg-white p-4 border-[2px] border-grey rounded-[0.8rem]"><?php echo ($comment['avatar']); ?></div>
                                    </div>

                                </div>

                                <div class="flex gap-6">
                                    <div class="flex-1">
                                        <label for="comment_user_name[]" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                            Tên người dùng<span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="comment_user_name[]" name="comment_user_name[]" required
                                            class="bg-white w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                            placeholder="Nhập tên sản phẩm" value="<?php echo ($comment['user_name']); ?>">
                                    </div>
                                    <div class="flex-1">
                                        <label for="comment_is_like[]" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                            Lượt thích
                                        </label>
                                        <input type="number" id="comment_is_like[]" name="comment_is_like[]" required
                                            class="bg-white w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                            placeholder="Nhập tên sản phẩm" value="<?php echo ($comment['is_like']); ?>">
                                    </div>
                                    <div class="flex-1">
                                        <label for="comment_last_date[]" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                            Thời gian bình luận
                                        </label>
                                        <input type="text" id="comment_last_date[]" name="comment_last_date[]" required
                                            class="bg-white w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                            placeholder="Nhập tên sản phẩm" value="<?php echo ($comment['last_date']); ?>">
                                    </div>
                                </div>

                                <div>
                                    <label for="comment_note[]" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                        Bình luận
                                    </label>
                                    <textarea id="comment_note[]" name="comment_note[]" rows="5"
                                        class="w-full p-4 border border-gray-300 rounded-xl focus:border-blue-200 transition-all duration-300"
                                        placeholder="Nhập ghi chú về sản phẩm..."><?php echo ($comment['comment']); ?></textarea>
                                </div>

                                <div>
                                    <label for="comment_product_image[]" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                        Ảnh sản phẩm<span class="text-red-500">*</span>
                                    </label>

                                    <div class="flex items-center gap-[1rem]">
                                        <input type="file" id="comment_product_image[]" name="comment_product_image[]" required
                                            class="bg-white max-w-[29.5rem] w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                            placeholder="Nhập tên sản phẩm">
                                        <div class="bg-white p-4 border-[2px] border-grey rounded-[0.8rem]"><?php echo ($comment['product_image']); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- clone comment  -->
                <button id="cloneFormBtn" type="button"
                    class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition duration-300 mt-4">
                    Thêm
                </button>
                <!-- ///////////////  -->


                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 w-[80%] mx-auto h-[2px] mt-12"></div>
                <span class="font-bold block text-[2rem] text-center mt-16 italic underline">NỘI DUNG</span>

                <!-- Content-->
                <div id="content-form">
                    <?php if (empty($contents)): ?>
                        <p class="text-gray-500 text-lg">Chưa có nội dung</p>
                    <?php else: ?>
                        <?php foreach ($contents as $content): ?>
                            <div class="bg-gray-100 p-8 rounded-xl space-y-8 mt-8">
                                <div>
                                    <button type="button"
                                        class="remove-content-form px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition duration-300 flex items-center gap-2 ml-auto">
                                        Xóa
                                    </button>
                                    <label for="content_image" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                        Hình ảnh
                                    </label>

                                    <div class="flex items-center gap-[1rem]">
                                        <input type="file" id="content_image" name="content_image[]" required
                                            class="bg-white max-w-[29.5rem] w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                            placeholder="Nhập tên sản phẩm">
                                        <div class="bg-white p-4 border-[2px] border-grey rounded-[0.8rem]"><?php echo ($content['images']); ?></div>
                                    </div>
                                </div>

                                <div>
                                    <label for="content_title" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                        Tiêu đề
                                    </label>
                                    <input type="text" id="content_title" name="content_title[]" required
                                        class="bg-white w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                        placeholder="Nhập tên tiêu đề" value="<?php echo ($content['title']); ?>">
                                </div>

                                <div>
                                    <label for="content_body" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                        Nội dung
                                    </label>
                                    <textarea id="content_body" name="content_body[]" rows="5"
                                        class="w-full p-4 border border-gray-300 rounded-xl focus:border-blue-200 transition-all duration-300"
                                        placeholder="Nhập nội dung"><?php echo ($content['note']); ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- clone content  -->
                <button id="cloneContentForm" type="button"
                    class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition duration-300 mt-4">
                    Thêm
                </button>
                <!-- ///////////////  -->

                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 w-[80%] mx-auto h-[2px] mt-12"></div>
                <span class="font-bold block text-[2rem] text-center mt-16 italic underline">SLIDER</span>

                <!-- Slide-->
                <div id="slide-form">
                    <?php if (empty($slides)): ?>
                        <p class="text-gray-500 text-lg">Chưa có ảnh</p>
                    <?php else: ?>
                        <?php foreach ($slides as $slide): ?>
                            <div class="bg-gray-100 p-8 rounded-xl space-y-8 mt-8">
                                <div>
                                    <button type="button"
                                        class="remove-slide-form px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg transition duration-300 flex items-center gap-2 ml-auto">
                                        Xóa
                                    </button>
                                    <label for="slide_image" class="block text-gray-700 font-semibold mb-1 text-[1.4rem]">
                                        Hình ảnh
                                    </label>

                                    <div class="flex items-center gap-[1rem]">
                                        <input type="file" id="slide_image" name="slide_image[]" required
                                            class="bg-white max-w-[29.5rem] w-full px-4 py-3 text-[1.4rem] border border-gray-300 rounded-lg focus:ring-blue-300 focus:border-blue-500 transition-all duration-300"
                                            placeholder="Nhập tên sản phẩm">
                                        <div class="bg-white p-4 border-[2px] border-grey rounded-[0.8rem]"><?php echo ($slide['images']); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <!-- clone content  -->
                <button id="cloneSlideForm" type="button"
                    class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition duration-300 mt-4">
                    Thêm
                </button>
                <!-- ///////////////  -->

                <!-- submit -->
                <div class="flex flex-col sm:flex-row justify-end pb-8 pr-8 border-gray-200 space-y-4 sm:mt-4 mt-12">
                    <button type="submit"
                        class="px-10 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-[1.6rem] font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 shadow-md hover:shadow-lg ">
                        Lưu sản phẩm
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function setupCloneSystem(baseId, cloneBtnId, removeClass) {
                const cloneBtn = document.getElementById(cloneBtnId);
                const originalForm = document.getElementById(baseId);
                const container = originalForm.parentNode;

                originalForm.classList.add(baseId + "-item");

                function attachRemoveEvent(form) {
                    const removeBtn = form.querySelector("." + removeClass);
                    removeBtn.addEventListener("click", function() {
                        const totalForms = container.querySelectorAll("." + baseId + "-item").length;

                        if (totalForms > 1) {
                            form.remove();
                        }
                    });
                }

                // Cho form đầu tiên
                attachRemoveEvent(originalForm);

                // Khi nhấn nút thêm
                cloneBtn.addEventListener("click", function() {
                    const clone = originalForm.cloneNode(true);
                    clone.classList.add(baseId + "-item");
                    clone.id = baseId + "-" + Date.now();

                    // Reset tất cả input + textarea
                    const inputs = clone.querySelectorAll("input, textarea");
                    inputs.forEach(el => (el.value = ""));

                    attachRemoveEvent(clone);

                    // Chèn vào trước nút thêm
                    container.insertBefore(clone, cloneBtn);
                });
            }

            // Gọi cho form bình luận
            setupCloneSystem("comment-form", "cloneFormBtn", "remove-form-btn");

            // Gọi cho form nội dung
            setupCloneSystem("content-form", "cloneContentForm", "remove-content-form");

            // Gọi cho form slide
            setupCloneSystem("slide-form", "cloneSlideForm", "remove-slide-form");

        });
    </script>
</main>

<?php
include __DIR__ . '/layouts/footer.php';
?>