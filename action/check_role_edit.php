<?php

function filterRoleEdit($role_name, $id)
{
    include '../conn.php';

    $role_nameSql = $conn->real_escape_string($role_name);
    $idSql = $conn->real_escape_string($id);

    $countRowRole = $conn->prepare("SELECT nama_role FROM role WHERE nama_role=?");
    $countRowRole->bind_param('s',$role_nameSql);
    $countRowRole->execute();
    $resultCountRow = $countRowRole->get_result();
    
    $filterRoleDb = $conn->prepare("SELECT nama_role, role_id FROM role WHERE role_id=?");
    $filterRoleDb->bind_param('i',$idSql);
    $filterRoleDb->execute();
    $array = $filterRoleDb->get_result()->fetch_assoc();
    
    if($resultCountRow->num_rows > 0){
        $roleValid = false;
        if ($role_nameSql == $array['nama_role']) {
            $roleValid = true;
        }else{
            $roleValid = false;
        }
    }else{
        $roleValid = true;
    }

    $conn->close();
    return $roleValid;
}

$remoteRole = filterRoleEdit(strip_tags($_POST['nama_role']), strip_tags($_POST['id']));

echo json_encode($remoteRole);

?>