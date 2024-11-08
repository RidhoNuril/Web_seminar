<?php 

include '../function.php';

$judul = isset($_POST['judul']) ? strip_tags($_POST['judul']) : '';
$link = isset($_POST['link_meet']) ? strip_tags($_POST['link_meet']) : '';
$waktu = isset($_POST['waktu_seminar']) ? $_POST['waktu_seminar'] : '';
$status = isset($_POST['status_seminar']) ? strip_tags($_POST['status_seminar']) : '';
$file_name =  $_FILES['file_thumbnail']['name'] != '' ? $_FILES['file_thumbnail']['name'] : 'default_thumbnail.png';
$tmp_name =  isset($_FILES['file_thumbnail']['tmp_name']) ? $_FILES['file_thumbnail']['tmp_name'] : '';
 

function create_event( $judul, $file_name, $link, $waktu, $status, $tmp_name){
    include '../conn.php';

    $judulSql = $conn->real_escape_string($judul);
    $statusSql = $conn->real_escape_string($status);
    $linkSql = $link != '' ? $conn->real_escape_string($link) : null;

    if($file_name != 'default_thumbnail.png'){
        $file_name_rand = rand()."-".$file_name;
    }else{
        $file_name_rand = $file_name;
    }
    $target_dir = "../assets/thumbnail/".$file_name_rand;

    if($judulSql && $waktu  && $statusSql != ''){
        $create_event = $conn->prepare("INSERT INTO seminar (user_id, file_thumbnail, judul, link_meet, waktu_seminar, status_seminar) VALUES (?, ?, ?, ?, ?, ?)");
        $create_event->bind_param("isssss", $_SESSION['user'], $file_name_rand, $judulSql, $linkSql, $waktu, $statusSql);
        $create_event->execute();

        if($file_name_rand != 'default_thumbnail.png'){
            move_uploaded_file($tmp_name, $target_dir);
        }

        $response = [
            'status' => 'success',
            'icon' => '<i class="fa-solid fa-circle-check"></i>',
            'redirect' => '../create_events',
            'message' => 'Seminar Berhasil Dibuat'
        ];

    }else{
        $response = [
            'status' => 'error',
            'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
            'message' => 'Semua field wajib diisi'
        ];
    }

    return $response;
}

$insert = create_event( $judul, $file_name, $link, $waktu, $status, $tmp_name);
echo json_encode($insert, JSON_PRETTY_PRINT);
?>