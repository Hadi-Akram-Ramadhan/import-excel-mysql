<?php
session_start();
require '../nav/navAdmin.php';
require '../koneksi.php';

// Tambah query buat ambil total
$queryTotal = "SELECT 
    COALESCE(SUM(pagu2), 0) as total_pagu_all
FROM dipa 
WHERE kode_tunggal IS NOT NULL AND kode_tunggal != ''";
$resultTotal = mysqli_query($koneksi, $queryTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);

// Query terpisah untuk total blokir
$queryBlokir = "SELECT COALESCE(SUM(jml_blokir), 0) as total_blokir_all FROM dipa";
$resultBlokir = mysqli_query($koneksi, $queryBlokir);
$rowBlokir = mysqli_fetch_assoc($resultBlokir);

// Query original tetep ada
$query = "SELECT DISTINCT pic FROM dipa WHERE pic IS NOT NULL AND pic != '' ORDER BY pic";
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
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
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

    /* Style buat summary box */
    .summary-container {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .summary-box {
        flex: 1;
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        text-align: center;
    }

    .summary-box h3 {
        font-size: 16px;
        margin-bottom: 10px;
        color: #2d3436;
    }

    .summary-box p {
        font-size: 20px;
        font-weight: 600;
        color: #0984e3;
        margin: 0;
    }

    /* Style buat chart container */
    .chart-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        height: 400px;
        margin: 0 auto;
    }

    /* Style buat detail table */
    .detail-table {
        width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }

    .detail-content {
        padding: 10px;
    }

    .active-row {
        background-color: #e9ecef !important;
    }
    </style>
</head>

<body>
    <div class="summary-container">
        <div class="summary-box">
            <h3>Total Pagu</h3>
            <p><?php echo number_format($rowTotal['total_pagu_all'], 0, ',', '.'); ?></p>
        </div>
        <div class="summary-box">
            <h3>Total Blokir</h3>
            <p><?php echo number_format($rowBlokir['total_blokir_all'], 0, ',', '.'); ?></p>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="table-section">
            <h2 class="form-title">Data DIPA</h2>
            <p class="click-hint">👆 Klik PIC untuk melihat detail</p>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>PIC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr onclick="getDetail('<?php echo $row['pic']; ?>', this)" style="cursor: pointer">
                        <td><?php echo $row['pic']; ?></td>
                    </tr>
                    <!-- Tambah row buat detail, awalnya hidden -->
                    <tr class="detail-row" style="display: none;">
                        <td colspan="1">
                            <div class="detail-content"></div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="progress-section">
            <h2 class="form-title">Progress</h2>
            <div id="progressInfo">
                <h4 style="font-size: 14px;">PIC: <span id="picInfo">-</span></h4>
                <div class="chart-container">
                    <canvas id="budgetChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    let myChart = null; // Variable buat simpen instance chart
    let activeRow = null;
    let picTotalChart = null;

    function getDetail(pic, element) {
        // Reset active state
        if (activeRow) {
            activeRow.classList.remove('active-row');
        }

        // Toggle active state
        element.classList.add('active-row');
        activeRow = element;

        // Hide semua detail row dulu
        document.querySelectorAll('.detail-row').forEach(row => {
            row.style.display = 'none';
        });

        // Show detail row yang dipilih
        let detailRow = element.nextElementSibling;
        detailRow.style.display = 'table-row';

        // Fetch detail data
        fetch(`get_progress.php?action=detail&pic=${pic}`)
            .then(response => response.json())
            .then(data => {
                let detailContent = `
                    <table class="table table-bordered table-hover detail-table">
                        <thead>
                            <tr>
                                <th>Kode Khusus</th>
                                <th>Uraian</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.map(item => `
                                <tr onclick="getProgress('${item.kode_khusus}')" style="cursor: pointer">
                                    <td>${item.kode_khusus}</td>
                                    <td>${item.uraian}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
                detailRow.querySelector('.detail-content').innerHTML = detailContent;
            });
    }

    function getProgress(kodeKhusus) {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        fetch(`get_progress.php?action=chart&kode=${kodeKhusus}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('picInfo').textContent = data.kode_khusus;

                if (myChart) myChart.destroy();

                const ctx = document.getElementById('budgetChart').getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Total Pagu', 'Total Blokir'],
                        datasets: [{
                            data: [
                                parseFloat(data.total_pagu) || 0,
                                parseFloat(data.total_blokir) || 0
                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)', // Biru untuk pagu
                                'rgba(255, 99, 132, 0.8)' // Merah untuk blokir
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: chartOptions
                });
            });
    }

    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let value = context.raw;
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(value);
                    }
                }
            }
        }
    }
    </script>
</body>

</html>