<?php
header('Content-Type: application/json');

include '../function.php';

function regis_seminar($user_log, $seminar_id)
{
    include '../conn.php';
    
    $seminar_idSql = $conn->real_escape_string($seminar_id);

    $stmt = $conn->prepare("SELECT user_id,email FROM user WHERE user_id=?");
    $stmt->bind_param('i', $user_log);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $auth_token = isset($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '';

    if (auth_check_token($auth_token) == false) {

        $peserta = $conn->prepare("SELECT email_peserta, seminar_id FROM peserta_seminar WHERE email_peserta=? AND seminar_id=?");
        $peserta->bind_param("si", $user['email'], $seminar_idSql);
        $peserta->execute();
        $check_peserta = $peserta->get_result()->num_rows;

        if ($check_peserta < 1) {
            $no_reg = bin2hex(random_bytes(5));
            $regis_seminar = $conn->prepare("INSERT INTO peserta_seminar (no_reg, seminar_id, email_peserta) VALUES (?, ?, ?)");
            $regis_seminar->bind_param("sis", $no_reg, $seminar_idSql, $user['email']);
            $regis_seminar->execute();
            $response = [
                'status' => 'success',
                'message' => 'Anda berhasil mendaftar',
                'icon' => '<i class="fa-solid fa-circle-check"></i>',
                'redirect' => 'dashboard/event'
            ];
            http_response_code(200);
        } else {
            $response = [
                'status' => 'erorr',
                'message' => 'Anda sudah mendaftar',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'redirect' => 'dashboard/event'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
            'message' => 'Silahkan login dahulu untuk mendaftar',
            'redirect' => 'login'
        ];

        http_response_code(201);
    }


    return $response;
}

$user_log = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$seminar_id = isset($_GET['id']) ? strip_tags($_GET['id']) : '';

$regis = regis_seminar($user_log, $seminar_id);

echo json_encode($regis, JSON_PRETTY_PRINT);

?>