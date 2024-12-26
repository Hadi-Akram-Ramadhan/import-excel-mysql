<?php
// Include koneksi database
include '..\koneksi.php';

// Mengatur header agar hanya mengirimkan JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // Membersihkan output buffer
    if (ob_get_length()) {
        ob_clean();
    }

    // Query untuk mengambil data unik dari kolom 'berdasarkan'
    $query = "SELECT DISTINCT berdasarkan FROM dipa WHERE berdasarkan IS NOT NULL";
    $result = $conn->query($query);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['berdasarkan'];
        }
    }

    // Mengembalikan data dalam format JSON
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (Exception $e) {
    // Menangani error jika terjadi
    if (ob_get_length()) {
        ob_clean();
    }
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    // Menutup koneksi
    $conn->close();
}
?>
