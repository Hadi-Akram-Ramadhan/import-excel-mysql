<?php 
include "../koneksi.php";
include "../nav/navAdmin.php";

if(isset($_POST['submit'])) {
    $nm_timker = $_POST['nm_timker'];
    $pic = $_POST['pic'];
    $tahun = $_POST['tahun'];
    

    $query = "INSERT INTO tbl_tim (nm_timker, pic_timker, tahun) 
              VALUES ('$nm_timker', '$pic', '$tahun')";

    if(mysqli_query($koneksi, $query)) {
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Tim Kerja berhasil ditambahkan!',
                icon: 'success'
            }).then((result) => {
                window.location.href = 'tambah-timker.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal menambahkan Tim Kerja!',
                icon: 'error'
            });
        </script>";
    }
}

// Proses Edit
if(isset($_POST['edit'])) {
    $kd_timker = $_POST['kd_timker'];
    $nm_timker = $_POST['nm_timker'];
    $pic = $_POST['pic'];
    $tahun = $_POST['tahun'];
    
    $query = "UPDATE tbl_tim SET nm_timker='$nm_timker', pic_timker='$pic', tahun='$tahun' WHERE kd_timker='$kd_timker'";
    
    if(mysqli_query($koneksi, $query)) {
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Tim Kerja berhasil diupdate!',
                icon: 'success'
            }).then((result) => {
                window.location.href = 'tambah-timker.php';
            });
        </script>";
    }
}

// Proses Hapus
if(isset($_GET['delete'])) {
    $kd_timker = $_GET['delete'];
    
    $query = "DELETE FROM tbl_tim WHERE kd_timker='$kd_timker'";
    
    if(mysqli_query($koneksi, $query)) {
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Tim Kerja berhasil dihapus!',
                icon: 'success'
            }).then((result) => {
                window.location.href = 'tambah-timker.php';
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3>Tambah Tim Kerja Baru</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Tim Kerja</label>
                                <input type="text" name="nm_timker" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">PIC Tim Kerja</label>
                                <input type="text" name="pic" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Tahun Tim Kerja</label>
                                <select name="tahun" class="form-control" required>
                                    <?php 
                                    $currentYear = date('Y');
                                    for($year = $currentYear + 2; $year >= $currentYear-10; $year--) {
                                        $selected = ($year == $currentYear) ? 'selected' : '';
                                        echo "<option value='$year' $selected>$year</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" name="submit" class="btn btn-primary">Tambah Tim Kerja</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah section baru untuk menampilkan data -->
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Data Tim Kerja</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode Tim</th>
                                <th>Nama Tim</th>
                                <th>PIC</th>
                                <th>Tahun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM tbl_tim ORDER BY kd_timker DESC";
                            $result = mysqli_query($koneksi, $query);
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>{$row['kd_timker']}</td>";
                                echo "<td>{$row['nm_timker']}</td>";
                                echo "<td>{$row['pic_timker']}</td>";
                                echo "<td>{$row['tahun']}</td>";
                                echo "<td>
                                    <button onclick='editTimker({$row['kd_timker']}, \"{$row['nm_timker']}\", \"{$row['pic_timker']}\", \"{$row['tahun']}\")' 
                                        class='btn btn-warning'>Edit</button>
                                    <a href='?delete={$row['kd_timker']}' 
                                        onclick='return confirm(\"Yakin mau hapus tim kerja ini?\")' 
                                        class='btn btn-danger'>Hapus</a>
                                </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tim Kerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="kd_timker" id="edit_kd_timker">
                        <div class="mb-3">
                            <label class="form-label">Nama Tim Kerja</label>
                            <input type="text" name="nm_timker" id="edit_nm_timker" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PIC Tim Kerja</label>
                            <input type="text" name="pic" id="edit_pic" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="text" name="tahun" id="edit_tahun" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function editTimker(kd_timker, nm_timker, pic, tahun) {
        document.getElementById('edit_kd_timker').value = kd_timker;
        document.getElementById('edit_nm_timker').value = nm_timker;
        document.getElementById('edit_pic').value = pic;
        document.getElementById('edit_tahun').value = tahun;

        new bootstrap.Modal(document.getElementById('editModal')).show();
    }
    </script>
</body>

</html>