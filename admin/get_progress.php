<?php
require '../koneksi.php';

$kode = $_GET['kode'];
$query = "SELECT 
            COALESCE(SUM(pagu2), 0) as total_pagu, 
            COALESCE(GROUP_CONCAT(DISTINCT NULLIF(pic, '')), 'Belum Ada PIC') as pic_kegiatan,
            (SELECT COALESCE(SUM(jml_blokir), 0)
             FROM dipa 
             WHERE Kode_khusus LIKE CONCAT(?, '.%') 
             AND status = 'blokir'
             AND CHAR_LENGTH(Kode_khusus) = 33
            ) as total_blokir
         FROM dipa 
         WHERE Kode_khusus = ?";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ss", $kode, $kode);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

// Format response
$total_blokir = $row['total_blokir'] > 0 ? $row['total_blokir'] : 'Tidak ada nilai blokir';

echo json_encode([
    'total_pagu' => $row['total_pagu'],
    'pic' => $row['pic_kegiatan'],
    'total_blokir' => $total_blokir
]); 