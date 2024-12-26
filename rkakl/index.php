<?php
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
if (!isset($_SESSION['email']) || $_SESSION['role'] != 2) {
    header("Location: ../index.php");
    exit();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convert</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://use.typekit.net/xxxxxx.css">
    <link rel="stylesheet" href="css\pencarian.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body,
    html {
        height: 100%;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        color: #2d3436;
        position: fixed;
        width: 100%;
    }

    body::-webkit-scrollbar {
        display: none;
    }

    .bawah {
        display: flex;
        flex-direction: column;
        margin-top: 5rem;
        align-items: center;
        height: calc(100% - 70px);
        padding: 20px;
    }

    h1 {
        margin-bottom: 20px;
        color: #333333;
        font-weight: 500;
    }

    .form-container {
        position: relative;
        z-index: 9999;
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
        animation: fadeIn 1s ease-in-out;
        width: 400px;
        margin: 8rem auto;
        transition: all 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-container h2 {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3436;
    }

    .form-control {
        margin-bottom: 20px;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: #333333;
    }

    .form-control::placeholder {
        color: rgba(51, 51, 51, 0.6);
    }

    .btn {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background: #4834d4;
        border: none;
    }

    .btn-primary:hover {
        background: #686de0;
        transform: translateY(-2px);
    }

    .btn-success {
        background: #28a745;
        border: none;
        transition: background 0.3s ease;
    }

    .btn-success:hover {
        background: #218838;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
        transition: background 0.3s ease;
    }

    .btn-secondary:hover {
        background: #565e64;
    }

    .container.modal-container {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: #ffffff;
        border: 1px solid #ccc;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
        max-height: 80vh;
        overflow-y: auto;
        border-radius: 10px;
        z-index: 105099999999;
        height: 1000px;
        width: 1000px;

    }

    .table-bodi {
        overflow-y: auto;
    }

    .table-container {
        max-height: 70vh;
        overflow-y: auto;
    }

    .table thead th {
        background: #4834d4;
        color: white;
        font-weight: 500;
        padding: 1rem;
        border: none;
    }

    .table tbody tr {
        transition: background 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(0, 123, 255, 0.1);
    }

    .btn-danger {
        background: #dc3545;
        border: none;
        transition: background 0.3s ease;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .loading {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        color: #4834d4;
        font-weight: 500;
        z-index: 2000;
        display: none;
    }

    .pilih-head {
        background: white;

    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: transparent;
        border-radius: 50%;
    }

    .carousel-control-prev-icon::before,
    .carousel-control-next-icon::before {
        color: black;
    }

    body,
    html {
        height: 100%;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        color: #2d3436;
    }

    .pilih-head {
        background: linear-gradient(135deg, #007bff, #00c4ff);
        padding: 15px 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        margin-bottom: 20px;
        color: white;
        text-align: center;
    }

    .pilih-head i {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .nav-link {
        font-weight: 500;
        color: white;
        transition: color 0.3s ease;
    }

    .nav-link:hover {
        color: #ffeb3b;
        text-decoration: underline;
    }

    .nav-item {
        margin: 0 15px;
    }

    .form-container {
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        z-index: 99;
    }

    .form-container:hover {
        transform: scale(1.02);
    }

    .btn {
        border-radius: 5px;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn:hover {
        transform: scale(1.05);
    }

    .loading {
        font-size: 24px;
        color: #4834d4;
        font-weight: 500;
        display: none;
    }

    .modal-container {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    }

    .table {
        border-radius: 10px;
        overflow: hidden;
    }

    .table thead th {
        background: #4834d4;
        color: white;
    }

    .table tbody tr:hover {
        background: rgba(0, 123, 255, 0.1);
    }

    .heading-table {
        text-align: center;
    }

    .isi-table {
        text-align: center;
    }

    .breadcrumb-lebar {
        width: 100%;
        margin: 0 auto;
    }

    .cari-table {
        width: 100%;

    }

    .form-container {
        max-height: 20rem !important;
        min-height: 20rem !important;
        z-index: 99;
        width: 400px;
        margin-bottom: 150px;
        margin-top: 10rem;
        margin-left: 40%;

    }

    .pagginasi {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }

    .loading {
        z-index: 99999999 !important;
    }
    </style>

</head>

<body>

    <div class="form-container">
        <h2 class="form-title">Upload Data Type: RKAKL</h2>

        <?php include("process_dipa.php") ?>
        <div class="loading" id="loading">
            <!-- Loading spinner akan muncul dari CSS -->
        </div>
        <form action="" method="POST" enctype="multipart/form-data" id="uploadForm">
            <div class="form-group">
                <input type="file" class="form-control" name="filexls" placeholder="Masukan file excel" id="formFile">
            </div>
            <button type="submit" name="submit-dipa" value="Upload File XLS/XLSX"
                class="btn btn-primary btn-block">Submit</button>
        </form>
        <button name="lihat" id="lihat" class="btn btn-success btn-block mt-3">Atur PIC</button>

        <a href="../logout.php" class="btn btn-danger btn-block mt-3">Logout</a>
    </div>





    <div class="container modal-container" id="popup">
        <div class="tutupincok">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <nav aria-label="breadcrumb" class="breadcrumb-lebar">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" data-toggle="tooltip" data-placement="top"
                                    title="Data Inputan">
                                    <a href="#"><i class="fa-solid fa-file-csv"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">List Table</li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <div class="pencarian-container">
                                        <form class="pencarian-form">
                                            <input type="text" class="pencarian-input" placeholder="Cari sesuatu...">
                                            <button type="button" class="pencarian-button-cari">Cari</button>
                                            <button type="button" class="pencarian-button-tutup"><i
                                                    class="fas fa-times"></i></button>
                                        </form>
                                    </div>
                                    <div>
                                        <a class="fa-brands fa-searchengin" href="#" id="Cari"></a>

                                    </div>
                                </li>
                            </ol>
                        </nav>

                    </div>
                    <div class="col col-lg-2 d-flex justify-content-right kanan">
                        <button type="button" class="btn btn-danger tutup-btn d-flex justify-content-center ml-3"
                            id="tutupya"><i class="fa-solid fa-x"></i></button>
                    </div>
                    <style>
                    .tutup-btn {
                        width: 10%;
                        height: 30px;

                    }

                    .kanan {
                        justify-content: right;
                    }
                    </style>
                </div>
            </div>
        </div>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" class="heading-table">No</th>
                    <th scope="col" class="heading-table">Nama Tabel</th>
                    <th scope="col" class="heading-table">Aksi</th>
                    <th scope="col" class="heading-table">Tanggal Penginputan</th>
                </tr>
            </thead>
            <tbody class="table-bodi" id="tableBody">


            </tbody>



            <script>
            // Total data yang sudah ada dalam tbody
            const totalData = document.querySelectorAll('#tableBody tr').length;
            const rowsPerPage = 5; // Jumlah baris yang ingin ditampilkan per halaman
            let currentPage = 1; // Halaman saat ini

            // Fungsi untuk merender tabel
            function renderTable() {
                const rows = document.querySelectorAll('#tableBody tr');
                rows.forEach((row, index) => {
                    // Menghitung index baris untuk menampilkan berdasarkan halaman
                    row.style.display = (Math.floor(index / rowsPerPage) === currentPage - 1) ? '' : 'none';
                });

                updatePagination();
            }

            // Fungsi untuk mengupdate tombol pagination
            function updatePagination() {
                const pagination = document.querySelector('.pagination');
                const totalPages = Math.ceil(totalData / rowsPerPage);


                const pageItems = Array.from(pagination.children).slice(1, -1);
                pageItems.forEach(item => item.remove());


                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.classList.add('page-item');
                    li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                    pagination.insertBefore(li, document.getElementById('nextPage'));
                }


                document.getElementById('prevPage').classList.toggle('disabled', currentPage === 1);


                document.getElementById('nextPage').classList.toggle('disabled', currentPage === totalPages);
            }


            document.getElementById('prevPage').addEventListener('click', (e) => {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                }
            });

            document.getElementById('nextPage').addEventListener('click', (e) => {
                e.preventDefault();
                const totalPages = Math.ceil(totalData / rowsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderTable();
                }
            });

            // Event listener untuk pagination numbers


            // Render tabel pertama kali
            renderTable();
            </script>
        </table>

        <h1> </h1>
        <canvas id="myChart" width="400" height="200"></canvas>

        <script>
        document.addEventListener('DOMContentLoaded', function() {

            const closeButton = document.querySelector('.pencarian-button-tutup');
            const searchContainer = document.querySelector('.pencarian-container');
            const icon_pencarian = document.getElementById('Cari');

            icon_pencarian.style.display = "none";

            closeButton.addEventListener('click', function() {
                searchContainer.classList.add('hidden');
                icon_pencarian.style.display = "block";
            });

            icon_pencarian.addEventListener('click', function() {
                searchContainer.classList.remove('hidden');
                icon_pencarian.style.display = "none";
            });

            const tutup1 = document.getElementById("tutupya");
            const modaldet = document.getElementById("popupdet");

            tutup1.addEventListener('click', function() {

                document.getElementById('tableBody').innerHTML = '';
                document.querySelector('thead.statis').innerHTML = '';
                list_real = false;



                document.getElementById('popup').style.display = 'none';
            });


            const btn_real = document.getElementById("lihat");



            // Reset on page load
            list_real = false;

            document.getElementById('tableBody').innerHTML = ''; // Clear the table body



            btn_real.addEventListener('click', function() {
                list_real = true;

                loadTable(); // Call the function to load the main table
            });

            function loadTable() {
                if (list_real) {
                    const table_real = `<?php
                    require '../koneksi.php';

                    // Handle deletion of records
                    if (isset($_POST['delete-realisasi'])) {
                        $tableName = $koneksi->real_escape_string($_POST['table_name']);
                        $stmt = $koneksi->prepare("DELETE FROM realisasi WHERE nama_tabel = ?");
                        $stmt->bind_param("s", $tableName);
                        $stmt->execute();
                        $stmt->close();
                    }

                    // Fetch table names
                    $selectQuery = "SELECT DISTINCT nama_tabel FROM realisasi";
                    $result = $koneksi->query($selectQuery);
                    $nomor = 0;

                    if ($result->num_rows > 0) {
                        
                        while ($row = $result->fetch_assoc()) {
                            $nama_tabel = htmlspecialchars($row['nama_tabel']);
                            $TanggalQuery = "SELECT waktu FROM realisasi WHERE nama_tabel = ?";
                            $stmt = $koneksi->prepare($TanggalQuery);
                            $stmt->bind_param("s", $nama_tabel);
                            $stmt->execute();
                            $tanggal_row = $stmt->get_result()->fetch_assoc();
                            $hasil_tanggal = htmlspecialchars($tanggal_row['waktu'] ?? '');
                            $nomor++;

                            echo '<tr>
                                    <td class="isi-table">' . $nomor . '</td>
                                    <td class="isi-table">' . $nama_tabel . '</td>
                                    <td class="isi-table">
                                        <form action="" method="POST" style="display:inline;">
                                            <input type="hidden" name="table_name" value="' . $nama_tabel . '">
                                            <button type="submit" name="delete-realisasi" class="btn btn-danger">Hapus</button>
                                            <button type="button" class="btn btn-secondary detail-btn" data-table-name="' . $nama_tabel . '">Rincian</button>
                                        </form>
                                    </td>
                                    <td class="isi-table"><h6 class="isi-table">' . $hasil_tanggal . '</h6></td>
                                </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="2">No records found</td></tr>';
                    }

                    $koneksi->close(); // Close the connection
                ?>`;

                    // Insert the table rows into the tbody
                    document.getElementById('tableBody').innerHTML = table_real;
                }
            }


            // Event listeners for detail and visual buttons
            document.getElementById('tableBody').addEventListener('click', function(event) {
                if (event.target.classList.contains('detail-btn')) {
                    const tableName = event.target.getAttribute('data-table-name');
                    const head_real = `
                    <tr>
                        <th scope="col" class="isi-table">id</th>
                        <th scope="col">kode_generate</th>
                        <th scope="col">ef</th>
                        <th scope="col">kro</th>
                        <th scope="col">uraian_kro</th>
                        <th scope="col">kode1</th>
                        <th scope="col">kode2</th>
                        <th scope="col">uraian</th>
                        <th scope="col">bidang</th>
                        <th scope="col">akun</th>
                        <th scope="col">kelas_utama</th>
                        <th scope="col">kelas_kedua</th>
                        <th scope="col">kelas_ketiga</th>
                        <th scope="col">isi_1</th>
                        <th scope="col">isi_2</th>
                        <th scope="col">pagu</th>
                        <th scope="col">pred_lalu</th>
                        <th scope="col">pred_ini</th>
                        <th scope="col">s_pred</th>
                        <th scope="col">persenan</th>
                        <th scope="col">Sisa Anggaran</th>
                        <th scope="col">Berdasarkan</th>
                    </tr>`;

                    // Fetch table data via AJAX
                    fetch('fetch_table_data.php?table=' + encodeURIComponent(tableName))
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('table-data').innerHTML =
                            data; // Update table with fetched data
                            modaldet.style.display = "block"; // Show the modal
                            document.querySelector('thead.statis').innerHTML = head_real;
                        });
                } else if (event.target.classList.contains('visual-btn')) {
                    const tableName = event.target.getAttribute('data-table-name');

                    // Fetch visual data logic here
                    const dataKiriman = {
                        tableName: tableName
                    };

                    fetch('fetch_visual_data.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(dataKiriman)
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Process and display the visual data
                            console.log(data); // For debugging
                            // Implement chart rendering logic here
                        })
                        .catch(error => console.error('Error:', error));
                } else if (event.target.classList.contains('detail-btn-subkomponen')) {
                    const tableName = event.target.getAttribute('data-table-name');

                    const head_subkompo = `
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">kode_generate</th>
                        <th scope="col">ef</th>
                        <th scope="col">aea</th>
                        <th scope="col">kode1</th>
                        <th scope="col">kode2</th>
                        <th scope="col">uraian</th>
                        <th scope="col">bidang</th>
                        <th scope="col">akun</th>
                        <th scope="col">kelas_utama</th>
                        <th scope="col">kelas_kedua</th>
                        <th scope="col">kelas_ketiga</th>
                        <th scope="col">pagu</th>
                        <th scope="col">s_pred</th>
                        <th scope="col">persenan</th>
                        <th scope="col">Sisa Anggaran</th>
                    </tr>`;

                    // Fetch table data via AJAX
                    fetch('fetch_table_data_subkompo.php?table=' + encodeURIComponent(tableName))
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('table-data').innerHTML =
                            data; // Update table with fetched data
                            modaldet.style.display = "block"; // Show the modal

                            // Update table header dynamically by targeting the <thead> with the class 'statis'
                            document.querySelector('thead.statis').innerHTML = head_subkompo;
                        });
                }

            });

        });
        </script>



        <style>
        .kocak {
            width: 100%;
            height: 500px;
        }

        .myChart {
            height: 300px;
            width: 300px;
            margin-top: 50px;
            position: relative;
        }
        </style>

    </div>
    </div>


    <div class="container modal-container" id="popupdet">
        <div class="row">
            <div class="col-sm">

            </div>
            <div class="col-sm">

            </div>
            <div class="col-sm d-flex justify-content-right kanan">
                <button type="button" class="btn btn-danger tutup-btn d-flex justify-content-center" id="tutupyalagi"><i
                        class="fa-solid fa-x"></i></button>
            </div>
            <style>
            .tutup-btn {
                width: 10%;
                height: 30px;

            }

            .kanan {
                justify-content: right;
            }
            </style>
        </div>
        <div class="table-container">
            <table class="table">
                <thead class="statis">
                    <!-- Header will be dynamically inserted here -->
                </thead>
                <style>
                .statis {
                    position: relative;
                }

                .fullken {
                    width: 100%;
                }
                </style>
                <tbody id="table-data" class="fullken">
                    <!-- Data rows will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="visual.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const visualButtons = document.querySelectorAll(".visual-btn");

        let existingChart = null;


        visualButtons.forEach(button => {
            button.addEventListener("click", function() {
                const tableName = this.getAttribute("data-table-name");
                const tabel = document.getElementById("myChart");

                const tutup1 = document.getElementById("tutupya");

                tabel.style.display = "block";

                const dataKiriman = {
                    tableName: tableName
                };

                fetch('fetch_visual_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataKiriman)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Mengolah data yang diterima dari server
                        console.log(data.labels);
                        console.log(data.pagu);
                        console.log(data.s_pred);

                        // Hancurkan chart yang sudah ada jika ada
                        if (existingChart) {
                            existingChart.destroy();
                        }

                        // Buat chart baru
                        const ctx = document.getElementById('myChart').getContext('2d');
                        existingChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels, // Label untuk sumbu X
                                datasets: [{
                                    label: 'Bidang',
                                    data: data.pagu, // Data untuk sumbu Y
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });


                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const lihat = document.getElementById("lihat");

        const modal = document.getElementById("popup");
        const modaldet = document.getElementById("popupdet");


        const tutup2 = document.getElementById("tutupyalagi");
        const visual = document.getElementById("myChart");






        visual.style.display = "none";


        tutup2.addEventListener('click', function() {
            modaldet.style.display = "none";
        })



        lihat.addEventListener('click', function() {
            modal.style.display = "block";
        });



        // Close modal detail when clicking outside of it



    });
    </script>



    <!-- Footer -->



</body>

</html>