<?php 

$nama_role = isset($_POST['nama_role']) ? strip_tags($_POST['nama_role']) : '';
$permission = isset($_POST['permission']) ? $_POST['permission'] : '';

function insert_role( $nama_role, $permission){
    include '../conn.php';

    $nama_roleSql = $conn->real_escape_string(strtolower($nama_role));
    $permissionSql = $permission != '' ? json_encode($permission) : null;

    if($nama_roleSql != ''){
        $insert_role = $conn->prepare("INSERT INTO role (nama_role, permission) VALUES (?, ?)");
        $insert_role->bind_param("ss", $nama_roleSql, $permissionSql);
        $insert_role->execute();

        $response = [
            'status' => 'success',
            'icon' => '<i class="fa-solid fa-circle-check"></i>',
            'redirect' => '../role',
            'message' => 'Role berhasil ditambahkan'
        ];

    }else{
        $response = [
            'status' => 'error',
            'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
            'message' => 'Nama role wajib diisi'
        ];
    }

    return $response;
}

$insert = insert_role( $nama_role, $permission);
echo json_encode($insert, JSON_PRETTY_PRINT);
?>