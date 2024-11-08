<?php 
header("Content-Type: application/json");

$deleteId = $_REQUEST['id'];

    function delete_event($id){
        include '../conn.php';

        $idSql = $conn->real_escape_string($id);

        if($idSql != ''){

            $event = $conn->prepare("SELECT file_thumbnail FROM seminar WHERE seminar_id=?");
            $event->bind_param("i", $idSql);
            $event->execute();
            $result = $event->get_result();
            $file = $result->fetch_assoc();

            if($result->num_rows > 0){
                if($file['file_thumbnail'] != 'default_thumbnail.png'){
                    $target_dir = "../assets/thumbnail/$file[file_thumbnail]";
                    unlink($target_dir);
                }

                $stmt = $conn->prepare("DELETE FROM seminar WHERE seminar_id=?");
                $stmt->bind_param("i",$idSql);
                $stmt->execute();

                $conn->close();
                
                $data = [
                    'status' => 'success',
                    'icon' => '<i class="fa-solid fa-circle-check"></i>',
                    'message' => 'Seminar Berhasil Dihapus'
                ];
                http_response_code(200);
            }else{
                $data = [
                    'status' => 'error',
                    'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                    'message' => 'Seminar Tidak Ditemukan'
                ];
                http_response_code(404);
            }
            
        }else{
            $data = [
                'status' => 'error',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'message' => 'Seminar Tidak Ditemukan'
            ];
            http_response_code(404);
        }

        return $data;
        
    }
        
    $deleted_event = delete_event($deleteId);
    echo json_encode($deleted_event, JSON_PRETTY_PRINT);
?>