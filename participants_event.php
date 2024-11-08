<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Dashboard</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../assets/font/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/datatables.min.css">
    <link rel="stylesheet" href="../../../assets/css/responsive.dataTables.min.css">
</head>
<?php
include 'function.php';

$user_session = session_profile_user();
$role_session = session_role();
$seminar_id = isset($_GET['id']) ? $_GET['id'] : '';
$participants = show_participants_event($seminar_id);

if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '')) {
    header('location: ../../../login');
    exit;
}

if (authorization('create event')) {
    header('location: ../../home');
}

if (validation_contributor($seminar_id)) {
    header('location: ../../create_events');
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
                <form action="../../../action/action_set_status.php" id="ajax-status">
                    <div class="dialog_header">
                        <h3>Set Status</h3>
                        <button type="button" class="btn_close_dialog">
                            <i class="fa-solid fa-x"></i>
                        </button>
                    </div>
                    <div class="dialog_body">
                        Apakah anda yakin ingin set status?
                    </div>
                    <div class="dialog_footer">
                        <button type="button" class="btn_close_dialog">Tutup</button>
                        <button type="submit">Ya</button>
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
                        <span>Participants Event</span>
                        <a href="../../create_events">Back</a>
                    </div>
                    <div class="wrap_table">
                        <table id="table" class="responsive nowrap" width="100%" style="max-width: 100%;">
                            <thead>
                                <tr>
                                    <th data-priority="1">No Reg</th>
                                    <th data-priority="2">Peserta</th>
                                    <th>Waktu reg</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($participants as $row) {
                                    $date = isset($row['waktu_reg']) ? date_create($row['waktu_reg']) : '';
                                    ?>
                                    <tr>
                                        <td><?= $row['no_reg']; ?></td>
                                        <td><?= $row['email_peserta']; ?></td>
                                        <td>
                                            <?= date_format($date, 'd F Y, H:i') ?>
                                        </td>
                                        <td>
                                            <?php if ($row['status'] == 'tertunda') { ?>
                                                <div class="btn_status">
                                                    <button class="btn_set_status btn_td btn_green"
                                                        data-reg="<?= $row['no_reg'] ?>" data-status="diterima">Terima</button>
                                                    <button class="btn_set_status btn_td btn_red"
                                                        data-reg="<?= $row['no_reg'] ?>" data-status="ditolak">Tolak</button>
                                                </div>
                                            <?php } else { ?>
                                                <span
                                                    class="tag tag_status <?= $row['status'] == 'diterima' ? 'tag_green' : 'tag_red' ?>"><?= $row['status'] ?></span>
                                            <?php } ?>
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

    <script src="../../../assets/js/jquery.min.js"></script>
    <script src="../../../assets/js/datatables.min.js"></script>
    <script src="../../../assets/js/dataTables.responsive.min.js"></script>
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

            $('#table').on('click', '.btn_set_status', function (e) {
                let confirm = $('.dialog_background');
                let reg = $(this).data('reg');
                let status = $(this).data('status');
                let btn_set = $(this).parent();
                let row = $(this).closest('td');
                let form = $('#ajax-status');

                form.data('reg',reg);
                form.data('status',status);
                form.data('row',row);
                form.data('btn_set',btn_set);
                confirm.fadeIn(300);
            });

            $('.btn_close_dialog').click(function () {
                let confirm = $('.dialog_background');
                confirm.fadeOut(300);
            });

            $('#ajax-status').submit(function(e){
                e.preventDefault();
                let url = $(this).attr('action');
                let reg = $(this).data('reg');
                let status = $(this).data('status');
                
                let row = $(this).data('row');
                let btn_set = $(this).data('btn_set');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {no_reg: reg, status: status},
                    dataType: 'JSON',
                    beforeSend: function () {
                        $('.dialog_background').fadeOut(300);
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            btn_set.remove();
                            row.append(response.span_status);
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
                            }, 2000);
                        }
                    }
                });

            });
        });
    </script>
</body>

</html>