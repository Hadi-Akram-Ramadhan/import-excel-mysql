<?php
// Debug info
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log request
file_put_contents('debug.log', print_r($_POST, true) . "\n" . print_r($_FILES, true), FILE_APPEND);

if (!isset($_FILES['filexls']) || !isset($_POST['submit-dipa'])) {
    $response = [
        'success' => false,
        'message' => 'No file uploaded or missing submit parameter'
    ];
    echo json_encode($response);
    exit;
}

require '..\vendor\autoload.php';

require '..\koneksi.php';

if (isset($_POST['submit-dipa'])) {
    // flush output buffer
    ob_flush();
    flush();
    
    $err = "";
    $success = "";

    $query = "SHOW TABLES LIKE 'Dipa'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 0) {
            $createTableQuery = "CREATE TABLE IF NOT EXISTS Dipa (
            Kode_khusus VARCHAR(255),
            aea Varchar(30),
            kode_angka Varchar(30),
            kode_tunggal Varchar(30),
            nomor INT AUTO_INCREMENT PRIMARY KEY,
            baris_kode VARCHAR(255),
            akun Varchar(30),
            pic VARCHAR(30),
            uraian VARCHAR(255),
            rincian VARCHAR(255),
            kegiatan VARCHAR(255),
            OK VARCHAR(255),
            pagu1 BIGINT,
            pagu2 BIGINT,
            kode VARCHAR (30),
            nama_tabel VARCHAR(255),
            berdasarkan VARCHAR(255),
            jenis VARCHAR(10),
            status VARCHAR(20),
            jml_blokir BIGINT
        );
        ";
        if (!mysqli_query($koneksi, $createTableQuery)) {
            $errorcuy = true;
            echo "Error creating table: " . mysqli_error($koneksi) . "<br>";
        } else {
            echo "Table created successfully<br>";
            
        }

    } else{
        $checkQuery = "SELECT * FROM Dipa LIMIT 1";
        $checkResult = mysqli_query($koneksi, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Kosongkan tabel Dipa
            $truncateQuery = "TRUNCATE TABLE Dipa";
            if (!mysqli_query($koneksi, $truncateQuery)) {
                die("Error emptying table: " . mysqli_error($koneksi));
            }
        };
    };

    $file_name = 'upload_' . date('Y-m-d_H-i-s'); // Format: upload_2024-03-21_15-30-45
    $file_data = $_FILES['filexls']['tmp_name'];
    

    if (empty($file_name)) {
        $err .= "<p>Silahkan masukkan file</p>";
    } else {
        $ekstensi = pathinfo($_FILES['filexls']['name'], PATHINFO_EXTENSION);
        $ekstensi_allowed = array("xls", "xlsx");

        if (!in_array($ekstensi, $ekstensi_allowed)) {
            $err .= "<li>Silahkan masukkan data yang berekstensi xls atau xlsx. File yang Anda masukkan <b>$file_name</b> memiliki ekstensi <b>$ekstensi</b></li>";
        }
    }

    $errorcuy = false;
    if (empty($err)) {
    
        // Jika $errorcuy == true, hapus tabel yang baru saja dibuat
        if ($errorcuy == true) {
            $hapustableQuery = "DELETE FROM Dipa
            WHERE nama_tabel = `$file_name`;
            ";
            if (mysqli_query($koneksi, $hapustableQuery)) {
                echo "Tabel $file_name telah dihapus karena terjadi kesalahan<br>";
            } else {
                die("Error dropping table: " . mysqli_error($koneksi));
            }
        }

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_data);
        $spreadsheet = $reader->load($file_data);
        $worksheet = $spreadsheet->getActiveSheet();
        $waktu = date('Y/m/d');
        $kd_bid = "";


        $stmt = $koneksi->prepare("INSERT INTO dipa (Kode_khusus, aea, kode_angka, kode_tunggal, nomor, baris_kode, akun, pic, uraian, rincian, kegiatan, pagu1, pagu2, kode, nama_tabel, jenis, status, jml_blokir) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssissssiissssssi", $kode_khusus, $Kode_Lengkap, $Kode_Angka, $Kode_Huruf_Tunggal, $nomor, $kode_asli, $akun, $pic, $uraian, $rincian, $kegiatan, $pagu1, $pagu2, $kode, $file_name, $jenis, $status, $jml_blokir);

        

        $total_data = 0;
        $max_rows = 1400; // Set maximum rows to 1400
        $batch_size = 100;
        $batch = [];
        $start_collecting = false;
        $column_offsets = [];

        $nomor = 1; // Initialize nomor

        // Tambah fungsi helper baru
        function getCellValue($cell) {
            $value = $cell->getValue();
            return $value !== null ? trim((string)$value) : '';
        }

        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(True);

            foreach ($cellIterator as $cell) {
                $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($cell->getColumn());
                $value = getCellValue($cell);

                // Cek pola yang lebih umum
                if (preg_match('/^\d{3}\.\d{2}\.(?:EF|WA)$/', $value)) {
                    $column_offsets['a'] = $columnIndex;
                    $start_collecting = true;
                    break 2;
                }
            }
        }

        if (!$start_collecting) {
            die("File Excel tidak sesuai format. Pastikan file berisi data RKAKL dengan format yang benar.");
        }

        $column_offsets['baris_kode'] = $column_offsets['a'];
        $column_offsets['uraian'] = $column_offsets['a'] + 1;
        $column_offsets['rincian'] = $column_offsets['a'] + 3;
        $column_offsets['kegiatan'] = $column_offsets['a'] + 4;
        $column_offsets['pagu1'] = $column_offsets['a'] + 5;
        $column_offsets['pagu2'] = $column_offsets['a'] + 6;
        $column_offsets['jenis'] = $column_offsets['a'] + 7;
        $column_offsets['status'] = $column_offsets['a'] + 7;
        
        
       
        

        // Cek apakah kunci array ada
       

        $komponen_utama = $komponen_penunjang = $blokir = $baris_kode = $uraian = $rincian = $kegiatan = $pagu1 = $pagu2 = $kode = "";
        $baris_kode_0 = $uraian_0 = $rincian_0 = $kegiatan_0 = $kode_0 ="-";

        $kode_kegiatan_kro = "";
        $pic = "";


        // 
        $last_kode_kegiatan_kro = "";
        $last_kode_lengkap = "";
        $kode_kegiatan_ef = "";
        $last_kode_kegiatan_ef = "";

        // 

        // kode inti
        $Kode_Lengkap = "";
    	$Kode_Angka = "";
    	$Kode_Huruf_Tunggal ="";
        // 

        

        // Tambahkan fungsi helper untuk handle null values
        function safeString($value) {
            return $value ?? '';  // Return empty string if null
        }

        foreach ($worksheet->getRowIterator() as $row) {
            if ($total_data >= $max_rows) {
                break; // Stop if we've reached 1400 rows
            }

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $sortir_kegiatan = "";

            foreach ($cellIterator as $cell) {
                $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($cell->getColumn());
                $value = getCellValue($cell);

                switch ($columnIndex) {
                    case $column_offsets['baris_kode']:
                        $value = getCellValue($cell);
                        if (!empty($value)) {
                            $result = preg_replace('/[^A-Z]/i', '', $value);
                            // Inisialisasi variabel default
                            $kode_kegiatan_kro = '';
                            $last_kode_kegiatan_kro = '';
                            $akun = '';
                            $kode_asli = $value;
                            $kode_kegiatan_ef = "";
                            
                            
                            // Logika untuk cek kode EF (EF)
                            if (!empty($result)) {
                                // Memeriksa apakah result adalah 'EF' atau 'WA'
                                $kode_ef_wa = ($result == 'EF' || $result == 'WA') ? $result : null;
                            
                                if ($kode_ef_wa) {
                                    $cek_ef = $koneksi->prepare("SELECT distinct ef FROM realisasi WHERE ef = ?");
                                    $cek_ef->bind_param('s', $kode_ef_wa);
                                
                                    if ($cek_ef->execute()) {
                                        $hasil_ef = $cek_ef->get_result();
                                        if ($hasil_ef->num_rows > 0) {
                                            $hasil1_ef = $hasil_ef->fetch_assoc();
                                            $kode_kegiatan_ef = $hasil1_ef['ef'];
                                        } else {
                                            $kode_kegiatan_ef = "";
                                        }
                                        $hasil_ef->free();
                                    }
                                    $cek_ef->close();
                                } else {
                                
                                    $kode_kegiatan_ef = "";
                                }
                            }
                            
                        
                            // Logika untuk cek akun
                            if (!empty($value)) {
                                $cek_akun = $koneksi->prepare("SELECT distinct akun FROM realisasi WHERE akun = ?");
                                $cek_akun->bind_param('s', $value);
                                if ($cek_akun->execute()) {
                                    $hasil_akun = $cek_akun->get_result();
                                    if ($hasil_akun->num_rows > 0) {
                                        $hasil1_akun = $hasil_akun->fetch_assoc();
                                        $akun = $hasil1_akun['akun'];
                                    }
                                    $hasil_akun->free();
                                }
                                $cek_akun->close();
                            }
                        
                            // Logika untuk cek KRO berdasarkan huruf
                            if (!empty($result)) {
                                $cek_ro = $koneksi->prepare("SELECT kro_alias FROM kro WHERE kro_alias = ?");
                                $cek_ro->bind_param('s', $result);
                                if ($cek_ro->execute()) {
                                    $result1 = $cek_ro->get_result();
                                    if ($result1->num_rows > 0) {
                                        $kegiatan = $result1->fetch_assoc();
                                        $kode_kegiatan_kro = $kegiatan['kro_alias'];
                                    }
                                    $result1->free();
                                }
                                $cek_ro->close();
                            }
                        
                            // Menyimpan nilai sesuai dengan panjang karakter dan memperbarui jika valid
                            static $last_kode_lengkap = null;  // Menyimpan kode lengkap terakhir
                            static $last_kode_angka = null;    // Menyimpan angka terakhir
                            static $last_huruf_tunggal = null; // Menyimpan huruf tunggal terakhir
                            static $last_kode_kegiatan_ef = null; // Menyimpan huruf tunggal terakhir

                            if(!empty($kode_kegiatan_ef)){
                                $last_kode_kegiatan_ef = $kode_kegiatan_ef;
                            }
                        
                            // Menyimpan nilai berdasarkan panjang karakter
                            if (strlen(safeString($value)) > 8) {
                                $Kode_Lengkap = $value;
                                $last_kode_lengkap = $value;  // Update jika valid
                            } else {
                                $Kode_Lengkap = null;
                            }
                        
                            if (strlen(safeString($value)) === 3) {
                                $Kode_Angka = $value;
                                $last_kode_angka = $value;  // Update jika valid
                            } else {
                                $Kode_Angka = null;
                            }
                        
                            if (strlen(safeString($value)) === 1) {
                                $Kode_Huruf_Tunggal = $value;
                                $last_huruf_tunggal = $value;  // Update jika valid
                            } else {
                                $Kode_Huruf_Tunggal = null;
                            }
                        
                            // Membentuk kode khusus berdasarkan urutan prioritas
                            if (!empty($last_kode_kegiatan_ef)) {
                                $kode_khusus = "$last_kode_kegiatan_ef";
                            } else {
                                $kode_khusus = "";  // Jika tidak ada kode EF, kosongkan
                            }
                        
                            // Menambahkan bagian Kode Lengkap (3720.AEA.001)
                            if (!empty($last_kode_lengkap)) {
                                $kode_khusus .= "." . $last_kode_lengkap;
                            }
                        
                            // Menambahkan bagian Angka (051)
                            if (!empty($last_kode_angka)) {
                                $kode_khusus .= "." . $last_kode_angka . "." . $last_kode_angka;
                            }                        
                        
                            // Menambahkan bagian Huruf Tunggal (A)
                            if (!empty($last_huruf_tunggal)) {
                                $kode_khusus .= "." . "0$last_huruf_tunggal";
                            }
                        
                            // Jika akun ditemukan, tambahkan ke bagian akhir
                            if (!empty($akun)) {
                                $kode_khusus .= "." . $akun;
                            }
                        
                            // Hasil kode khusus
                            break;
                        }
                        
                    case $column_offsets['uraian']:
                        $value = getCellValue($cell);
                        $jenis = "-"; // Default value
                        
                        if (!empty($value)) {
                            // Debug: cek value yang masuk
                            file_put_contents('debug.log', "Checking value: " . $value . "\n", FILE_APPEND);
                            
                            if (strpos($value, '(') !== false || strpos($value, ')') !== false) {
                                $jenis = "main";
                                // Debug
                                file_put_contents('debug.log', "Found main: " . $value . "\n", FILE_APPEND);
                            }
                            elseif (strpos($value, '>') !== false) {
                                $jenis = "head";
                                $nextColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
                                $nextCell = $worksheet->getCell($nextColumn . $row->getRowIndex());
                                $value = getCellValue($nextCell);
                                // Debug
                                file_put_contents('debug.log', "Found head: " . $value . "\n", FILE_APPEND);
                            }
                            elseif (strpos($value, '-') !== false) {
                                $jenis = "isi";
                                $nextColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
                                $nextCell = $worksheet->getCell($nextColumn . $row->getRowIndex());
                                $value = getCellValue($nextCell);
                                // Debug
                                file_put_contents('debug.log', "Found isi: " . $value . "\n", FILE_APPEND);
                            }
                        }
                        
                        $uraian = !empty($value) ? $value : $uraian_0;
                        break;

                    case $column_offsets['rincian']:
                        $value = getCellValue($cell);
                        $rincian = $value !== null && $value !== '' ? $value : $rincian_0;
                        break;

                            case $column_offsets['status']:
                                $value = getCellValue($cell);
                                $status = (strpos($value, '*') !== false) ? 'blokir' : '';
                                break;

                    default:
                        if (isset($column_offsets['kegiatan']) && $columnIndex == $column_offsets['kegiatan']) {
                            $value = getCellValue($cell);
                            $sortir_kegiatan = $value === null ? "Tidak terdeteksi" : $value;
                            if ($sortir_kegiatan != null) {
                                $kegiatan = $value;
                            }
                        } elseif (isset($column_offsets['pagu1']) && $columnIndex == $column_offsets['pagu1']) {
                            $value = getCellValue($cell);
                            $pagu1 = $value;
                        } elseif (isset($column_offsets['pagu2']) && $columnIndex == $column_offsets['pagu2']) {
                            $value = getCellValue($cell);
                            
                            // Cek kolom 11 (pagu2)
                            $kolom11 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 2); // +2 karena mau ke kolom 11
                            $cell11 = $worksheet->getCell($kolom11 . $row->getRowIndex());
                            $value11 = getCellValue($cell11);
                            
                            // Bersihin value dari format currency/string
                            $clean_value11 = preg_replace('/[^0-9]/', '', $value11);
                            
                            // Cek if value di kolom 11 > 100000
                            if(is_numeric($clean_value11) && $clean_value11 > 100000) {
                                $pagu2 = $clean_value11;
                            } else {
                                $pagu2 = $value; // Pake value original kalo ga memenuhi kondisi
                            }
                        } elseif (isset($column_offsets['kode']) && $columnIndex == $column_offsets['kode']) {
                            $value = getCellValue($cell);
                            $kode = $value;
                        }
                        break;
                };
            };

            if ($start_collecting) {
                // Hitung jml_blokir berdasarkan status
                $jml_blokir = ($status === 'blokir') ? (int)($pagu2 / 2) : 0;

                $batch[] = [
                    'kode_khusus' => $kode_khusus,
                    'kode_lengkap' => $Kode_Lengkap,
                    'Kode_angka' => $Kode_Angka,
                    'Kode_huruf_tunggal' => $Kode_Huruf_Tunggal,
                    'nomor' => $nomor,
                    'baris_kode' => $kode_asli,
                    'akun' => $akun,
                    'pic' => $pic,
                    'uraian' => $uraian,
                    'rincian' => $rincian,
                    'kegiatan' => $kegiatan,
                    'pagu1' => $pagu1,
                    'pagu2' => $pagu2,
                    'kode' => $kode,
                    'nama_tabel' => $file_name,
                    'jenis' => $jenis,
                    'status' => $status,
                    'jml_blokir' => $jml_blokir
                ];

                $nomor++; // Increment nomor for each row

                if (count($batch) >= $batch_size) {
                    foreach ($batch as $data) {
                        $stmt->bind_param(
                            "ssssissssiissssssi",
                            $data['kode_khusus'],
                            $data['kode_lengkap'],
                            $data['Kode_angka'],
                            $data['Kode_huruf_tunggal'],
                            $data['nomor'],
                            $data['baris_kode'],
                            $data['akun'],
                            $data['pic'],
                            $data['uraian'],
                            $data['rincian'],
                            $data['kegiatan'],
                            $data['pagu1'],
                            $data['pagu2'],
                            $data['kode'],
                            $data['nama_tabel'],
                            $data['jenis'],
                            $data['status'],
                            $data['jml_blokir']
                        );

                        if (!$stmt->execute()) {
                            $response = [
                                'success' => false,
                                'message' => "Error executing batch insert: " . $stmt->error
                            ];
                            echo json_encode($response);
                            exit;
                        }
                    }
                    $batch = [];
                    $total_data += $batch_size;
                }
            }
        }

        // Insert remaining data in batch
        foreach ($batch as $data) {
            $stmt->bind_param(
                "ssssissssiissssssi",
                $data['kode_khusus'],
                $data['kode_lengkap'],
                $data['Kode_angka'],
                $data['Kode_huruf_tunggal'],
                $data['nomor'],
                $data['baris_kode'],
                $data['akun'],
                $data['pic'],
                $data['uraian'],
                $data['rincian'],
                $data['kegiatan'],
                $data['pagu1'],
                $data['pagu2'],
                $data['kode'],
                $data['nama_tabel'],
                $data['jenis'],
                $data['status'],
                $data['jml_blokir']
            );

            if (!$stmt->execute()) {
                $response = [
                    'success' => false,
                    'message' => "Error executing batch insert: " . $stmt->error
                ];
                echo json_encode($response);
                exit;
            }
            
        }
        $total_data += count($batch);

        $response = [
            'success' => true,
            'message' => 'Data berhasil diimpor: ' . $total_data . ' baris'
        ];
        echo json_encode($response);
        exit;
    }

    if (!empty($err)) {
        $response = [
            'success' => false,
            'message' => $err
        ];
        echo json_encode($response);
        exit;
    }

    if (isset($stmt)) {
        $stmt->close();
    }

    if (isset($koneksi)) {
        mysqli_close($koneksi);
    }
}
?>