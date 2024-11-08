<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Tambah Role</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/font/fontawesome/css/all.min.css">
</head>

<body>
    <?php
    include 'function.php';

    $user_session = session_profile_user();
    $role_session = session_role();
    $event_id = $_GET['id'];
    $events = form_edit_create_events($event_id);

    if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '')) {
        header('location: ../../login');
        exit;
    }

    if(authorization('create event')){
        header('location: ../home');
        exit;
    }

    if(validation_contributor($event_id)){
        header('location: ../create_events');
        exit;
    }

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
                                    <a href="../create_events" id="btn_back">
                                        Back
                                    </a>
                                </div>
                                <span class="sub-title">Event</span>
                            </div>
                            <form action="../../action/action_edit_event.php" method="POST" id="form_edit">
                                <div class="form_body">
                                    <div class="form_left form_65">
                                        <input type="hidden" name="id" value="<?= $events['seminar_id'] ?>">
                                        <div class="form_group">
                                            <label for="">Judul Seminar</label>
                                            <input type="text" name="judul" id="judul" value="<?= $events['judul'] ?>"
                                                placeholder="Isi Judul Seminar" required />
                                        </div>
                                        <div class="form_group">
                                            <label for="">Waktu Seminar</label>
                                            <input type="datetime-local" name="waktu_seminar" value="<?= $events['waktu_seminar'] ?>" id="waktu_seminar" required/>
                                        </div>
                                        <div class="form_group">
                                            <label for="">Link Meet</label>
                                            <input type="url" name="link_meet" value="<?= $events['link_meet'] ?>" id="link_meet" placeholder="Isi jika link meet sudah ada">
                                        </div>
                                        <div class="form_group">
                                            <label for="">Status Seminar</label>
                                            <select name="status_seminar" id="select_status" required="required">
                                                <option disabled selected>Pilih status</option>
                                                <option value="aktif" <?= $events['status_seminar'] == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                                <option value="tidak aktif" <?= $events['status_seminar'] == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form_right form_35">
                                        <div class="form_group">
                                            <label for="">Thumbnail Seminar</label>
                                            <input type="file" name="file_thumbnail" class="file_thumbnail">
                                            <img src="../../assets/thumbnail/<?= $events['file_thumbnail'] ?>" class="img_thumbnail_td img_thumbnail_max" alt="Thumbnail_image">
                                            <div class="recomended_dimention">
                                                Recomended dimention 1620x1080 pixel
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

            $(".file_thumbnail").change(function() {
                let thumb = $(this).siblings('img');
                let reader = new FileReader();
                
                reader.onload = function(e) {
                    thumb.attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#form_edit').validate({
                messages: {
                    judul: 'Judul Seminar Harus Diisi',
                    waktu_seminar: 'Waktu Seminar Harus Diisi',
                    link_meet: 'Masukkan Url Yang Valid',
                    status_seminar: 'Status Seminar Harus Diisi',
                },
                errorElement: 'small',
                errorClass: 'border-red',
                errorPlacement: function (error, element) {
                    error.appendTo(element.closest('.form_group'));
                },
                submitHandler: function (form) {
                    let method = $(form).attr('method');
                    let url = $(form).attr('action');
                    let data = new FormData(form);
                    $.ajax({
                        url: url,
                        type: method,
                        contentType: false,
                        processData: false,
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
                                }, 1500);
                                setTimeout(function () {
                                    if(response.redirect != ''){
                                        location.href = response.redirect;
                                    }
                                }, 1500);
                                $('#judul').val('');
                                $('#waktu_seminar').val('');
                                $('#link_meet').val('');
                                $('#select_status').empty().append('<option disabled selected>Pilih status</option>');
                            } else {
                                $('.icon_notif').empty().append(response.icon);
                                $('#title_notif').text('Failed !');
                                $('#message_notif').text(response.message);
                                $('.notif').css({ 'background-color': '#c85c57' }).fadeIn(300);
                                setTimeout(function () {
                                    $('.notif').fadeOut(800);
                                }, 1500);
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