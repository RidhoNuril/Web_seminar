<?php 

function filterEmailRegis($email){
    include '../conn.php';
    $emailSql = $conn->real_escape_string($email);

    $query = $conn->prepare("SELECT email FROM user WHERE email=?");
    $query->bind_param('s',$emailSql);
    $query->execute();
    $resultQuery = $query->get_result();

    if($resultQuery->num_rows > 0){
        $emailValid = false;
    }else{
        $emailValid = true;
    }
    
    $conn->close();
    return $emailValid;
}

$remoteEmail = filterEmailRegis(strip_tags($_POST['email']));
echo json_encode($remoteEmail);




?>