<?php
    include __DIR__ . "/admin/config.php";

    $id = $_GET['id'] ?? null;

    if ($id && is_numeric($id)) {
        //lấy product
        $sql = "SELECT * FROM dtb_product WHERE id = $id AND delete_at IS NULL";
        $result = $conn->query($sql);
        $product = $result->fetch_assoc();

        // 2. Lấy comment theo product_id
        $sqlComment = "SELECT * FROM dtb_product_comment WHERE product_id = $id ORDER BY id";
        $commentResult = $conn->query($sqlComment);
        if ($sqlComment) {
            $commentResult = $conn->query($sqlComment);
            $comments = [];
            if ($commentResult) {
                while ($row = $commentResult->fetch_assoc()) {
                    $comments[] = $row;
                }
            }
        } else {
            $comments = [];
        }

        // 3. Lấy slide theo product_id
        $sqlSlide = "SELECT * FROM dtb_product_images_slide WHERE product_id = $id ORDER BY id";
        $slideResult = $conn->query($sqlSlide);
        if ($sqlSlide) {
            $slideResult = $conn->query($sqlSlide);
            $slides = [];
            if ($slideResult) {
                while ($row = $slideResult->fetch_assoc()) {
                    $slides[] = $row;
                }
            }
        } else {
            $slides = [];
        }

        // 4. Lấy content theo product_id
        $sqlContent = "SELECT * FROM dtb_product_images_content WHERE product_id = $id ORDER BY id";
        $contentResult = $conn->query($sqlContent);

        $contents = [];

        if ($contentResult) {
            while ($row = $contentResult->fetch_assoc()) {
                $contents[] = $row;
            }

            // CHỈ CHIA SAU KHI ĐÃ LẤY TOÀN BỘ DỮ LIỆU
            $total = count($contents);
            $half = ceil($total / 2);
            
            $firstHalf = array_slice($contents, 0, $half);
            $secondHalf = array_slice($contents, $half);

        } else {
            $contents = [];
            $firstHalf = [];
            $secondHalf = [];
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['product_name'] . ' - '. $product['price']?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./assets/scss/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

</head>

<body>

    <!-- header  -->
    <?php if (!empty($slides)): ?>
            <header class="header">
                <div class="sec-content__slder">
                    <!-- Slider chính -->
                    <div class="swiper main-slider">
                        <div class="swiper-wrapper">
                            <?php foreach ($slides as $slide): ?>
                                <div class="swiper-slide">
                                    <img src="/admin/uploads/slide/<?php echo($slide['images']) ?>" alt="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Nút next/prev -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    <!-- Slider thumbnail -->
                    <div class="swiper thumb-slider">
                        <div class="swiper-wrapper">
                            <?php foreach ($slides as $slide): ?>
                                <div class="swiper-slide">
                                    <img src="/admin/uploads/slide/<?php echo($slide['images']) ?>" alt="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </header>
    <?php endif; ?>

    <main class="main">
        <section class="sec-main pt-4">
            <div class="container">
                <div class="content flex gap-3">
                    <div class="item flex gap-1">
                        <p class="mon font-bold">4.9</p>
                        <div class="flex items-center gap-0">
                            <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>

                            <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>

                            <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                    <div class="item">
                        <span class="text-[#b42B23] mon font-bold">9.083</span>
                        <span class="text-2xl">lượt bán</span>
                    </div>
                    <div class="">
                        <span class="text-[#b42B23] mon font-bold">1.623</span>
                        <span class="text-2xl">đánh giá</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="sec-banner mt-8">
            <div class="container">
                <p class="noto !text-[2.8rem] font-bold text-center"><?php echo $product['product_name'] ?></p>
                <p class="philo !text-[2.8rem] font-bold text-center leading-snug !mt-2">Chất Liệu : <?php echo $product['material']?></p>
                <div class="flex items-center gap-8">
                    <img src="./assets/images/sale.webp" alt="" class="w-[16rem] h-[16rem] flash ">
                    <p class="text-[1.8rem] italic">Giá chỉ từ<br><span
                            class="text-[#cc1818] tino text-[4.5rem] leading-none font-bold zoom-small v2 block"><?php echo $product['price']?></span>
                    </p>
                </div>
                <div class="flex justify-center -mt-4">
                    <a href="#form" class="btn-submit zoom-small mon">ĐẶT HÀNG</a>
                </div>
            </div>
        </section>

        <?php if (!empty($contents)): ?>                       
            <section class="sec-introduce mt-8 pb-8">
                <?php foreach ($firstHalf as $content): ?>
                    <p class="text-[1.9rem] text-[#ffc107] italic text-center px-2 pt-6"><?php echo($content['title']) ?></p>
                    <p class="text-[1.7rem] text-white philo text-center px-2 pb-5 leading-snug !mt-3"><?php echo($content['note']) ?></p>
                    <div class="mt-2">
                        <img src="/admin/uploads/content/<?php echo($content['images']) ?>" alt="" class="">
                    </div>
                <?php endforeach; ?>

                <!-- thông tin sp  -->
                <div class="container bg-white rounded-[2rem] content p-6 !mt-14 mb-10">
                    <span
                        class="text-[#fb0101] barlow font-bold text-[2.2rem] mx-auto w-fit block !border-b-2 border-[#fb0101] leading-[1.1]">THÔNG
                        TIN SẢN PHẨM</span>
                    <div class="flex items-center gap-4 mt-6">
                        <img src="./assets/images/icon.png" alt="" class="-mt-2">
                        <p class="text-[1.8rem] leading-[1.2]"><span class="font-bold">Chất liệu:</span> <?php echo $product['material']?></p>
                    </div>
                    <div class="flex items-center gap-4 mt-2">
                        <img src="./assets/images/icon.png" alt="" class="-mt-2">
                        <p class="text-[1.8rem] leading-[1.2]"><span class="font-bold">Trọng lượng:</span> <?php echo $product['weight']?></p>
                    </div>
                    <div class="flex items-center gap-4 mt-2">
                        <img src="./assets/images/icon.png" alt="" class="-mt-2">
                        <p class="text-[1.8rem] leading-[1.2]"><span class="font-bold">Kích thước:</span> <?php echo $product['size']?>
                        </p>
                    </div>
                    <div class="flex items-start gap-4 mt-2">
                        <img src="./assets/images/icon.png" alt="" class="">
                        <p class="text-[1.8rem] leading-[1.2]"><span class="font-bold">Nơi để phù hợp:</span> <?php echo $product['note']?>...</p>
                    </div>
                    <div class="flex justify-center mt-4">
                        <a href="#form" class="btn-submit zoom-small mon !rounded-full">ĐẶT HÀNG</a>
                    </div>
                </div>

                <?php foreach ($secondHalf  as $content): ?>
                    <div class="mt-2">
                        <img src="/admin/uploads/content/<?php echo($content['images']) ?>" alt="" class="">
                    </div>
                    <p class="text-[1.9rem] text-[#ffc107] italic text-center pt-6 px-2"><?php echo($content['title']) ?></p>
                    <p class="text-[1.7rem] text-white philo text-center px-2 pb-5 leading-snug !mt-3"><?php echo($content['note']) ?></p>
                    
                <?php endforeach; ?>

            </section>
        <?php endif; ?>

        <section class="sec-comments pt-8 pb-16">
            <div class="container">
                <div class="flex items-center justify-between roboto mb-12">
                    <p class="font-bold text-[1.8rem]">9783 Bình luận</p>
                    <p class="text-[1.5rem]">Sắp xếp theo</p>
                    <div
                        class="relative w-[14rem] h-[3.7rem] !border border-[rgba(191,191,191,1.000)] bg-[#eee] content-center rounded-xl">
                        <p class="pl-7 mon text-[1.5rem] font-bold text-[#5f5f5f]">Hàng đầu
                            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="10" height="13"
                                viewBox="0 0 11 14" fill="none" class="absolute top-2 right-7 -rotate-90">
                                <path d="M0 14L11 7L0 0V14Z" fill="#5f5f5f" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="10" height="13"
                                viewBox="0 0 11 14" fill="none" class="absolute bottom-2 right-7 rotate-90">
                                <path d="M0 14L11 7L0 0V14Z" fill="#5f5f5f" />
                            </svg>
                        </p>
                    </div>

                </div>

                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="flex gap-5 mt-8">
                            <img src="/admin/uploads/comments/<?php echo $comment['avatar']; ?>" alt="" class="w-[6.5rem] h-[6.5rem] rounded-full object-cover">
                            <div class="flex flex-col gap-2 flex-1">
                                <p class="mon text-[rgba(5,34,74,1)] font-bold"><?= $comment['user_name'] ?></p>
                                <p class="text-[1.5rem] mon"><?= $comment['comment'] ?></p>
                                <img src="/admin/uploads/comments/<?php echo $comment['product_image']; ?>" alt=""
                                    class="w-[24rem] h-[18rem] object-cover rounded-[2rem]">
                                <div class="flex items-center gap-0 mt-4">
                                    <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>

                                    <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>

                                    <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <svg class="w-7 h-7 text-[#ffbc01]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                <div class="flex gap-6 mt-2 items-center">
                                    <p class="opens text-[rgba(67,102,176,1)] text-xl cursor-pointer">Thích</p>
                                    <p class="opens text-[rgba(67,102,176,1)] text-xl cursor-pointer">Bình Luận</p>
                                    <div class="flex items-center gap-1">
                                        <img src="./assets/images/ic-like.png" alt="" class="">
                                        <p class="opens text-[rgba(67,102,176,1)] text-xl"><?= $comment['is_like'] ?></p>

                                    </div>
                                    <p class="opens text-[rgba(67,102,176,1)] text-xl"><?= $comment['last_date'] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <img src="https://content.pancake.vn/1/s440x440/fwebp75/dlc/f7/e6/85/81/cf26a6c6ba7a07147fc8ffc04e12b84190f9a11cf0b826167054fd24.gif"
                    class="w-[4rem] h-[4rem] mx-auto mt-10">
                <p class="text-center italic text-[1.4rem] mon !mt-2">Ai đó đang nhập bình luận...</p>
            </div>
        </section>

        <section class="sec-form pt-16 pb-10 px-8">
            <div class="bg-gradient-to-b from-white to-[#FFF6C3] px-4 pt-20 pb-12 rounded-[1.5rem]">
                <div
                    class="relative w-[33rem] h-8.5rem rounded-[5rem] !border border-[rgba(174,174,174,1)] content-center mx-auto">
                    <div class="absolute -top-[6rem] -left-[4rem]">
                        <img src="./assets/images/sale.webp" alt="" class="w-[19rem] h-[20rem] flash">

                    </div>
                    <div class="pl-[16rem]">
                        <p class="text-[1.5rem] font-bold">GIÁ TRI ÂN<br><span
                                class="block text-[rgba(251,1,1,1)] font-bold text-[2.5rem] leading-none"><?php echo $product['price']?></span>
                        </p>
                        <p class="line-through text-[1.7rem]">1.250.000 VNĐ</p>
                    </div>
                </div>

                <div class="text-center mt-8 max-w-[21rem] ml-[13rem]">
                    <p class="text-[1.9rem] philo font-bold leading-[1.3]">Chất Liệu : <?php echo $product['material']?></p>
                </div>

                <form action="" id="form">
                    <p class="text-[rgba(251,1,1,1)] text-[2rem] font-bold text-center !mt-10 block">ĐẶT MUA NGAY HÔM
                        NAY</p>

                    <div class="container flex flex-col gap-3 !mt-7 pb-8">
                        <div class="flex gap-3">
                            <input type="text" placeholder="Họ và Tên"
                                class="!rounded-lg placeholder-gray-500 !border border-gray-300 !bg-white text-xl !px-3 !py-1 w-[19rem]">
                            <input type="number" placeholder="Số Điện Thoại"
                                class="!rounded-lg placeholder-gray-500 !border border-gray-300 !bg-white text-xl !px-3 !py-1 flex-1 w-[14rem]">
                        </div>
                        <input type="text" placeholder="Địa chỉ (ghi rõ số nhà)"
                            class="!rounded-lg placeholder-gray-500 !border border-gray-300 !bg-white text-xl !px-3 !py-1 w-full">
                    </div>

                    <button class="btn-order !mx-auto">
                        ĐẶT HÀNG
                    </button>
                </form>
            </div>

            <p class="text-center philo text-[2rem] text-white font-bold !mt-20">CAM KẾT CỦA CHÚNG TÔI</p>
            <p class="text-center font-bold text-[1.5rem] text-white philo leading-[1.2] !mt-2">Mang lại cho khách hàng
                sự hài lòng và<br> những sản phẩm chất lượng nhất</p>
            <span class="block w-[7rem] h-[1.5px] bg-black mx-auto mt-4"></span>

            <div class="flex gap-5 mt-8">
                <div class="flex flex-col gap-2 items-center flex-1">
                    <img src="./assets/images/ic.png" alt="" class="w-[4.8rem] -ml-[5px]">
                    <p class="text-item barlow">BẢO HÀNH</p>
                    <p class="barlow text-white text-[1.4rem] !-mt-2 text-center">Bảo hành trọn đời về màu sắc</p>
                </div>

                <div class="flex flex-col gap-2 items-center max-w-[15rem] w-full">
                    <img src="./assets/images/ic.png" alt="" class="w-[4.8rem] -ml-[5px]">
                    <p class="text-item barlow">VẬN CHUYỂN</p>
                    <p class="barlow text-white text-[1.4rem] !-mt-2 text-center">Lỗi vận chuyển bể vỡ 1 đổi 1 cho khách
                        hàng</p>
                </div>

                <div class="flex flex-col gap-2 items-center flex-1">
                    <img src="./assets/images/ic.png" alt="" class="w-[4.8rem] -ml-[5px]">
                    <p class="text-item barlow">NGUỒN GỐC</p>
                    <p class="barlow text-white text-[1.4rem] !-mt-2 text-center">Hàng chính hãng</p>
                </div>
            </div>
        </section>

        <div class="fixed bottom-2 left-1/2 !-translate-x-1/2 z-20">
            <a href="#form" class="btn-submit zoom-small">ĐẶT HÀNG</a>
        </div>

        <div class="flex flex-col items-end fixed bottom-4 right-1/2 !translate-x-1/2 gap-4 w-full max-w-[42rem] pr-4 ic-contact z-10">
            <a href="tel:0865188858" class="w-[5rem]">
                <img src="./assets/images/phone.jpg" alt="">
            </a>
            <a href="https://zalo.me/0865188858" class="w-[5rem]">
                <img src="./assets/images/zalo.jpg" alt="">
            </a>
        </div>
    </main>

    <footer class="bg-gradient-to-b from-[rgba(99,90,90,1)] to-[rgba(0,0,0,1)] pt-6">
        <div class="container">
            <!-- <p class="text-[rgba(255,193,7,1)] font-bold text-[1.7rem] trirong">PHƯƠNG LINH DECOR</p> -->
            <div class="flex items-center gap-3">
                <p class="text-[1.7rem] text-white font-bold trirong">CÔNG TY TNHH NAM TÍN NGỌC</p>
            </div>

            <div class="flex items-start gap-3 mt-6">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    preserveAspectRatio="none" width="20" height="20" viewBox="0 0 24 24" fill="rgba(255,255,255,1)">
                    <path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
                </svg>
                <p class="mon text-[1.4rem] text-white flex-1">Địa chỉ: Tổ 4c, khu Hương Trầm, Phường Việt trì, Tỉnh Phú Thọ, Việt nam</p>
            </div>
            
            <div class="flex items-center gap-3 mt-3">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    preserveAspectRatio="none" width="20" height="20" viewBox="0 0 24 24" fill="rgba(255,255,255,1)">
                    <path
                        d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
                </svg>
                <a href="tel:0865188858" class="mon text-[1.4rem] !text-white">Hotline: 0865188858</a>
            </div>
            <!-- <div class="flex items-center gap-3 mt-3">
                <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="20" height="20" fill="#fff"
                    viewBox="0 0 24 24" class="web">
                    <path
                        d="M16.36,14C16.44,13.34 16.5,12.68 16.5,12C16.5,11.32 16.44,10.66 16.36,10H19.74C19.9,10.64 20,11.31 20,12C20,12.69 19.9,13.36 19.74,14M14.59,19.56C15.19,18.45 15.65,17.25 15.97,16H18.92C17.96,17.65 16.43,18.93 14.59,19.56M14.34,14H9.66C9.56,13.34 9.5,12.68 9.5,12C9.5,11.32 9.56,10.65 9.66,10H14.34C14.43,10.65 14.5,11.32 14.5,12C14.5,12.68 14.43,13.34 14.34,14M12,19.96C11.17,18.76 10.5,17.43 10.09,16H13.91C13.5,17.43 12.83,18.76 12,19.96M8,8H5.08C6.03,6.34 7.57,5.06 9.4,4.44C8.8,5.55 8.35,6.75 8,8M5.08,16H8C8.35,17.25 8.8,18.45 9.4,19.56C7.57,18.93 6.03,17.65 5.08,16M4.26,14C4.1,13.36 4,12.69 4,12C4,11.31 4.1,10.64 4.26,10H7.64C7.56,10.66 7.5,11.32 7.5,12C7.5,12.68 7.56,13.34 7.64,14M12,4.03C12.83,5.23 13.5,6.57 13.91,8H10.09C10.5,6.57 11.17,5.23 12,4.03M18.92,8H15.97C15.65,6.75 15.19,5.55 14.59,4.44C16.43,5.07 17.96,6.34 18.92,8M12,2C6.47,2 2,6.5 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                </svg>
                <a href="https://phuonglinhdecor.com" target="_blank"
                    class="mon text-[1.4rem] !text-white">https://phuonglinhdecor.com</a>
            </div> -->
            <div class="flex items-center gap-4 mt-3">
                <svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="18" height="14"
                    viewBox="0 0 20 16" fill="#fff">
                    <path
                        d="M18 16H2C0.89543 16 0 15.1046 0 14V1.913C0.04661 0.842551 0.92853 -0.00100909 2 9.06007e-07H18C19.1046 9.06007e-07 20 0.895431 20 2V14C20 15.1046 19.1046 16 18 16ZM2 3.868V14H18V3.868L10 9.2L2 3.868ZM2.8 2L10 6.8L17.2 2H2.8Z"
                        fill="#fff" />
                </svg>
                <p class="mon text-[1.4rem] text-white">Email: ledinhbangoo@gmail.com</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Thumbnail slider
            const thumbSwiper = new Swiper(".thumb-slider", {
                spaceBetween: 10,
                slidesPerView: 5,
                freeMode: true,
                watchSlidesProgress: true,
                watchSlidesVisibility: true,
                slideToClickedSlide: true,
                loop: false, // thumbnail thường không nên loop
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });

            // Slider chính
            const mainSwiper = new Swiper(".main-slider", {
                spaceBetween: 10,
                loop: true, // bật vòng lặp
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                thumbs: {
                    swiper: thumbSwiper,
                },
            });
        });
    </script>
</body>

</html>