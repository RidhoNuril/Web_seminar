<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Ubah Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dataTables.min.css">
    <link rel="stylesheet" href="../assets/font/fontawesome/css/all.min.css">
</head>
<?php
include 'function.php';

$user_session = session_profile_user();
$role_session = session_role();

if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '')) {
    header('location: ../login');
    exit;
}
?>

<body>
    <div class="loading_wrapper">
        <div class="loading">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
    </div>
    <div class="notif">
        <div class="box_notif">
            <div class="icon_notif">
            </div>
            <div class="text_notif">
                <div class="wrap_message">
                    <span style="display:block; font-weight:bold;" id="title_notif"></span>
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
                    <div class="title_content">
                        <span>Ubah Password</span>
                    </div>
                    <div class="form form_dashboard form_shadow">
                        <form action="../action/action_reset_password.php" method="POST" id="form_edit">
                            <div class="form_body_single">
                                <div class="form_group">
                                    <label for="">Password Lama</label>
                                    <div class="input_password">
                                        <input type="password" name="password_lama" id="password_lama" class="password radius_pw" required/>
                                        <div class="eye_password">
                                            <i class="fa-solid fa-eye-slash eye_slash"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form_group">
                                    <label for="">Password Baru</label>
                                    <div class="input_password">
                                        <input type="password" name="password_baru" id="password_baru" class="password radius_pw" required/>
                                        <div class="eye_password">
                                            <i class="fa-solid fa-eye-slash eye_slash"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form_group">
                                    <label for="">Konfirmasi Password</label>
                                    <div class="input_password">
                                        <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="password radius_pw" required/>
                                        <div class="eye_password">
                                            <i class="fa-solid fa-eye-slash eye_slash"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_bottom">
                                <button type="submit" class="btn_tambah">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jquery.validate.min.js"></script>
    <script src="../assets/js/datatables.min.js"></script>
    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('.loading_wrapper').fadeOut(500);
            }, 500);
        });
    </script>
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

            $('.eye_password').click(function () {
                let input = $(this).siblings('.password');
                if (input.attr('type') == 'password') {
                    input.attr('type', 'text');
                    $(this).children('i').removeClass('fa-eye-slash');
                    $(this).children('i').addClass('fa-eye');
                } else {
                    input.attr('type', 'password');
                    $(this).children('i').removeClass('fa-eye');
                    $(this).children('i').addClass('fa-eye-slash');
                }
            });

            $('#form_edit').validate({
                rules: {
                    konfirmasi_password: {
                        equalTo: "#password_baru"
                    }
                },
                messages: {
                    password_lama: 'Password lama wajib diisi',
                    password_baru: 'Password baru wajib diisi',
                    konfirmasi_password: {
                        required: 'Konfirmasi password wajib diisi',
                        equalTo: 'Konfirmasi password tidak sesuai'
                    }
                },
                errorElement: 'small',
                errorClass: 'border-red',
                errorPlacement: function (error, element) {
                    error.appendTo(element.closest('.form_group'));
                },
                submitHandler: function(form){
                    let url = $(form).attr('action');
                    let method = $(form).attr('method');
                    let data = $(form).serialize();

                    $.ajax({
                        url: url,
                        type: method,
                        data: data,
                        dataType: 'JSON',
                        success: function (response) {
                            if (response.status == 'success') {
                                $('.icon_notif').empty().append(response.icon);
                                $('#title_notif').text('Success !');
                                $('#message_notif').text(response.message);
                                $('.notif').css({ 'background-color': '#74b574e2' }).fadeIn(300);
                                setTimeout(function () {
                                    $('.notif').fadeOut(800);
                                }, 2000);
                                $('#password_lama').val('');
                                $('#password_baru').val('');
                                $('#konfirmasi_password').val('');
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

            $('.button_dropdown').click(function () {
                let display = $('.dropdown_profile').css('display');
                if (display == 'none') {
                    $('.dropdown_profile').fadeIn(200);
                } else {
                    $('.dropdown_profile').fadeOut(0);
                }
            });
        });
    </script>
</body>

</html>