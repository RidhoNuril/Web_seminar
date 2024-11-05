<?php 

function filterTelpRegis($telp){
    include '../conn.php';
    $telpSql = $conn->real_escape_string($telp);
    
    $query = $conn->prepare("SELECT no_telp FROM user WHERE no_telp=?");
    $query->bind_param('s',$telpSql);
    $query->execute();
    $resultQuery = $query->get_result();

    if($resultQuery->num_rows > 0){
        $telp = false;
    }else{
        $telp = true;
    }

    $conn->close();
    return $telp;
}

$remoteTelp =  filterTelpRegis(strip_tags($_POST['no_telp']));
echo json_encode($remoteTelp);


?>