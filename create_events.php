<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/font/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/datatables.min.css">
    <link rel="stylesheet" href="../assets/css/responsive.dataTables.min.css">
</head>
<?php
include 'function.php';

$user_session = session_profile_user();
$role_session = session_role();
$event_created = event_created();

if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '')) {
    header('location: ../login');
    exit;
}

if (authorization('create event')) {
    header('location: home');
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
                    <span id="title_notif"></span>
                    <span id="message_notif"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="dialog_background">
        <div class="wrap_dialog">
            <div class="dialog_confirm">
                <form action="../action_delete_event.php" id="ajax-delete">
                    <div class="dialog_header">
                        <h3>Hapus Seminar</h3>
                        <button type="button" class="btn_close_dialog">
                            <i class="fa-solid fa-x"></i>
                        </button>
                    </div>
                    <div class="dialog_body">
                        Apakah anda yakin ingin menghapus seminar?
                    </div>
                    <div class="dialog_footer">
                        <button type="button" class="btn_close_dialog">Tutup</button>
                        <button type="submit">Ya, Hapus</button>
                    </div>
                </form>
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
                        <span>Event Created</span>
                        <a href="create_events/create">Create Event</a>
                    </div>
                    <div class="wrap_table">
                        <table id="table" class="nowrap" width="100%" style="max-width: 100%;">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Detail Event</th>
                                    <th>Link Meet</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($event_created as $row) {
                                    $date = isset($row['waktu_seminar']) ? date_create($row['waktu_seminar']) : '';
                                    ?>
                                    <tr>
                                        <td class="td_image vertical_align_top"><img
                                                src="../assets/thumbnail/<?= $row['thumbnail'] ?>" class="img_thumbnail_td"
                                                alt="Thumbnail image"></td>
                                        <td class="vertical_align_top">
                                            <p class="judul_event">
                                                <?= $row['judul'] ?>
                                            </p>
                                            <span class="time">
                                                <?= date_format($date, 'd F Y, H:i') ?>
                                            </span>
                                            <span
                                                class="tag tag_status <?= $row['status_seminar'] == 'aktif' ? 'tag_green' : 'tag_red' ?>"><?= ucwords($row['status_seminar']) ?></span>
                                        </td>
                                        <td class="vertical_align_top">
                                            <?= $row['link_meet'] != null ? $row['link_meet'] : 'Link meet belum diset' ?>
                                        </td>
                                        <td class="vertical_align_top">
                                            <a href="create_events/<?= $row['seminar_id'] ?>" class="btn_td"><i
                                                    class="fa-solid fa-pen-to-square"></i></a>
                                            <button class="btn_delete btn_td btn_red" data-id="<?= $row['seminar_id'] ?>"><i
                                                    class="fa-solid fa-trash-can"></i></button>
                                            <a href="create_events/<?= $row['seminar_id'] ?>/participants"
                                                class="btn_td btn_grey"><i class="fa-solid fa-users"></i>
                                                <?= count_participants_event($row['seminar_id']) ?></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/datatables.min.js"></script>
    <script src="../assets/js/dataTables.responsive.min.js"></script>
    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('.loading_wrapper').fadeOut(500);
            }, 500);
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#table').DataTable({
                order: []
            });

            $('.button_dropdown').click(function () {
                let display = $('.dropdown_profile').css('display');
                if (display == 'none') {
                    $('.dropdown_profile').fadeIn(200);
                } else {
                    $('.dropdown_profile').fadeOut(0);
                }
            });

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

            $('#table').on('click', '.btn_delete', function () {
                let confirm = $('.dialog_background');
                let id = $(this).data('id');
                let form = $('#ajax-delete');
                let row = $(this).closest('tr');
                form.attr('action', '../action/action_delete_event.php?id=' + id);
                form.data('row',row);
                confirm.fadeIn(300);
            });

            $('.btn_close_dialog').click(function () {
                let confirm = $('.dialog_background');
                let form = $('#ajax-delete');
                form.attr('action', 'action/action_delete_event.php');
                confirm.fadeOut(300);
            });

            $('#ajax-delete').submit(function (e) {
                e.preventDefault();
                let url = $(this).attr('action');
                let row = $(this).data('row');
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    beforeSend: function () {
                        $('.dialog_background').fadeOut(300);
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            row.remove();
                            $('.icon_notif').empty().append(response.icon);
                            $('#title_notif').text('Success !');
                            $('#message_notif').text(response.message);
                            $('.notif').css({ 'background-color': '#74b574e2' }).fadeIn(300);
                            setTimeout(function () {
                                $('.notif').fadeOut(800);
                            }, 1500);
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
            });
        });
    </script>
</body>

</html>