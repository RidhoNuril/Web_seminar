<?php
header('Content-Type: application/json');

$nama = isset($_POST['nama']) ? strip_tags($_POST['nama']) : '';
$password = isset($_POST['password']) ? strip_tags($_POST['password']) : '';
$kelas = isset($_POST['kelas']) ? strip_tags($_POST['kelas']) : '';
$sekolah = isset($_POST['asal_sekolah']) ? strip_tags($_POST['asal_sekolah']) : '';
$email = isset($_POST['email']) ? strip_tags($_POST['email']) : '';
$telp = isset($_POST['no_telp']) ? strip_tags($_POST['no_telp']) : '';
$provinsi = isset($_POST['provinsi']) ? strip_tags($_POST['provinsi']) : '';
$kabupaten = isset($_POST['kabupaten']) ? strip_tags($_POST['kabupaten']) : '';
$kecamatan = isset($_POST['kecamatan']) ? strip_tags($_POST['kecamatan']) : '';
$kelurahan = isset($_POST['kelurahan']) ? strip_tags($_POST['kelurahan']) : '';

function tambah_user($nama, $password, $kelas, $sekolah, $email, $telp, $provinsi, $kabupaten, $kecamatan, $kelurahan)
{
    include '../conn.php';

    $namaSql = $conn->real_escape_string($nama);
    $passwordSql = $conn->real_escape_string(password_hash($password, PASSWORD_BCRYPT));
    $kelasSql = $conn->real_escape_string($kelas);
    $sekolahSql = $conn->real_escape_string($sekolah);
    $emailSql = $conn->real_escape_string($email);
    $telpSql = $conn->real_escape_string($telp);
    $provinsiSql = $conn->real_escape_string($provinsi);
    $kabupatenSql = $conn->real_escape_string($kabupaten);
    $kecamatanSql = $conn->real_escape_string($kecamatan);
    $kelurahanSql = $conn->real_escape_string($kelurahan);

    if ($namaSql && $passwordSql && $kelasSql && $sekolahSql && $emailSql && $telpSql && $provinsiSql && $kabupatenSql && $kecamatanSql && $kelurahanSql != '') {
        $role = $conn->prepare("SELECT role_id FROM role WHERE nama_role='user'");
        $role->execute();
        $role_result = $role->get_result()->fetch_assoc();

        $stmt = $conn->prepare("INSERT INTO user (nama, password, kelas, asal_sekolah, email, no_telp, provinsi, kabupaten, kecamatan, kelurahan, role_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssi", $namaSql, $passwordSql, $kelasSql, $sekolahSql, $emailSql, $telpSql, $provinsiSql, $kabupatenSql, $kecamatanSql, $kelurahanSql, $role_result['role_id']);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        $response = [
            'status' => 'success',
            'icon' => '<i class="fa-solid fa-circle-check"></i>',
            'message' => 'User Berhasil Ditambahkan',
            'redirect' => '../user'
        ];
        http_response_code(200);
    } else {
        $response = [
            'status' => 'error',
            'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
            'message' => 'Semua Field Wajib Diisi'
        ];
        http_response_code(400);
    }
    return $response;
}

$regis_success = tambah_user($nama, $password, $kelas, $sekolah, $email, $telp, $provinsi, $kabupaten, $kecamatan, $kelurahan);
echo json_encode($regis_success, JSON_PRETTY_PRINT);

?>