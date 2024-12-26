<?php
session_start();
if (isset($_SESSION['email'])) {
    if ($_SESSION['role'] == 2) {
        header("Location: rkakl/index.php");
    } else if ($_SESSION['role'] == 1) {
        header("Location: admin/dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login RKAKL</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/yearSelect/yearSelect.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: #f5f5f5;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    h2 {
        color: #333;
        margin-bottom: 1.5rem;
        text-align: center;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
        font-size: 0.9rem;
    }

    input,
    select {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: border-color 0.3s;
    }

    input:focus {
        border-color: #4A90E2;
        outline: none;
    }

    button {
        width: 100%;
        padding: 0.8rem;
        background: #4A90E2;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s;
    }

    button:hover {
        background: #357ABD;
    }

    .error-msg {
        background: #ffe6e6;
        color: #d63031;
        padding: 0.8rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .select2-container--default .select2-selection--single {
        height: 45px;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 45px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 28px;
        font-family: 'Poppins', sans-serif;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login RKAKL</h2>
        <?php
        if (isset($_GET['error'])) {
            echo "<div class='error-msg'>Email atau password salah!</div>";
        }
        ?>
        <form action="process/login_process.php" method="POST">
            <div class="form-group">
                <label>Gmail</label>
                <input type="email" name="email" required placeholder="Masukkan email">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password">
            </div>
            <div class="form-group">
                <label>Tahun</label>
                <select name="tahun" id="tahun-picker" required>
                    <?php 
                    $currentYear = date('Y');
                    for($i = $currentYear; $i >= $currentYear-9; $i--) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
    $(document).ready(function() {
        $('#tahun-picker').select2({
            minimumResultsForSearch: Infinity, // ilangin search box
            width: '100%',
        });
    });
    </script>
</body>

</html>