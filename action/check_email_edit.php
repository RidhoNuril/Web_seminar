
<?php

function filterEmailEdit($id, $email)
{
    include '../conn.php';

    $idSql = $conn->real_escape_string($id);
    $emailSql = $conn->real_escape_string($email);

    $emailFilterDb = $conn->prepare("SELECT email FROM user WHERE email=?");
    $emailFilterDb->bind_param('s',$emailSql);
    $emailFilterDb->execute();
    $resultEmail = $emailFilterDb->get_result();
    
    $peserta = $conn->prepare("SELECT email, user_id FROM user WHERE user_id=?");
    $peserta->bind_param('i',$idSql);
    $peserta->execute();
    $array = $peserta->get_result()->fetch_assoc();
    
    if($resultEmail->num_rows > 0){
        $emailValid = false;
        if ($emailSql == $array['email']) {
            $emailValid = true;
        }else{
            $emailValid = false;
        }
    }else{
        $emailValid = true;
    }

    $conn->close();
    return $emailValid;
}

$remoteEmail = filterEmailEdit(strip_tags($_POST['id']), strip_tags($_POST['email']));

echo json_encode($remoteEmail);
?>