<?php 

function filterTelpEdit($telp, $id){
    include '../conn.php';

    $telpSql = $conn->real_escape_string($telp);
    $idSql = $conn->real_escape_string($id);

    $telpFilterDb = $conn->prepare("SELECT no_telp FROM user WHERE no_telp=?");
    $telpFilterDb->bind_param('s',$telpSql);
    $telpFilterDb->execute();
    $resultTelp = $telpFilterDb->get_result();
    
    $peserta = $conn->prepare("SELECT no_telp, user_id FROM user WHERE user_id=?");
    $peserta->bind_param('i',$idSql);
    $peserta->execute();
    $array = $peserta->get_result()->fetch_assoc();

    if($resultTelp->num_rows > 0){
        $telpValid = false;
        
        if($telpSql == $array['no_telp']){
            $telpValid = true;
        }else{
            $telpValid = false;
        }
    }else{
        $telpValid = true;
    }

    $conn->close();
    return $telpValid;
}

$remoteTelp = filterTelpEdit(strip_tags($_POST['no_telp']), strip_tags($_POST['id']));
echo json_encode($remoteTelp);

?>