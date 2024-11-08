<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Data Role</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/font/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/datatables.min.css">
    <link rel="stylesheet" href="../assets/css/responsive.dataTables.min.css">
</head>
<?php

include 'function.php';

$user_session = session_profile_user();
$role_session = session_role();
$role_get = get_all_role();

if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '')) {
    header('location: ../login');
    exit;
}

if (authorization('role')) {
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
                <form action="../action/action_delete_role.php" id="ajax-delete">
                    <div class="dialog_header">
                        <h3>Hapus Role</h3>
                        <button type="button" class="btn_close_dialog">
                            <i class="fa-solid fa-x"></i>
                        </button>
                    </div>
                    <div class="dialog_body">
                        Apakah anda yakin ingin menghapus role?
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
                        <span>Data Roles</span>
                        <a href="role/add">Add Role</a>
                    </div>
                    <div class="wrap_table">
                        <table id="table" class="responsive nowrap" width="100%" style="max-width: 100%;">
                            <thead>
                                <tr>
                                    <th data-priority="1">Role</th>
                                    <th>Permission</th>
                                    <th data-priority="2">Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($role_get as $row) { ?>
                                    <tr>
                                        <td><?= $row['nama_role']; ?></td>
                                        <td>
                                            <?php
                                            if ($row['permission'] != '') {
                                                foreach ($row['permission'] as $permission) { ?>
                                                    <span class="tag"><?= ucfirst($permission) ?></span>
                                                <?php }
                                            } else { ?>
                                                <span class="tag tag_red">Tidak ada</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="role/<?= $row['role_id'] ?>" class="btn_td"><i
                                                    class="fa-solid fa-pen-to-square"></i></a>
                                            <button class="btn_delete btn_td btn_red" data-id="<?= $row['role_id'] ?>"><i
                                                    class="fa-solid fa-trash-can"></i></button>
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
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 },
                    { responsivePriority: 3, targets: 2 }
                ],
                order: []
            });

            $('.btn_hamburger').click(function () {
                let sidebar = $('.sidebar').css('margin-left');

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

            $('.button_dropdown').click(function () {
                let display = $('.dropdown_profile').css('display');
                if (display == 'none') {
                    $('.dropdown_profile').fadeIn(200);
                } else {
                    $('.dropdown_profile').fadeOut(0);
                }
            });

            $('#table').on('click', '.btn_delete', function () {
                let confirm = $('.dialog_background');
                let id = $(this).data('id');
                let form = $('#ajax-delete');
                let row = $(this).closest('tr');
                form.attr('action', '../action/action_delete_role.php?id='+ id);
                form.data('row',row);
                confirm.fadeIn(300);
            });

            $('.btn_close_dialog').click(function () {
                let confirm = $('.dialog_background');
                let form = $('#ajax-delete');
                form.attr('action', '../action/action_delete_role.php');
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