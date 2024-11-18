<?php 

$question = isset($_POST['question']) ? strip_tags($_POST['question']) : '';
$answer = isset($_POST['answer']) ? strip_tags($_POST['answer']) : '';

function insert_question( $question, $answer){
    include '../conn.php';

    $questionSql = $conn->real_escape_string($question);
    $answerSql = $conn->real_escape_string($answer);

    if($questionSql && $answerSql != ''){
        $insert_question = $conn->prepare("INSERT INTO faq (question, answer) VALUES (?, ?)");
        $insert_question->bind_param("ss", $questionSql, $answerSql);
        $insert_question->execute();

        $response = [
            'status' => 'success',
            'icon' => '<i class="fa-solid fa-circle-check"></i>',
            'redirect' => '../question',
            'message' => 'Question berhasil ditambahkan'
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

$insert = insert_question( $question, $answer);
echo json_encode($insert, JSON_PRETTY_PRINT);
?>