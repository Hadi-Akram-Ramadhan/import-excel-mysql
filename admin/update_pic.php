<?php
session_start();
require '../koneksi.php';

// Check if user is logged in and has correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] != '1') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (empty($_POST['kode_khusus']) || empty($_POST['pic'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Kode khusus dan PIC tidak boleh kosong!'
        ]);
        exit;
    }

    $kode_khusus = trim($_POST['kode_khusus']);
    $pic = trim($_POST['pic']);
    
    try {
        // Update semua data yang kode khususnya dimulai dengan pattern yang sama
        $query = "UPDATE dipa SET pic = ? WHERE Kode_khusus LIKE CONCAT(?, '%')";
        $stmt = mysqli_prepare($koneksi, $query);
        
        if (!$stmt) {
            throw new Exception('Prepare statement failed');
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $pic, $kode_khusus);
        
        if (mysqli_stmt_execute($stmt)) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            echo json_encode([
                'success' => true,
                'message' => "PIC berhasil diupdate! ($affected_rows data terupdate)"
            ]);
        } else {
            throw new Exception(mysqli_error($koneksi));
        }
        
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal update PIC: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>