<?php
include "../koneksi.php";
include "../nav/navAdmin.php";

if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $bidang = $_POST['bidang'];
    $nama = $_POST['nama'];
    
    $query = "INSERT INTO akun (email, nama,  password, role, bidang) VALUES ('$email', '$nama', '$password', '$role', '$bidang')";
    
    if(mysqli_query($koneksi, $query)) {
        echo "<script>
            alert('Akun berhasil ditambahkan!');
            window.location.href='tambah-akun.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menambahkan akun!');
            window.location.href='tambah-akun.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3>Tambah Akun Baru</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Role</label>
                                <select name="role" id="roleSelect" onchange="toggleBidangDropdown()">
                                    <option value="1">Admin</option>
                                    <option value="2">Operator</option>
                                </select>
                            </div>
                            <div class="mb-3" id="bidangDropdown" style="display: none;">
                                <label class="form-label">Pilih Bidang</label>
                                <select name="bidang" id="bidang">
                                    <option value="" disabled selected>Pilih Bidang</option>
                                    <option value="BID.01">Bidang Bapok Hasil Pertanian dan Hortikultura</option>
                                    <option value="BID.02">Bidang Bapok Hasil Peternakan dan Perikanan</option>
                                    <option value="BID.03">Bidang Bapok Hasil Industri</option>
                                    <option value="BID.04">Bidang Barang Penting</option>
                                    <option value="BID.05">Bidang Informasi Pasar</option>
                                    <option value="BID.TU">Tata Usaha</option>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Tambah Akun</button>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#modalAkun">
                                Lihat Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAkun" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Nama</th>
                                    <th>Bidang</th>
                                    <th>Tahun</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM akun";
                                $result = mysqli_query($koneksi, $query);
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['nama']}</td>";
                                    echo "<td>{$row['bidang']}</td>";
                                    echo "<td>{$row['tahun']}</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function toggleBidangDropdown() {
        const roleSelect = document.getElementById('roleSelect');
        const bidangDropdown = document.getElementById('bidangDropdown');
        const bidang = document.getElementById('bidang');

        if (roleSelect.value === '1') {
            bidangDropdown.style.display = 'none';
        } else {
            bidangDropdown.style.display = 'block';
            bidang.value = '';

        }
    }
    </script>
</body>

</html>