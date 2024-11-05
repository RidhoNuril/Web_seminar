<?php 
header('Content-Type: application/json');
include '../function.php';

function login($email, $password, $csrf_token){
    include '../conn.php';

    $emailSql = $conn->real_escape_string($email);
    $passwordSql = $conn->real_escape_string($password);
    $csrf_tokenSql = $conn->real_escape_string($csrf_token);

    if(!empty($_SESSION['csrf_token']) && $csrf_tokenSql == $_SESSION['csrf_token']){

        if($emailSql && $passwordSql != ''){
            $stmt = $conn->prepare("SELECT user_id, password, role_id FROM user WHERE email=?");
            $stmt->bind_param('s',$emailSql);
            $stmt->execute();
            $result = $stmt->get_result();
            $array = $result->fetch_assoc();
    
            if($result->num_rows > 0){

                if(password_verify( $passwordSql, $array['password'])){
                    $auth_token = create_auth_token();
                    
                    $tokenDb = $conn->prepare("UPDATE user SET auth_token=? WHERE email=?");
                    $tokenDb->bind_param("ss", $auth_token ,$emailSql);
                    $tokenDb->execute();
                    
                    $_SESSION['user'] = $array['user_id'];
                    $_SESSION['role'] = $array['role_id'];

                    $response = [
                        'status' => 'success',
                        'message' => 'Email dan password sesuai',
                        'icon' => '<i class="fa-solid fa-circle-check"></i>',
                        'redirect' => '../web_seminar'
                    ];
                    http_response_code(200);

                }else{
                    $response = [
                        'status' => 'error',
                        'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                        'message' => 'Email dan password Tidak sesuai'
                    ];
                    http_response_code(401);
                }
            }else{
                $response = [
                    'status' => 'error',
                    'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                    'message' => 'Email Tidak Terdaftar',
                ];
    
                http_response_code(404);
            }
            
            
        }else{
            $response = [
                'status' => 'error',
                'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
                'message' => 'Semua Field Wajib Diisi'
            ];
            http_response_code(400);
        }

    }else{
        $response = [
            'status' => 'error',
            'icon' => '<i class="fa-solid fa-circle-exclamation"></i>',
            'message' => 'Token Tidak Valid'
        ];

        http_response_code(403);
    }

    return $response;
}

$email = isset($_POST['email']) ? strip_tags($_POST['email']) : '';
$password = isset($_POST['password']) ? strip_tags($_POST['password']) : '';
$token = isset($_POST['token']) ? strip_tags($_POST['token']) : '';

$login = login($email, $password, $token);

echo json_encode($login, JSON_PRETTY_PRINT);

?>
