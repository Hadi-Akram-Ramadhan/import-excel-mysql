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

// Ubah query untuk data tanpa PIC jadi query untuk semua kegiatan
$queryKegiatan = "SELECT kode_khusus, uraian, pic, tahun
                 FROM dipa 
                 WHERE CHAR_LENGTH(Kode_khusus) = 26 
                 AND CHAR_LENGTH(kode_tunggal) > 0 
                 ORDER BY kode_khusus";
$resultKegiatan = mysqli_query($koneksi, $queryKegiatan);
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
        margin-bottom: 3rem;
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
        height: 300px;
        margin: 2rem auto;
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

    .budget-details {
        margin-top: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .budget-item {
        text-align: center;
    }

    .total-amount {
        font-size: 1.5rem;
        font-weight: bold;
        color: #2d3436;
        margin: 1rem 0;
    }

    .budget-breakdown {
        margin-top: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        position: relative;
    }

    .breakdown-item {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        margin-bottom: 2rem;
    }

    .indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        position: absolute;
        left: -6px;
        top: 50%;
        transform: translateY(-50%);
    }

    .breakdown-item h5 {
        font-size: 0.9rem;
        color: #636e72;
        margin-bottom: 0.5rem;
    }

    .breakdown-item p {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3436;
        margin: 0.5rem 0;
    }

    .realization {
        font-size: 0.9rem !important;
        color: #00b894 !important;
    }

    .connector-line {
        position: absolute;
        border-left: 2px dashed #ccc;
        height: 40px;
        left: 50%;
        top: -40px;
    }

    .connector-dot {
        width: 10px;
        height: 10px;
        background: #ccc;
        border-radius: 50%;
        position: absolute;
        left: 50%;
        top: -45px;
        transform: translateX(-50%);
    }
    </style>
</head>

<body>
    <div class="summary-container">
        <div class="summary-box">
            <h3>TOTAL PAGU ANGGARAN</h3>
            <p><?php echo number_format($rowTotal['total_pagu_all'], 0, ',', '.'); ?></p>
        </div>
        <div class="summary-box">
            <h3>TOTAL BLOKIR ANGGARAN</h3>
            <p><?php echo number_format($rowBlokir['total_blokir_all'], 0, ',', '.'); ?></p>
        </div>
        <div class="summary-box" style="display: flex; flex-direction: column;">
            <div>
                <h3>TOTAL PAGU OPERASIONAL</h3>
                <p><?php echo number_format($rowTotal['total_pagu_all'] - $rowBlokir['total_blokir_all'], 0, ',', '.'); ?>
                </p>
            </div>
            <div style="display: flex; margin-top: 1rem; gap: 1rem;">
                <div style="flex: 1;">
                    <h3 style="font-size: 14px;">REALISASI</h3>
                    <p style="font-size: 16px;">-</p>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 14px;">SISA ANGGARAN</h3>
                    <p style="font-size: 16px;">-</p>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <div style="flex: 0.6; display: flex; flex-direction: column; gap: 1rem;">
            <div class="table-section">
                <h2 class="form-title">Data DIPA</h2>
                <button onclick="resetFilter()" class="btn btn-sm btn-secondary mb-2">Tampilkan Semua</button>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>TIM KERJA</th>
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

            <div class="table-section">
                <h2 class="form-title">Data Kegiatan</h2>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode Khusus</th>
                            <th>Uraian</th>
                            <th>Tim Kerja</th>
                            <th>Tahun</th>
                        </tr>
                    </thead>
                    <tbody id="kegiatanTableBody">
                        <?php while($rowKegiatan = mysqli_fetch_assoc($resultKegiatan)) { ?>
                        <tr onclick="getProgress('<?php echo $rowKegiatan['kode_khusus']; ?>')" style="cursor: pointer"
                            data-pic="<?php echo $rowKegiatan['pic']; ?>">
                            <td><?php echo $rowKegiatan['kode_khusus']; ?></td>
                            <td><?php echo $rowKegiatan['uraian']; ?></td>
                            <td><?php echo $rowKegiatan['pic'] ?: '-'; ?></td>
                            <td><?php echo $rowKegiatan['tahun'] ?: '-'; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="progress-section">
            <h2 class="form-title">MONITORING DIPA</h2>
            <div id="progressInfo">
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

        // Filter tabel kegiatan
        const rows = document.querySelectorAll('#kegiatanTableBody tr');
        rows.forEach(row => {
            if (pic === 'all' || row.getAttribute('data-pic') === pic) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
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
                // Reset konten progressInfo
                document.getElementById('progressInfo').innerHTML = `
                    
                    <div class="chart-container">
                        <canvas id="budgetChart"></canvas>
                    </div>
                    <div id="detailContainer"></div>
                `;

                if (myChart) {
                    myChart.destroy();
                }

                const ctx = document.getElementById('budgetChart').getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pagu Anggaran', 'Anggaran Blokir'],
                        datasets: [{
                            data: [
                                parseFloat(data.total_pagu),
                                parseFloat(data.total_blokir)
                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 99, 132, 0.8)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${formatCurrency(context.raw)}`;
                                    }
                                }
                            }
                        }
                    }
                });

                // Tambahin detail info dengan box
                const detailHTML = `
                    <div class="budget-breakdown">
                        <div class="connector-line" style="border-left: 2px dashed rgba(54, 162, 235, 0.8)"></div>
                        <div class="connector-dot" style="background: rgba(54, 162, 235, 0.8)"></div>
                        <div class="breakdown-item">
                            <div class="indicator" style="background: rgba(54, 162, 235, 0.8)"></div>
                            <h5>PAGU ANGGARAN</h5>
                            <p>${formatCurrency(data.total_pagu)}</p>
                        </div>
                        <div class="breakdown-item">
                            <div class="indicator" style="background: rgba(255, 99, 132, 0.8)"></div>
                            <h5>ANGGARAN BLOKIR</h5>
                            <p>${formatCurrency(data.total_blokir)}</p>
                        </div>
                    </div>
                `;

                document.getElementById('detailContainer').innerHTML = detailHTML;
            });
    }

    // Helper function buat format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    // Tambah function untuk reset filter
    function resetFilter() {
        if (activeRow) {
            activeRow.classList.remove('active-row');
            activeRow = null;
        }
        const rows = document.querySelectorAll('#kegiatanTableBody tr');
        rows.forEach(row => row.style.display = '');
    }
    </script>
</body>

</html>