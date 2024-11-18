<?php

session_start();

function create_csrf_token($token)
{
    if (empty($token)) {
        $token = bin2hex(random_bytes(20));
        $_SESSION['csrf_token'] = $token;
    }
    return $_SESSION['csrf_token'];
}

function create_auth_token()
{

    if (empty($_SESSION['auth_token'])) {
        $_SESSION['auth_token'] = bin2hex(random_bytes(20));
    }

    return $_SESSION['auth_token'];
}

function auth_check_token($token)
{
    include 'conn.php';

    if ($token != '') {
        $auth_token = $conn->prepare("SELECT auth_token FROM user WHERE auth_token=?");
        $auth_token->bind_param('s', $token);
        $auth_token->execute();
        $result = $auth_token->get_result()->fetch_assoc();

        $resultToken = !empty($result['auth_token']) ? $result['auth_token'] : '';

        if ($resultToken == $token) {
            $response = false;
        } else {
            $response = true;
        }
    } else {
        $response = true;
    }

    $conn->close();
    return $response;
}

function session_profile_user()
{
    include 'conn.php';

    $stmt = $conn->prepare("SELECT user_id,nama,password,kelas,asal_sekolah,email,no_telp,provinsi,kabupaten,kecamatan,kelurahan FROM user WHERE user_id=?");
    $stmt->bind_param("i", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $conn->close();
    return $result;
}

function session_role()
{
    include 'conn.php';

    $stmt = $conn->prepare("SELECT * FROM role WHERE role_id=?");
    $stmt->bind_param("i", $_SESSION['role']);
    $stmt->execute();
    $result = $stmt->get_result();
    $array = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        $response = [
            'nama_role' => $array['nama_role'] ? $array['nama_role'] : '',
            'permission' => $array['permission'] != null ? json_decode($array['permission']) : ''
        ];

    } else {
        $response = [];
    }

    $conn->close();
    return $response;
}

function authorization($permission)
{
    $role = session_role();

    if ($role != [] && $role['permission'] != '') {
        foreach ($role['permission'] as $roles) {
            if ($roles == $permission) {
                $response = false;
                break;
            }
            $response = true;
        }
    } else {
        $response = true;
    }

    return $response;
}

function form_edit_user($user_id)
{
    include 'conn.php';
    $id = $conn->real_escape_string($user_id);

    if ($id != '') {
        $stmt = $conn->prepare("SELECT * FROM user WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response = $result->fetch_assoc();
        } else {
            $response = '';
            echo '<script>alert("User tidak ditemukan"); location.href = "../user"</script>';
        }
    } else {
        $response = '';
        echo '<script>alert("User tidak ditemukan"); location.href = "../user"</script>';
    }

    $conn->close();
    return $response;
}


function form_edit_role($role_id)
{
    include 'conn.php';
    $id = $conn->real_escape_string($role_id);

    if ($id != '') {
        $stmt = $conn->prepare("SELECT * FROM role WHERE role_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $array = $result->fetch_assoc();

        if ($result->num_rows > 0) {
            $response = [
                'role_id' => $array['role_id'],
                'nama_role' => $array['nama_role'],
                'permission' => $array['permission'] != null ? json_decode($array['permission']) : ''
            ];
        } else {
            $response = '';
            echo '<script>alert("Role tidak ditemukan"); location.href = "../role"</script>';
        }

    } else {
        $response = '';
        echo '<script>alert("Role tidak ditemukan"); location.href = "../role"</script>';
    }

    $conn->close();
    return $response;
}

function checked_checkbox_permission($permission, $role_id)
{
    $role = form_edit_role($role_id);

    if ($role['permission'] != '' && $role['permission'] != []) {
        foreach ($role['permission'] as $permission_role) {
            if ($permission_role == $permission) {
                $response = true;
                break;
            }
            $response = false;
        }
    } else {
        $response = false;
    }

    return $response;
}

function form_edit_create_events($event_id)
{
    include 'conn.php';
    $id = $conn->real_escape_string($event_id);

    if ($id != '') {
        $stmt = $conn->prepare("SELECT * FROM seminar WHERE seminar_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response = $result->fetch_assoc();
        } else {
            header('location: ../create_events');
        }
    } else {
        header('location: ../create_events');
    }



    $conn->close();
    return $response;
}

function get_all_role()
{
    include 'conn.php';

    $stmt = $conn->prepare("SELECT * FROM role");
    $stmt->execute();

    $results = $stmt->get_result();

    if ($results->num_rows > 0) {
        while ($row = $results->fetch_assoc()) {
            $role[] = [
                'role_id' => $row['role_id'],
                'nama_role' => $row['nama_role'],
                'permission' => $row['permission'] != null ? json_decode($row['permission']) : ''
            ];
        }
    } else {
        $role = [];
    }

    $conn->close();
    return $role;
}

function event_user()
{
    include 'conn.php';
    $user = session_profile_user();

    $stmt = $conn->prepare("SELECT * FROM peserta_seminar LEFT JOIN seminar ON peserta_seminar.seminar_id = seminar.seminar_id WHERE email_peserta=? ORDER BY waktu_reg DESC");
    $stmt->bind_param("s", $user['email']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = [
                'thumbnail' => $row['file_thumbnail'],
                'judul' => $row['judul'],
                'waktu_seminar' => isset($row['waktu_seminar']) ? date_create($row['waktu_seminar']) : '',
                'status_seminar' => $row['status_seminar'],
                'link_meet' => $row['link_meet'],
                'email_peserta' => $row['email_peserta'],
                'status' => $row['status']
            ];
        }
    } else {
        $response = [];
    }

    return $response;
}

function event_created()
{
    include 'conn.php';
    $user = session_profile_user();

    $stmt = $conn->prepare("SELECT * FROM seminar WHERE user_id=? ORDER BY waktu_seminar");
    $stmt->bind_param("i", $user['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = [
                'seminar_id' => $row['seminar_id'],
                'thumbnail' => $row['file_thumbnail'],
                'judul' => $row['judul'],
                'link_meet' => $row['link_meet'],
                'waktu_seminar' => $row['waktu_seminar'],
                'status_seminar' => $row['status_seminar'],
            ];
        }
    } else {
        $response = [];
    }

    return $response;
}

function count_participants_event($seminar_id)
{
    include 'conn.php';
    $stmt = $conn->prepare("SELECT COUNT(seminar_id) AS jumlah_peserta FROM peserta_seminar WHERE seminar_id=?");
    $stmt->bind_param("i", $seminar_id);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    return $result['jumlah_peserta'];
}

function show_participants_event($seminar_id)
{
    include 'conn.php';


    $stmt = $conn->prepare("SELECT no_reg, email_peserta, waktu_reg, status FROM peserta_seminar WHERE seminar_id=? ORDER BY waktu_reg");
    $stmt->bind_param("i", $seminar_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = [
                'no_reg' => $row['no_reg'],
                'email_peserta' => $row['email_peserta'],
                'waktu_reg' => $row['waktu_reg'],
                'status' => $row['status']
            ];
        }
    } else {
        $response = [];
    }

    return $response;
}

function get_all_question(){
    include 'conn.php';

    $stmt = $conn->prepare("SELECT * FROM faq");
    $stmt->execute();
    $results = $stmt->get_result();

    if($results->num_rows > 0){
        while($row = $results->fetch_assoc()){
            $response[] = [
                'id' => $row['id'],
                'question' => $row['question'],
                'answer' => $row['answer']
            ];
        }
    }else{
        $response = [];
    }

    return $response;
}

function form_edit_question($question_id){
    include 'conn.php';

    $stmt = $conn->prepare("SELECT * FROM faq WHERE id=?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $response = $result->fetch_assoc();
    }else{
        header('location: ../question');
    }

    return $response;
}

function validation_contributor($seminar_id)
{
    include 'conn.php';

    $user_permission = $conn->prepare("SELECT user_id FROM seminar WHERE seminar_id=?");
    $user_permission->bind_param("i", $seminar_id);
    $user_permission->execute();
    $user_result = $user_permission->get_result();
    $user = $user_result->fetch_assoc();

    if ($user_result->num_rows > 0) {
        if ($_SESSION['user'] == $user['user_id']) {
            $response = false;
        } else {
            $response = true;
        }
    } else {
        $response = true;
    }

    return $response;
}

function event_upcoming()
{
    include 'conn.php';

    $status = 'aktif';
    $stmt = $conn->prepare("SELECT seminar_id, file_thumbnail, user.nama, judul, waktu_seminar FROM seminar LEFT JOIN user ON seminar.user_id = user.user_id WHERE status_seminar=? ORDER BY waktu_seminar DESC LIMIT 4");
    $stmt->bind_param("s", $status);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $date_create = date_create($row['waktu_seminar']);

            $response[] = [
                'seminar_id' => $row['seminar_id'],
                'thumbnail' => $row['file_thumbnail'],
                'nama_contributor' => $row['nama'],
                'judul_seminar' => $row['judul'],
                'waktu_seminar' => date_format($date_create, "H:i"),
                'tanggal_seminar' => date_format($date_create, "d M Y"),
            ];
        }
    } else {
        $response = [];
    }

    return $response;
}

function event_upcoming_all()
{
    include 'conn.php';

    $status = 'aktif';
    $stmt = $conn->prepare("SELECT seminar_id, file_thumbnail, user.nama, judul, waktu_seminar FROM seminar LEFT JOIN user ON seminar.user_id = user.user_id WHERE status_seminar=? ORDER BY waktu_seminar DESC");
    $stmt->bind_param("s", $status);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $date_create = date_create($row['waktu_seminar']);

            $response[] = [
                'seminar_id' => $row['seminar_id'],
                'thumbnail' => $row['file_thumbnail'],
                'nama_contributor' => $row['nama'],
                'judul_seminar' => $row['judul'],
                'waktu_seminar' => date_format($date_create, "H:i"),
                'tanggal_seminar' => date_format($date_create, "d M Y"),
            ];
        }
    } else {
        $response = [];
    }

    return $response;
}

?>