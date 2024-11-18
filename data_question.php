<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Data Question</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/font/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/datatables.min.css">
    <link rel="stylesheet" href="../assets/css/responsive.dataTables.min.css">
</head>
<?php

include 'function.php';

$user_session = session_profile_user();
$role_session = session_role();
$data_question = get_all_question();

if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '')) {
    header('location: ../login');
    exit;
}

if (authorization('question')) {
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
                        <h3>Hapus Question</h3>
                        <button type="button" class="btn_close_dialog">
                            <i class="fa-solid fa-x"></i>
                        </button>
                    </div>
                    <div class="dialog_body">
                        Apakah anda yakin ingin menghapus question?
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
                        <span>Data Question</span>
                        <a href="question/add">Add Question</a>
                    </div>
                    <div class="wrap_table">
                        <table id="table" width="100%" style="max-width: 100%;">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($data_question as $row) { ?>
                                    <tr>
                                        <td class="vertical_align_top"><?= $row['question']; ?></td>
                                        <td class="vertical_align_top"><?= $row['answer']; ?></td>
                                        <td class="vertical_align_top">
                                            <div class="td_btn_wrapper">
                                                <a href="question/<?= $row['id'] ?>" class="btn_td"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
                                                <button class="btn_delete btn_td btn_red" data-id="<?= $row['id'] ?>"><i
                                                        class="fa-solid fa-trash-can"></i></button>
                                            </div>
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
                form.attr('action', '../action/action_delete_question.php?id='+ id);
                form.data('row',row);
                confirm.fadeIn(300);
            });

            $('.btn_close_dialog').click(function () {
                let confirm = $('.dialog_background');
                let form = $('#ajax-delete');
                form.attr('action', '../action/action_delete_question.php');
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