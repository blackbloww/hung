<?php
    include __DIR__ . "/config.php";

    $id = $_GET['id'] ?? 1;

    if ($id && is_numeric($id)) {
        $sql = "SELECT * FROM dtb_customer WHERE product_id = $id ORDER BY id";
        $result = $conn->query($sql);
        if ($sql) {
            $result = $conn->query($sql);
            $customers = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $customers[] = $row;
                }
            }
        } else {
            $customers = [];
        }
    }

    $statusMap = [
        1 => "Chưa gọi",
        2 => "Không nghe máy",
        3 => "Đã gọi",
        4 => "Không nhu cầu"
    ];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer</title>
        <link rel="stylesheet" href="./../style.css">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body>
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
                        <?php if (!empty($customers)): ?>
                            <?php $stt = 1; ?>
                            <?php foreach ($customers as $item): ?>
                                <tr class="hover:bg-gray-200 bg-white">
                                    <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-right"><?= $stt++ ?></td>
                                    <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-center"><?= $item['customer_name'] ?></td>
                                    <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-left"><?= $item['tel'] ?></td>
                                    <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-left"><?= $item['address'] ?></td>
                                    <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-center">
                                        <select 
                                            class="status-select !border border-gray-300 text-[1.5rem] px-2" 
                                            data-id="<?= $item['id'] ?>">
                                            <?php foreach ($statusMap as $key => $label): ?>
                                                <option value="<?= $key ?>" <?= ($item['status'] == $key) ? 'selected' : '' ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="!border border-gray-300 px-2 py-2 align-center text-[1.5rem] text-center">
                                        <button 
                                            class="btn-delete bg-red-500 text-white text-xl px-2 hover:bg-red-700" 
                                            data-id="<?= $item['id'] ?>">
                                            Xóa
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </main>

        <!-- status  -->
        <script>
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function() {
                    const customerId = this.dataset.id;
                    const status = this.value;

                    fetch('/admin/update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: customerId, status: status })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            alert('Cập nhật trạng thái thành công!');
                        } else {
                            alert('Cập nhật thất bại: ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Lỗi server!');
                    });
                });
            });
        </script>

        <!-- xóa  -->
        <script>
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    if(!confirm('Bạn có chắc muốn xóa khách hàng này?')) return;

                    const customerId = this.dataset.id;
                    const row = this.closest('tr'); // dòng table

                    fetch('/admin/delete_customer.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: customerId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success){
                            // ẩn dòng đã xóa
                            row.remove();
                            alert('Xóa khách hàng thành công!');
                        } else {
                            alert('Xóa thất bại: ' + data.message);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Lỗi server!');
                    });
                });
            });
        </script>


    <?php
        include __DIR__ . '/layouts/footer.php';
    ?>