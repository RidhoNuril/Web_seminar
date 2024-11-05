<?php 
header("Content-Type: application/json");

$deleteId = $_REQUEST['id'];

    function delete_user($id){
        include '../conn.php';

        $idSql = $conn->real_escape_string($id);

        if($idSql != ''){
            $stmt = $conn->prepare("DELETE FROM user WHERE user_id=?");
            $stmt->bind_param("i",$idSql);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            $data = [
                'status' => 'success',
                'icon' => '<i class="fa-solid fa-circle-check"></i>',
                'message' => 'User Berhasil Di Hapus'
            ];
            http_response_code(200);
        }else{
            $data = [
                'status' => 'error',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'message' => 'Peserta Tidak Ditemukan'
            ];
            http_response_code(404);
        }

        return $data;
        
    }
        
    $deleted_user = delete_user($deleteId);
    echo json_encode($deleted_user, JSON_PRETTY_PRINT);
?>