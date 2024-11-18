<?php 

$id = isset($_POST['id']) ? strip_tags($_POST['id']) : '';
$question = isset($_POST['question']) ? strip_tags($_POST['question']) : '';
$answer = isset($_POST['answer']) ? strip_tags($_POST['answer']) : '';

function update_question($id, $question, $answer){
    include '../conn.php';

    $idSql = $conn->real_escape_string($id);
    $questionSql = $conn->real_escape_string($question);
    $answerSql = $conn->real_escape_string($answer);

    if($questionSql && $answerSql != ''){
        $update_question = $conn->prepare("UPDATE faq SET question=?, answer=? WHERE id=?");
        $update_question->bind_param("ssi", $questionSql, $answerSql, $idSql);
        $update_question->execute();

        $response = [
            'status' => 'success',
            'icon' => '<i class="fa-solid fa-circle-check"></i>',
            'redirect' => '../question',
            'message' => 'Question berhasil diedit'
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

$update = update_question($id, $question, $answer);
echo json_encode($update, JSON_PRETTY_PRINT);
?>