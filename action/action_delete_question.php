<?php 
header("Content-Type: application/json");

$deleteId = $_GET['id'];

    function delete_question($id){
        include '../conn.php';

        $idSql = $conn->real_escape_string($id);

        if($idSql != ''){
            $stmt = $conn->prepare("DELETE FROM faq WHERE id=?");
            $stmt->bind_param("i",$idSql);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            $data = [
                'status' => 'success',
                'icon' => '<i class="fa-solid fa-circle-check"></i>',
                'message' => 'Question Berhasil Di Hapus'
            ];
            http_response_code(200);
        }else{
            $data = [
                'status' => 'error',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'message' => 'Question Tidak Ditemukan'
            ];
            http_response_code(404);
        }

        return $data;
        
    }
        
    $deleted_question = delete_question($deleteId);
    echo json_encode($deleted_question, JSON_PRETTY_PRINT);
?>