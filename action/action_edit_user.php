<?php 
    header("Content-Type: application/json");

    $id = isset($_POST['id']) ? strip_tags($_POST['id']) : '';
    $nama = isset($_POST['nama']) ? strip_tags($_POST['nama']) : '';
    $kelas = isset($_POST['kelas']) ? strip_tags($_POST['kelas']) : '';
    $sekolah = isset($_POST['asal_sekolah']) ? strip_tags($_POST['asal_sekolah']) : '';
    $email = isset($_POST['email']) ? strip_tags($_POST['email']) : '';
    $telp = isset($_POST['no_telp']) ? strip_tags($_POST['no_telp']) : '';
    $provinsi = isset($_POST['provinsi']) ? strip_tags($_POST['provinsi']) : '';    
    $kabupaten = isset($_POST['kabupaten']) ? strip_tags($_POST['kabupaten']) : '';
    $kecamatan = isset($_POST['kecamatan']) ? strip_tags($_POST['kecamatan']) : '';
    $kelurahan = isset($_POST['kelurahan']) ? strip_tags($_POST['kelurahan']) : '';
    $role_id = isset($_POST['role_id']) ? strip_tags($_POST['role_id']) : '';

    function update_user($id, $nama, $kelas, $sekolah, $email, $telp, $provinsi, $kabupaten, $kecamatan, $kelurahan, $role_id){
        include '../conn.php';

        $idSql = $conn->real_escape_string($id);
        $namaSql = $conn->real_escape_string($nama);
        $kelasSql = $conn->real_escape_string($kelas);
        $sekolahSql = $conn->real_escape_string($sekolah);
        $emailSql = $conn->real_escape_string($email);
        $telpSql = $conn->real_escape_string($telp);
        $provinsiSql = $conn->real_escape_string($provinsi);
        $kabupatenSql = $conn->real_escape_string($kabupaten);
        $kecamatanSql = $conn->real_escape_string($kecamatan);
        $kelurahanSql = $conn->real_escape_string($kelurahan);
        $roleSql = $conn->real_escape_string($role_id);

        if($idSql && $namaSql && $kelasSql && $sekolahSql && $emailSql && $telpSql && $provinsiSql && $kabupatenSql && $kecamatanSql && $kelurahanSql != ''){
            $stmt = $conn->prepare("UPDATE user SET nama=?, kelas=?, asal_sekolah=?, email=?, no_telp=?, provinsi=?, kabupaten=?, kecamatan=?, kelurahan=?, role_id=? WHERE user_id=?");
            $stmt->bind_param("sssssssssii", $namaSql, $kelasSql, $sekolahSql, $emailSql, $telpSql, $provinsiSql, $kabupatenSql, $kecamatanSql, $kelurahanSql, $roleSql, $idSql);
            $stmt->execute();
            $conn->close();
            $response = [
                'status' => 'success',
                'icon' => '<i class="fa-solid fa-circle-check"></i>',
                'message' => 'User Berhasil Di Update',
                'redirect' => '../user'
            ];
            http_response_code(200);
        }else{
            $response = [
                'status' => 'error',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'message' => 'Semua Field Wajib Diisi'
            ];
        }

        return $response;
    }

    $edited = update_user($id, $nama, $kelas, $sekolah, $email, $telp, $provinsi, $kabupaten, $kecamatan, $kelurahan, $role_id);
    echo json_encode($edited, JSON_PRETTY_PRINT);
?>