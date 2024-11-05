<?php 
header("Content-Type: application/json");

$deleteId = $_REQUEST['id'];

    function delete_role($id){
        include '../conn.php';

        $idSql = $conn->real_escape_string($id);

        if($idSql != ''){
            $stmt = $conn->prepare("DELETE FROM role WHERE role_id=?");
            $stmt->bind_param("i",$idSql);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            $data = [
                'status' => 'success',
                'icon' => '<i class="fa-solid fa-circle-check"></i>',
                'message' => 'Role Berhasil Di Hapus'
            ];
            http_response_code(200);
        }else{
            $data = [
                'status' => 'error',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'message' => 'Role Tidak Ditemukan'
            ];
            http_response_code(404);
        }

        return $data;
        
    }
        
    $deleted_role = delete_role($deleteId);
    echo json_encode($deleted_role, JSON_PRETTY_PRINT);
?>