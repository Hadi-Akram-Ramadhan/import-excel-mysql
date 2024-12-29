<?php
session_start();
require '../nav/navAdmin.php'
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
        position: fixed;
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
    </style>
</head>

<body>
    <div class="form-container">
        <h2 class="form-title">Upload Data Type: RKAKL</h2>

        <div class="loading" id="loading">Processing...</div>

        <form action="process_dipa.php" method="POST" enctype="multipart/form-data" id="uploadForm">
            <div class="form-group">
                <input type="file" class="form-control" name="filexls" placeholder="Masukan file excel" id="formFile">
            </div>
            <button type="submit" name="submit-dipa" value="Upload File XLS/XLSX"
                class="btn btn-primary btn-block">Submit</button>
        </form>
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
    });
    </script>
</body>

</html>