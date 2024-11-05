<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="font/fontawesome/css/all.min.css">
</head>

<body>
    <?php

    include 'function.php';

    if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '') == false) {
        header('location: dashboard/home');
        exit;
    }

    $csrf_token = create_csrf_token(!empty($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '');

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
                    <span style="display:block; font-weight:bold;" id="title_notif"></span>
                    <span id="message_notif"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form_login">
            <div class="form_head">
                <div class="btn_beranda">
                    <a href="../web_seminar"><i class="fa-solid fa-arrow-left"></i> Beranda</a>
                </div>
                <h1>Masuk</h1>
                <span class="sub-title">Akun Anda</span>
            </div>
            <form action="action/action_login.php" method="POST" id="form_login">
                <input type="hidden" name="token" value="<?= $csrf_token ?>">
                <div class="form_group">
                    <label for="">Email</label>
                    <input type="email" name="email" id="email" placeholder="Isi Email Anda" required />
                </div>
                <div class="form_group">
                    <label for="">Password</label>
                    <div class="input_password">
                        <input type="password" name="password" id="password" class="radius_pw"
                            placeholder="Isi Password Anda" required>
                        <div class="eye_password">
                            <i class="fa-solid fa-eye-slash eye_slash"></i>
                        </div>
                    </div>
                </div>
                <div class="form_bottom">
                    <button type="submit" class="btn_auth">Masuk</button>
                    <div class="question">
                        <p>Belum Memiliki akun? <a href="registrasi">Daftar Disini</a></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('.loading_wrapper').fadeOut(500);
            }, 500);
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.eye_password').click(function () {
                let input = $(this).siblings('#password');
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

            $('#form_login').validate({
                messages: {
                    email: {
                        required: 'Email Harus Diisi',
                        email: 'Format Email Harus Example@gmail.com'
                    },
                    password: 'Password Harus Diisi'
                },
                errorElement: 'small',
                errorClass: 'border-red',
                errorPlacement: function (error, element) {
                    error.appendTo(element.closest('.form_group'));
                },
                submitHandler: function (form) {
                    let url = $(form).attr('action');
                    let method = $(form).attr('method');
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
                                }, 2000);


                                setTimeout(function () {
                                    if (response.redirect != '') {
                                        location.href = response.redirect;
                                    } else {
                                        location.reload();
                                    }
                                }, 3000);
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

</html>