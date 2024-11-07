<?php 
include '../function.php';

function reset_password($password_lama, $password_baru, $konfirmasi_password){
    include '../conn.php';

    $user_session = session_profile_user();

    $password_lamaSql = $conn->real_escape_string($password_lama);
    $password_baruSql = $conn->real_escape_string($password_baru);
    $konfirmasi_passwordSql = $conn->real_escape_string($konfirmasi_password);

    if(password_verify($password_lamaSql, $user_session['password'])){
        if($password_baruSql == $konfirmasi_passwordSql){

            $password_hash = password_hash($password_baruSql, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE user SET password=? WHERE user_id=?");
            $stmt->bind_param("si", $password_hash, $user_session['user_id']);
            $stmt->execute();

            $response = [
                'status' => 'success',
                'icon' => '<i class="fa-solid fa-circle-check"></i>',
                'message' => 'Password berhasil diubah'
            ];

        }else{
            $response = [
                'status' => 'error',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'message' => 'Konfirmasi password tidak sesuai'
            ];
        }

    }else{
        $response = [
            'status' => 'error',
            'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
            'message' => 'Password lama salah'
        ];
    }

    return $response;
}

$password_lama = isset($_POST['password_lama']) ? strip_tags($_POST['password_lama']) : '';
$password_baru = isset($_POST['password_baru']) ? strip_tags($_POST['password_baru']) : '';
$konfirmasi_password = isset($_POST['konfirmasi_password']) ? strip_tags($_POST['konfirmasi_password']) : '';

$reset = reset_password($password_lama, $password_baru, $konfirmasi_password);
echo json_encode($reset);

?>