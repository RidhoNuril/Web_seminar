<?php

function filterRoleTambah($role_name)
{
    include '../conn.php';

    $role_nameSql = $conn->real_escape_string($role_name);

    $countRowRole = $conn->prepare("SELECT nama_role FROM role WHERE nama_role=?");
    $countRowRole->bind_param('s',$role_nameSql);
    $countRowRole->execute();
    $resultCountRow = $countRowRole->get_result();
    
    if($resultCountRow->num_rows > 0){
        $roleValid = false;
    }else{
        $roleValid = true;
    }

    $conn->close();
    return $roleValid;
}

$remoteRole = filterRoleTambah(strip_tags($_POST['nama_role']));

echo json_encode($remoteRole);
?>