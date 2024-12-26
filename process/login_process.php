<?php
session_start();
include '../koneksi.php';
print_r($_SESSION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $tahun = $_POST['tahun'];

    $query = "SELECT * FROM akun WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['tahun'] = $tahun;
        $_SESSION['bidang'] = $row['bidang'];
        
        if ($row['role'] == 2) {
            header("Location: ../rkakl/index.php");
        } else if ($row['role'] == 1) {
            header("Location: ../admin/dashboard.php");
        }
    } else {
        header("Location: ../index.php?error=1");
    }
}
?>