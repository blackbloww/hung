<?php
    include __DIR__ . '/layouts/header.php';
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
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Họ Và Tên</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Địa chỉ</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Số Điện thoại</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold">Trạng thái</td>
                        <td class="!border border-gray-300 px-4 py-2 text-center font-bold"></td>
                    </tr>
                    <tr class="hover:bg-gray-200 bg-white">
                        <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-center">1</td>
                        <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-left">abc</td>
                        <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-left">PT</td>
                        <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-right">091623</td>
                        <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-center">
                            <select name="" id="" class="!border border-gray-300 text-[1.5rem] px-2">
                                <option value="">Chưa gọi</option>
                                <option value="">Không nghe máy</option>
                                <option value="">Đã gọi</option>
                                <option value="">Không nhu cầu</option>

                            </select>
                        </td>
                        <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-center">
                            <button class="bg-red-500 text-white text-xl px-2 hover:bg-red-700">Xóa</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </main>

<?php
    include __DIR__ . '/layouts/footer.php';
?>