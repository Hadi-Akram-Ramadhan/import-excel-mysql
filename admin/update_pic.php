<?php
require '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_khusus = $_POST['kode_khusus'];
    $pic = $_POST['pic'];
    
    // Update semua data yang kode khususnya dimulai dengan pattern yang sama
    $query = "UPDATE dipa SET pic = ? WHERE Kode_khusus LIKE CONCAT(?, '%')";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $pic, $kode_khusus);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'PIC berhasil diupdate!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal update PIC: ' . mysqli_error($koneksi)
        ]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>