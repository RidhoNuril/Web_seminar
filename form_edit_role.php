<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Edit Role</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/font/fontawesome/css/all.min.css">
</head>

<body>
    <?php
    include 'function.php';

    $user_session = session_profile_user();
    $role_session = session_role();

    if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '')) {
        header('location: ../../login');
        exit;
    }

    if (authorization('role')) {
        header('location: ../home');
        exit;
    }

    $role_id = isset($_GET['id']) ? $_GET['id'] : '';
    $role_edit = form_edit_role($role_id);

    ?>
    <div class="loading_wrapper">
        <div class="loading">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
    </div>
    <div class="notif">
        <div class="box_notif">
            <div class="icon_notif"></div>
            <div class="text_notif">
                <div class="wrap_message">
                    <span id="title_notif"></span>
                    <span id="message_notif"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="container_page">
        <?php
        include 'component/navbar.php';
        ?>
        <div class="main">
            <?php
            include 'component/sidebar.php';
            ?>
            <div class="content">
                <div class="container_content">
                    <div class="container_form">
                        <div class="form form_dashboard form_shadow">
                            <div class="form_head">
                                <div class="d-flex">
                                    <h1>Edit</h1>
                                    <a href="../role" id="btn_back">
                                        Back
                                    </a>
                                </div>
                                <span class="sub-title">Role</span>
                            </div>
                            <form action="../../action/action_edit_role.php" method="POST" id="form_edit">
                                <div class="form_body_single">
                                    <input type="hidden" id="role_id" name="id" value="<?= $role_edit['role_id'] ?>">
                                    <div class="form_group">
                                        <label for="">Nama Role</label>
                                        <input type="text" name="nama_role" id="nama_role"
                                            value="<?= $role_edit['nama_role'] ?>"
                                            placeholder="Isi Nama Anda Cth : Agus Salim" required />
                                    </div>
                                    <div class="form_group">
                                        <label for="">Permission</label>
                                        <div class="input_checkbox">
                                            <div class="checkbox">
                                                <input type="checkbox" name="permission[]" value="user" id="user"
                                                    <?= checked_checkbox_permission('user', $role_edit['role_id']) == true ? 'checked' : '' ?>>
                                                <label for="user">User</label>
                                            </div>
                                            <div class="checkbox">
                                                <input type="checkbox" name="permission[]" value="role" id="role"
                                                    <?= checked_checkbox_permission('role', $role_edit['role_id']) == true ? 'checked' : '' ?>>
                                                <label for="role">Role</label>
                                            </div>
                                            <div class="checkbox">
                                                <input type="checkbox" name="permission[]" value="create event" id="create_event"
                                                    <?= checked_checkbox_permission('create event', $role_edit['role_id']) == true ? 'checked' : '' ?>>
                                                <label for="create_event">Create Event</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form_bottom">
                                    <button type="submit" class="btn_update">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/js/jquery.min.js"></script>
    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('.loading_wrapper').fadeOut(500);
            }, 500);
        });
    </script>
    <script src="../../assets/js/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {

            $('.btn_hamburger').click(function () {
                let sidebar = $('.sidebar').css('margin-left');
                console.log(sidebar);
                if (sidebar == '0px') {
                    $('.sidebar').removeClass('open');
                    $('.sidebar').addClass('close');
                    $('.content').width('100%');
                } else {
                    $('.sidebar').removeClass('close');
                    $('.sidebar').addClass('open');
                    $('.content').width('calc(100% - 15rem)');
                }
            });

            $('.button_dropdown').click(function(){
                let display = $('.dropdown_profile').css('display');
                if(display == 'none'){
                    $('.dropdown_profile').fadeIn(200);
                }else{
                    $('.dropdown_profile').fadeOut(0);
                }
            });

            $('#form_edit').validate({
                rules: {
                    nama_role: {
                        remote: {
                            url: '../../action/check_role_edit.php',
                            type: 'POST',
                            data: {id: $('#role_id').val()}
                        }
                    }
                },
                messages: {
                    nama_role: {
                        required: 'Nama role harus diisi',
                        remote: 'Nama role sudah ada'
                    }
                },
                errorElement: 'small',
                errorClass: 'border-red',
                errorPlacement: function (error, element) {
                    error.appendTo(element.closest('.form_group'));
                },
                submitHandler: function (form) {
                    let method = $(form).attr('method');
                    let url = $(form).attr('action');
                    let data = $(form).serialize();
                    $.ajax({
                        url: url,
                        type: method,
                        data: data,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status == 'success') {
                                $('.icon_notif').empty().append(response.icon);
                                $('#title_notif').text('Success !');
                                $('#message_notif').text(response.message);
                                $('.notif').css({ 'background-color': '#74b574e2' }).fadeIn(300);
                                setTimeout(function () {
                                    $('.notif').fadeOut(800);
                                }, 1500);
                                $('#nama_role').val('');
                                setTimeout(function () {
                                    if (response.redirect != '') {
                                        location.href = response.redirect;
                                    }
                                }, 1500);
                            } else {
                                $('.icon_notif').empty().append(response.icon);
                                $('#title_notif').text('Failed !');
                                $('#message_notif').text(response.message);
                                $('.notif').css({ 'background-color': '#c85c57' }).fadeIn(300);
                                setTimeout(function () {
                                    $('.notif').fadeOut(800);
                                }, 2000);
                            }
                        },
                        error: function (response) {
                            $('.icon_notif').empty().append(response.responseJSON.icon);
                            $('#title_notif').text('Failed !');
                            $('#message_notif').text(response.responseJSON.message);
                            $('.notif').css({ 'background-color': '#c85c57' }).fadeIn(300);
                            setTimeout(function () {
                                $('.notif').fadeOut(800);
                            }, 2000);
                        }
                    });
                }
            });

        });
    </script>
</body>

</html