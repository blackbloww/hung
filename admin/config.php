<?php
$DB_HOST = "localhost";
$DB_USER = "xs191273_baobao";
$DB_PASS = "dH(|0(7o3LO9";
$DB_NAME = "xs191273_baobao";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// if ($conn) {
//     echo "Kết nối OK!";
// }

$conn->set_charset("utf8mb4");
?>
