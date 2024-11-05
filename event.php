<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../font/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/datatables.min.css">
    <link rel="stylesheet" href="../css/responsive.dataTables.min.css">
</head>
<?php
    include 'function.php';

    $user_session = session_profile_user();
    $role_session = session_role();
    $event_user = event_user();
    
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
                    <div class="title_content">
                        <span>Event</span>
                    </div>
                    <div class="wrap_table">
                        <table id="table" class="nowrap" width="100%" style="max-width: 100%;">
                            <thead>
                                <tr>
                                    <th data-priority="1">Thumbnail</th>
                                    <th data-priority="2">Detail Event</th>
                                    <th>Link</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach($event_user as $row) { 
                                    ?>
                                    <tr>
                                        <td class="td_image"><img src="../thumbnail/<?= $row['thumbnail'] ?>" class="img_thumbnail_td" alt="Thumbnail_image"></td>
                                        <td class="vertical_align_top">
                                            <?php if($row['waktu_seminar'] != ''){ ?>
                                                <p class="judul_event"><?= $row['judul'] ?></p>
                                                <span class="time"><?= date_format($row['waktu_seminar'], "d F Y | H:i") ?> WIB</span>
                                                <div class="status">
                                                    <span class="tag_status <?= $row['status_seminar'] != 'aktif' ? 'tag_red' : '' ?>"><?= $row['status_seminar'] == 'aktif' ? 'Event Mendatang' : 'Event Terlewat' ?></span>
                                                </div>
                                            <?php }else{ ?>
                                                <p class="judul_event">Event tidak ditemukan</p>
                                            <?php } ?>
                                        </td>
                                        <td class="vertical_align_top">
                                            <?php 
                                            if($row['status'] == 'diterima'){
                                                if($row['link_meet'] == null) {?>
                                                    <span>Link belum dibagikan</span>
                                                <?php }else{ ?>
                                                    <span class="link"><a href=<?= $row['link_meet'] ?> target="_blank"><i class="fa-solid fa-calendar-days"></i>Join meet</a></span>
                                                <?php } 
                                            }else if($row['status'] == 'tertunda'){?>
                                                <span>Status anda masih tertunda</span>
                                            <?php }else if($row['status'] == 'ditolak'){ ?>
                                                <span>Status anda ditolak</span>
                                            <?php } ?>
                                        </td>
                                        <td class="vertical_align_top"><span class="tag <?= $row['status'] == 'diterima' ? 'tag_green' : 'tag_red' ?>"><?= $row['status'] ?></span></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/datatables.min.js"></script>
    <script src="../js/dataTables.responsive.min.js"></script>
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

            $('.button_dropdown').click(function(){
                let display = $('.dropdown_profile').css('display');
                if(display == 'none'){
                    $('.dropdown_profile').fadeIn(200);
                }else{
                    $('.dropdown_profile').fadeOut(0);
                }
            });
            
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
        });
    </script>
</body>

</html>