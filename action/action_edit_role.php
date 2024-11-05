<?php 
$role_id = isset($_POST['id']) ? strip_tags($_POST['id']) : '';
$nama_role = isset($_POST['nama_role']) ? strip_tags($_POST['nama_role']) : '';
$permission = isset($_POST['permission']) ? $_POST['permission'] : '';

function update_role($role_id, $nama_role, $permission){
    include '../conn.php';

    $role_idSql = $conn->real_escape_string($role_id);
    $nama_roleSql = $conn->real_escape_string(strtolower($nama_role));
    $permissionSql = $permission != '' ? strtolower(json_encode($permission)) : null;

    if($nama_roleSql != ''){
        $update_role = $conn->prepare("UPDATE role SET nama_role=?, permission=? WHERE role_id=?");
        $update_role->bind_param("ssi", $nama_roleSql, $permissionSql, $role_idSql);
        $update_role->execute();

        $response = [
            'status' => 'success',
            'icon' => '<i class="fa-solid fa-circle-check"></i>',
            'redirect' => '../role',
            'message' => 'Role berhasil diedit'
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

$updated = update_role($role_id, $nama_role, $permission);
echo json_encode($updated, JSON_PRETTY_PRINT);

?>