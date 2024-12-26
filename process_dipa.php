// 1. Pastikan nilai status selalu ada sebelum insert
if (empty($status)) {
$status = 'default_value'; // Ganti dengan nilai default yang sesuai
}

// 2. Tambah validasi sebelum panggil fungsi string
if ($haystack !== null) {
$result = strpos($haystack, $needle);
}

// 3. Validasi parameter sebelum preg_replace
if ($subject !== null) {
$result = preg_replace($pattern, $replacement, $subject);
}

// 4. Validasi sebelum strlen
if ($string !== null) {
$length = strlen($string);
}