<?php
require '../koneksi.php';

$action = $_GET['action'];

if ($action === 'detail') {
    $pic = $_GET['pic'];
    $query = "SELECT kode_khusus, uraian 
             FROM dipa 
             WHERE pic = ? 
             AND CHAR_LENGTH(Kode_khusus) = 26 
             AND CHAR_LENGTH(kode_tunggal) > 0
             ORDER BY kode_khusus";
             
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $pic);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $details = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $details[] = $row;
    }
    
    echo json_encode($details);
    
} else if ($action === 'chart') {
    $kode = $_GET['kode'];
    
    $query = "SELECT 
                ?,
                SUM(CASE 
                    WHEN (aea IS NULL OR aea = '') 
                    AND (kode_angka IS NULL OR kode_angka = '')
                    THEN pagu2 
                    ELSE 0 
                END) as total_pagu,
                (SELECT SUM(CASE 
                    WHEN (aea IS NULL OR aea = '') 
                    AND (kode_angka IS NULL OR kode_angka = '')
                    THEN jml_blokir 
                    ELSE 0 
                END)
                 FROM dipa 
                 WHERE kode_khusus LIKE CONCAT(?, '%')) as total_blokir
             FROM dipa 
             WHERE kode_khusus LIKE CONCAT(?, '%')
             GROUP BY ?";
             
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $kode, $kode, $kode, $kode);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    
    echo json_encode([
        'total_pagu' => $row['total_pagu'],
        'total_blokir' => $row['total_blokir'],
        'kode_khusus' => $kode
    ]);
} 