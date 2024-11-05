
<?php 

include '../function.php';

$no_reg = isset($_POST['no_reg']) ? strip_tags($_POST['no_reg']) : '';
$status = isset($_POST['status']) ? strip_tags($_POST['status']) : '';

function set_status($no_reg, $status){
    include '../conn.php';

    $no_regSql = $conn->real_escape_string($no_reg);
    $statusSql = $conn->real_escape_string($status);

    if($no_regSql && $statusSql != ''){
        $set_status = $conn->prepare("UPDATE peserta_seminar SET status=? WHERE no_reg=?");
        $set_status->bind_param("ss", $statusSql, $no_regSql);
        $set_status->execute();

        if($statusSql == 'diterima'){
            $class = 'tag_green';
        }else{
            $class = 'tag_red';
        }

        $response = [
            'status' => 'success',
            'icon' => '<i class="fa-solid fa-circle-check"></i>',
            'message' => 'Status berhasil diset',
            'span_status' => '<span class="tag tag_status '.$class.'">'.$statusSql.'</span>'
        ];

    }else{
        $response = [
            'status' => 'error',
            'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
            'message' => 'Data tidak valid'
        ];
    }

    return $response;
}

$updated = set_status($no_reg, $status);
echo json_encode($updated, JSON_PRETTY_PRINT);

?>