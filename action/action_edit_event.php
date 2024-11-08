<?php 
include '../function.php';

$id = isset($_POST['id']) ? strip_tags($_POST['id']) : '';
$judul = isset($_POST['judul']) ? strip_tags($_POST['judul']) : '';
$link = isset($_POST['link_meet']) ? strip_tags($_POST['link_meet']) : '';
$waktu = isset($_POST['waktu_seminar']) ? $_POST['waktu_seminar'] : '';
$status = isset($_POST['status_seminar']) ? strip_tags($_POST['status_seminar']) : '';
$file_name = $_FILES['file_thumbnail']['name'] != '' ? $_FILES['file_thumbnail']['name'] : '';
$tmp_name = isset($_FILES['file_thumbnail']['tmp_name']) ? $_FILES['file_thumbnail']['tmp_name'] : '';

function update_event($id, $file_name, $judul, $link, $waktu, $status, $tmp_name){
    include '../conn.php';

    $idSql = $conn->real_escape_string($id);
    $judulSql = $conn->real_escape_string($judul);
    $linkSql = $link != '' ? $conn->real_escape_string($link) : null;
    $statusSql = $conn->real_escape_string($status);

    if($judulSql && $waktu  && $statusSql != ''){
        $event = $conn->prepare("SELECT file_thumbnail FROM seminar WHERE seminar_id=?");
        $event->bind_param("i", $idSql);
        $event->execute();
        $result = $event->get_result();
        $file = $result->fetch_assoc();

        if($result->num_rows > 0){

            if($file_name != ''){
                if($file['file_thumbnail'] != 'default_thumbnail.png'){
                    $delete_dir = "../assets/thumbnail/$file[file_thumbnail]";
                    unlink($delete_dir);
                }

                $file_name_update = rand()."-".$file_name;
                $update_dir = "../assets/thumbnail/".$file_name_update;
                move_uploaded_file($tmp_name, $update_dir);
            }else{
                $file_name_update = $file['file_thumbnail'];
            }

            $create_event = $conn->prepare("UPDATE seminar SET file_thumbnail=?, judul=?, link_meet=?, waktu_seminar=?, status_seminar=? WHERE seminar_id=?");
            $create_event->bind_param("sssssi", $file_name_update, $judulSql, $linkSql, $waktu, $statusSql, $idSql);
            $create_event->execute();

            $response = [
                'status' => 'success',
                'icon' => '<i class="fa-solid fa-circle-check"></i>',
                'redirect' => '../create_events',
                'message' => 'Seminar berhasil diubah'
            ];

        }else{
            $response = [
                'status' => 'error',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'redirect' => '../create_events',
                'message' => 'Seminar Tidak Ditemukan'
            ];
        }

        

    }else{
        $response = [
            'status' => 'error',
            'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
            'message' => 'Semua field wajib diisi'
        ];
    }

    return $response;
}

$update = update_event($id, $file_name, $judul, $link, $waktu, $status, $tmp_name);
echo json_encode($update, JSON_PRETTY_PRINT);

?>