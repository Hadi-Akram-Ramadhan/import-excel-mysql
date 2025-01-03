<?php
session_start();
require '../nav/navAdmin.php';
require '../koneksi.php';

$query = "SELECT Kode_khusus, kode_tunggal, uraian, pic 
         FROM dipa 
         WHERE CHAR_LENGTH(Kode_khusus) = 26 AND CHAR_LENGTH(kode_tunggal) > 0";
$result = mysqli_query($koneksi, $query);

// Tambah query untuk ambil data tim
$query_tim = "SELECT nm_timker FROM tbl_tim";
$result_tim = mysqli_query($koneksi, $query_tim);

// Simpan data tim dalam array biar gampang dipake berkali-kali
$tim_options = array();
while($tim = mysqli_fetch_assoc($result_tim)) {
    $tim_options[] = $tim['nm_timker'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convert</title>

    <!-- CSS Dependencies -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css\pencarian.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- JS Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    /* Base Styles */
    body,
    html {
        height: 100%;
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #f8f9fa;
        color: #2d3436;
        width: 100%;
    }

    body::-webkit-scrollbar {
        display: none;
    }

    /* Layout Components */
    .bawah {
        display: flex;
        flex-direction: column;
        margin-top: 5rem;
        align-items: center;
        height: calc(100% - 70px);
        padding: 20px;
    }

    .form-container {
        position: relative;
        z-index: 10;
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
        animation: fadeIn 1s ease-in-out;
        width: 400px;
        max-height: 20rem !important;
        min-height: 20rem !important;
        margin: 10rem auto 150px 40%;
        transition: all 0.3s ease;
    }

    .form-container:hover {
        transform: scale(1.02);
    }

    /* Table Styles */
    .table-container {
        margin-top: 2rem;
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        width: 90%;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 10rem;
        overflow-x: auto;
    }

    .table {
        margin-bottom: 2rem;
        table-layout: fixed;
    }

    .table th,
    .table td {
        width: 200px;
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
        padding: 12px 8px;
        vertical-align: top;
        word-wrap: break-word;
    }

    .table th:last-child,
    .table td:last-child {
        width: 80px;
        text-align: center;
        vertical-align: middle;
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
        min-height: 50px;
    }

    .table tbody tr:hover {
        background: rgba(0, 123, 255, 0.1);
    }

    /* Button Styles */
    .btn {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: scale(1.05);
    }

    /* Modal Styles */
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

    /* Animation */
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

    /* Utility Classes */
    .heading-table,
    .isi-table {
        text-align: center;
    }

    .breadcrumb-lebar {
        width: 100%;
        margin: 0 auto;
    }

    .loading {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        color: #4834d4;
        font-weight: 500;
        z-index: 99999999 !important;
        display: none;
    }

    .swal-fire {
        z-index: 999999 !important;
    }

    /* SweetAlert Z-Index Fix */
    .swal2-container {
        z-index: 999999999 !important;
        /* Pastiin nilainya lebih tinggi dari modal */
    }

    .form-title {
        text-align: center;
        padding: 10px;
        font-size: 1.5rem;
    }

    .pic-container {
        position: relative;
    }

    .pic-text {
        display: inline-block;
        padding: 8px;
    }

    .toggle-edit {
        width: 35px;
        height: 35px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .btn-warning {
        background: #ffa502;
        border: none;
        color: white;
    }

    .btn-success {
        background: #20bf6b;
        border: none;
        color: white;
    }

    /* Style untuk dropdown select */
    .pic-select {
        width: 100%;
        /* Full width */
        white-space: normal;
        /* Biar bisa wrap */
        height: auto;
        /* Auto height */
        min-height: 38px;
        /* Minimal height */
    }

    /* Style untuk option di dalam select */
    .pic-select option {
        padding: 8px;
        white-space: normal;
        /* Biar text bisa wrap */
        word-wrap: break-word;
        /* Force word wrap */
        min-height: 30px;
        /* Minimal height tiap option */
        height: auto;
        /* Auto height */
    }

    /* Container buat select */
    .pic-container {
        position: relative;
        min-width: 200px;
        /* Minimal width sama kaya kolom */
        width: 100%;
    }

    /* Text PIC yang ditampilin */
    .pic-text {
        display: inline-block;
        padding: 8px;
        word-wrap: break-word;
        /* Force word wrap */
        width: 100%;
    }
    </style>
</head>

<body>
    <div class="table-container">
        <table class="table table-bordered table-hover">
            <h2 class="form-title">Atur Kegiatan</h2>
            <thead>
                <tr>
                    <th>Kode Khusus</th>
                    <th>Uraian</th>
                    <th>PIC</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['Kode_khusus']; ?></td>
                    <td><?php echo $row['uraian']; ?></td>
                    <td>
                        <div class="pic-container">
                            <span class="pic-text"><?php echo $row['pic']; ?></span>
                            <select class="form-control pic-select" data-kode="<?php echo $row['Kode_khusus']; ?>"
                                style="display: none;">
                                <?php 
                                if (!empty($row['pic'])) {
                                    echo "<option value='".$row['pic']."' selected>".$row['pic']."</option>";
                                } else {
                                    echo "<option value=''>Pilih PIC</option>";
                                }
                                
                                foreach($tim_options as $tim) {
                                    if ($tim != $row['pic']) {
                                        echo "<option value='".$tim."'>".$tim."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-sm toggle-edit" data-editing="false">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="visual.js"></script>

    <script>
    $(document).ready(function() {
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();

            // Validasi file input
            const fileInput = $('#formFile')[0];
            if (!fileInput.files.length) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Pilih file dulu ya!',
                    icon: 'warning'
                });
                return;
            }

            // Validasi extension
            const fileName = fileInput.files[0].name;
            const fileExt = fileName.split('.').pop().toLowerCase();
            if (!['xls', 'xlsx'].includes(fileExt)) {
                Swal.fire({
                    title: 'Error!',
                    text: 'File harus format XLS/XLSX ya!',
                    icon: 'warning'
                });
                return;
            }

            $('#loading').show();

            let formData = new FormData(this);
            formData.append('submit-dipa', 'true');

            $.ajax({
                url: 'process_dipa.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#loading').hide();
                    try {
                        const result = JSON.parse(response);
                        Swal.fire({
                            title: result.success ? 'Success!' : 'Error!',
                            text: result.message,
                            icon: result.success ? 'success' : 'error'
                        });
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        console.error('Raw response:', response);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Invalid server response',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    console.error('Ajax error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat upload file: ' + error,
                        icon: 'error'
                    });
                }
            });
        });

        $('.toggle-edit').click(function() {
            const btn = $(this);
            const row = btn.closest('tr');
            const picContainer = row.find('.pic-container');
            const picText = picContainer.find('.pic-text');
            const picSelect = picContainer.find('.pic-select');

            if (btn.data('editing') === false) {
                // Mode edit
                picText.hide();
                picSelect.show();
                btn.removeClass('btn-warning').addClass('btn-success');
                btn.html('<i class="fas fa-check"></i>');
                btn.data('editing', true);
            } else {
                // Mode save
                const pic = picSelect.val();
                const kode = picSelect.data('kode');

                if (!pic) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Pilih PIC dulu ya!',
                        icon: 'warning'
                    });
                    return;
                }

                $.ajax({
                    url: 'update_pic.php',
                    type: 'POST',
                    data: {
                        kode_khusus: kode,
                        pic: pic
                    },
                    success: function(response) {
                        try {
                            const result = JSON.parse(response);
                            if (result.success) {
                                picText.text(pic).show();
                                picSelect.hide();
                                btn.removeClass('btn-success').addClass('btn-warning');
                                btn.html('<i class="fas fa-edit"></i>');
                                btn.data('editing', false);

                                Swal.fire({
                                    title: 'Success!',
                                    text: result.message,
                                    icon: 'success'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: result.message,
                                    icon: 'error'
                                });
                            }
                        } catch (e) {
                            console.error('Error:', e);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan sistem',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal menghubungi server',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
    </script>
</body>

</html>