<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../font/fontawesome/css/all.min.css">
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
                        <span>Profile</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('.loading_wrapper').fadeOut(500);
            }, 500);
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.btn_hamburger').click(function() {
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
        });
    </script>
</body>

</html>