<?php
session_start();
require '../nav/navAdmin.php';
require '../koneksi.php';

$query = "SELECT Kode_khusus, uraian, pic FROM dipa WHERE CHAR_LENGTH(Kode_khusus) = 26 AND CHAR_LENGTH(kode_tunggal) > 0";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
    /* Base font */
    body,
    html {
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        color: #2d3436;
        font-size: 14px;
        /* Base font size */
    }

    .dashboard-container {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .table-section {
        flex: 0.6;
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .progress-section {
        flex: 0.4;
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .table th {
        width: auto;
        /* Override previous fixed width */
    }

    .form-title {
        text-align: center;
        padding: 10px;
        font-size: 18px;
        /* Ukuran font judul */
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    /* Nambahin style buat table biar lebih compact */
    .table {
        font-size: 13px;
        /* Ukuran font table */
    }

    .table td,
    .table th {
        padding: 0.5rem;
    }

    #progressInfo {
        font-size: 14px;
        /* Ukuran font progress info */
    }

    /* Style buat hint */
    .click-hint {
        color: #6c757d;
        font-size: 12px;
        text-align: center;
        margin-bottom: 10px;
        font-style: italic;
    }

    /* Hover effect buat table row */
    .table tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease;
    }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="table-section">
            <h2 class="form-title">Data DIPA</h2>
            <p class="click-hint">👆 Klik baris untuk melihat detail progress</p>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Kode Khusus</th>
                        <th>Uraian</th>
                        <th>PIC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr onclick="getProgress('<?php echo $row['Kode_khusus']; ?>')" style="cursor: pointer">
                        <td><?php echo $row['Kode_khusus']; ?></td>
                        <td><?php echo $row['uraian']; ?></td>
                        <td><?php echo $row['pic']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="progress-section">
            <h2 class="form-title">Progress</h2>
            <div id="progressInfo">
                <h4 style="font-size: 14px;">Total Pagu: <span id="totalPagu">-</span></h4>
                <h4 style="font-size: 14px;">PIC: <span id="picInfo">-</span></h4>
                <h4 style="font-size: 14px;">Jumlah Blokir: <span id="blokir">-</span></h4>
            </div>
        </div>
    </div>

    <script>
    function getProgress(kodeKhusus) {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        fetch(`get_progress.php?kode=${kodeKhusus}`)
            .then(response => response.json())
            .then(data => {
                // Format IDR
                const formatIDR = (number) => {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(number);
                };

                document.getElementById('totalPagu').textContent = formatIDR(data.total_pagu);
                document.getElementById('picInfo').textContent = data.pic;
                document.getElementById('blokir').textContent =
                    data.total_blokir === 'Tidak ada nilai blokir' ?
                    data.total_blokir :
                    formatIDR(data.total_blokir);
            });
    }
    </script>
</body>

</html>