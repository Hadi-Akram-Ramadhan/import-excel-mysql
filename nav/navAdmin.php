<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="shortcut icon" href="image\kementrian.png">
    <link rel="stylesheet" href="css/kita.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a0900c7e55.js" crossorigin="anonymous"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Roboto:wght@300;400;700&display=swap"
        rel="stylesheet">
</head>
<style>
body {
    background-color: #fafafa;
    color: #484b6a;
    font-family: 'Roboto', sans-serif;
}

.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1001;
    background-color: #ffffff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 0.5rem 1rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: top;
}

.navbar:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.header {
    position: sticky;
    background-color: #ffffff;
    width: 100%;
    padding: 0.8rem 1.2rem;
    height: auto;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 1002;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.kemendag {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 1003;
    color: #484b6a;
    font-size: 22px;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
}

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    width: 80px;
    background-color: #ffffff;
    border-right: 1px solid rgba(0, 79, 159, 0.1);
    z-index: 1000;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    padding-top: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: width 0.2s ease;
    padding-bottom: 40px;
    overflow-x: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sidebar.expanded {
    width: 200px;
}

.icon-container {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
    margin-top: 50px;
    transition: background-color 0.2s ease, color 0.2s ease;
    cursor: pointer;
    color: #004F9F;
    /* Default icon color */
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.icon-container.active {
    color: #ffffff;
    /* Color when active */
    background-color: #004F9F;
    /* Background color when active */
}

.icon-container:hover {
    background-color: rgba(0, 79, 159, 0.1);
    color: #004F9F;
    transform: translateY(-2px);
}

.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
    padding-right: 15px;
    margin-left: 80px;
    transition: margin-left 0.3s ease;
}

.container-fluid.expanded {
    margin-left: 220px;
}

.foto {
    height: 45px;
    width: auto;
    filter: drop-shadow(1px 1px 20px rgba(0, 255, 238, 0.5));
    transition: transform 0.3s ease;
    margin-right: 15px;
}

.foto:hover {
    transform: scale(1.05);
}

.nama {
    color: #333333;
    font-size: 16px;
    font-weight: 500;
    margin: 0;
    padding: 0 15px;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
}

.nama:hover {
    transform: translateY(-1px);
    color: #004F9F;
}

.logout {
    color: #dc3545;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    padding: 8px 20px;
    border-radius: 5px;
    transition: all 0.3s ease;
    border: 1px solid #dc3545;
    font-family: 'Poppins', sans-serif;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.logout:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(220, 53, 69, 0.1);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
}

.logout:hover:before {
    width: 200%;
    height: 200%;
}

.logout:hover {
    background-color: #dc3545;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(220, 53, 69, 0.2);
}

.separator {
    border-left: 1px solid #484b6a;
    height: 25px;
    margin: 0 15px;
    opacity: 0.3;
}

.submenu {
    opacity: 0;
    display: none;
    flex-direction: column;
    align-items: flex-start;
    padding: 5px;
    transition: all 0.3s ease;
    max-height: 0;
    overflow: hidden;
    transform: translateY(-10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.submenu-active {
    display: flex;
    max-height: 300px;
    opacity: 1;
    transform: translateY(0);
}

.submenu ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    width: 100%;
}

.submenu li {
    width: 100%;
    margin: 2px 0;
}

.submenu a {
    color: #004F9F;
    text-decoration: none;
    padding: 10px 15px;
    display: block;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 14px;
    width: 100%;
    position: relative;
    background: linear-gradient(90deg, rgba(0, 79, 159, 0) 0%, rgba(0, 79, 159, 0) 100%);
    background-size: 200% 100%;
    background-position: 100% 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: 'Poppins', sans-serif;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.submenu a:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: #004F9F;
    transition: width 0.3s ease;
}

.submenu a:hover:after {
    width: 100%;
}

.submenu a:hover {
    color: #004F9F;
    background-color: rgba(0, 79, 159, 0.08);
    transform: translateX(5px);
    background-position: 0 0;
    padding-left: 20px;
}

.sidebar {
    display: flex;
    flex-direction: column;
    padding-bottom: 20px;
    /* Nambahin padding di bawah */
}

.spacer {
    flex-grow: 1;
}

.sidebar .icon-container:last-child {
    margin-bottom: 10px;
    /* Kurangin margin bawah */
}

.modal {
    display: none;
    position: fixed;
    z-index: 999999999999999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content-p {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
}

.modal-content-p input,
.modal-content-p textarea {
    width: 100%;
    margin-bottom: 10px;
    padding: 5px;
}

.button-group {
    text-align: right;
}

.button-group button {
    margin-left: 10px;
}

button {
    background-color: #E7E8D8;

    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #9394a5;
}

.container-fluid {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.foto {
    width: 50px;
    /* Sesuaiin ukurannya */
    height: auto;
    margin-right: 10px;
    margin-left: 10px;
}

.kemendag-text {
    display: flex;
    flex-direction: column;
}

.kemendag-text h2 {
    color: #004F9F;
    font-size: 18px;
    margin: 0;
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    transform: translateY(0);
    transition: transform 0.3s ease;
}

.kemendag-text p {
    color: #2E8B57;
    font-size: 14px;
    margin: 0;
    font-family: 'Poppins', sans-serif;
    font-weight: 400;
    opacity: 0.9;
    transform: translateY(0);
    transition: transform 0.3s ease;
}

.kemendag-text:hover h2 {
    transform: translateY(-2px);
}

.kemendag-text:hover p {
    transform: translateY(-2px);
}

.navbar-brand {
    display: flex;
    align-items: center;
}

.navbar-brand img {
    margin-right: 10px;
}

.icon-container::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0, 79, 159, 0.08);
    border-radius: 15px;
    top: 0;
    left: -100%;
    transition: all 0.3s ease;
}

.icon-container:hover::after {
    left: 0;
}

/* Animasi untuk icon container */
.icon-container {
    position: relative;
    overflow: hidden;
}

.icon-container::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0, 79, 159, 0.08);
    border-radius: 15px;
    top: 0;
    left: -100%;
    transition: all 0.3s ease;
}

.icon-container:hover::after {
    left: 0;
}
</style>

<body>
    <header>
        <nav class="navbar navbar-expand navbar-grey bg-grey topbar mb-4 static-top shadow header">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <img class="foto" src="image\kementrian.png" alt="">
                    <div class="kemendag-text">
                        <h2>Selamat Datang!</h2>
                        <p>Di Dashboard Admin TU Bapokting</p>
                    </div>
                </div>
                <div class="d-flex align-items-center ml-auto">
                    <h1 class="nama">-</h1>
                    <div class="separator"></div>
                    <a class="logout" href="../logout-admin.php">Log Out</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="sidebar">
        <div class="icon-container" onclick="window.location.href='../admin/dashboard.php'">
            <i class="fa-solid fa-house"></i>
        </div>

        <div class="icon-container" onclick="toggleSubmenu('submenu-manage', this)">
            <i class="fa-solid fa-pen-to-square"></i>
        </div>
        <div class="submenu" id="submenu-manage">
            <ul>
                <li><a href="../admin/upload-rkakl.php">Tambah RKAKL</a></li>
                <li><a href="../admin/tambah-timker.php">Tambah Tim Kerja</a></li>
                <li><a href="../admin/tambah-akun.php">Tambah Akun</a></li>
            </ul>
        </div>

        <div class="icon-container" onclick="toggleSubmenu('submenu-auth', this)">
            <i class="fa-solid fa-key"></i>
        </div>
        <div class="submenu" id="submenu-auth">
            <ul>
                <li><a href="../admin/kegiatan.php">Atur Kegiatan</a></li>
            </ul>
        </div>

        <div class="spacer"></div>

        <div class="icon-container" onclick="toggleSubmenu('submenu-settings', this)">
            <i class="fa-solid fa-gear"></i>
        </div>
        <div class="submenu" id="submenu-settings">
            <ul>
                <li><a href="#" onclick="openSettingsModal()">Belum Ada Fungsi</a></li>
                <li><a href="#" onclick="openEditAdminLayoutModal()">Belum Ada Fungsi</a></li>
            </ul>
        </div>
    </div>



    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
    <script>
    function toggleSubmenu(id, clickedIcon) {
        const submenu = document.getElementById(id);
        const sidebar = document.querySelector('.sidebar');
        const allIcons = document.querySelectorAll('.icon-container');

        if (submenu.classList.contains('submenu-active')) {
            submenu.classList.remove('submenu-active');
            sidebar.classList.remove('expanded');
            setTimeout(() => {
                submenu.style.display = 'none';
            }, 500);
            clickedIcon.classList.remove('active');
        } else {
            const activeSubmenus = document.querySelectorAll('.submenu-active');
            activeSubmenus.forEach(sub => {
                sub.classList.remove('submenu-active');
                sub.style.display = 'none';
            });
            allIcons.forEach(icon => {
                icon.classList.remove('active');
            });
            submenu.style.display = 'flex';
            setTimeout(() => {
                submenu.classList.add('submenu-active');
                sidebar.classList.add('expanded');
            }, 10);
            clickedIcon.classList.add('active');
        }
    }
    </script>
</body>

</html>