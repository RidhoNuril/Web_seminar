<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Seminar - Edit User</title>
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../../font/fontawesome/css/all.min.css">
</head>

<body>
    <?php
    include 'function.php';

    $user_session = session_profile_user();
    $role_session = session_role();
    $role_get = get_all_role();

    if (auth_check_token(!empty($_SESSION['auth_token']) ? $_SESSION['auth_token'] : '')) {
        header('location: ../../login');
        exit;
    }

    if(authorization('user')){
        header('location: ../home');
        exit;
    }

    $user_id = isset($_GET['user']) ? $_GET['user'] : '';
    $user = form_edit_user($user_id);

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
                                    <a href="../user" id="btn_back">
                                        Back
                                    </a>
                                </div>
                                <span class="sub-title">User</span>
                            </div>
                            <form action="../../action/action_edit_user.php" method="POST" id="form_edit">
                                <div class="form_body">
                                    <input type="hidden" id="input_hidden" name="id" value="<?= $user['user_id'] ?>">
                                    <div class="form_left">
                                        <div class="form_group">
                                            <label for="">Nama Lengkap</label>
                                            <input type="text" name="nama" id="nama" value="<?= $user['nama'] ?>"
                                                placeholder="Isi Nama Anda Cth : Agus Salim" required />
                                        </div>

                                        <div class="form_group">
                                            <label for="">Kelas</label>
                                            <select name="kelas" id="kelas" required="required">
                                                <option value="" disabled selected>Pilih Kelas</option>
                                                <option value="10" <?= ($user['kelas'] == '10' ? 'selected' : '') ?>>X</option>
                                                <option value="11" <?= ($user['kelas'] == '11' ? 'selected' : '') ?>>XI</option>
                                                <option value="12" <?= ($user['kelas'] == '12' ? 'selected' : '') ?>>XII</option>
                                            </select>
                                        </div>

                                        <div class="form_group">
                                            <label for="">Asal Sekolah</label>
                                            <input type="text" name="asal_sekolah" id="asal_sekolah"
                                                value="<?= $user['asal_sekolah'] ?>" placeholder="Cth : SMKN 2 Bandar Lampung"
                                                required />
                                        </div>

                                        <div class="form_group">
                                            <label for="">Email</label>
                                            <input type="text" name="email" id="email" value="<?= $user['email'] ?>"
                                                placeholder="Cth : example@gmail.com" required />
                                        </div>

                                        <div class="form_group">
                                            <label for="">Nomor Handphone / Whatsapp</label>
                                            <input type="text" name="no_telp" id="no_telp" value="<?= $user['no_telp'] ?>"
                                                placeholder="Cth : 082356778699" required />
                                        </div>
                                    </div>

                                    <div class="form_right">
                                        <div class="form_group">
                                            <label for="">Provinsi</label>
                                            <select name="provinsi" id="select-provinsi" data-prov="<?= $user['provinsi'] ?>"
                                                required="required">
                                                <option value="" disabled selected>Pilih Provinsi</option>
                                            </select>
                                        </div>

                                        <div class="form_group">
                                            <label for="">Kabupaten</label>
                                            <select name="kabupaten" id="select-kabupaten" data-kab="<?= $user['kabupaten'] ?>"
                                                required="required">
                                                <option value="" disabled selected>Pilih Kabupaten</option>
                                            </select>
                                        </div>

                                        <div class="form_group">
                                            <label for="">Kecamatan</label>
                                            <select name="kecamatan" id="select-kecamatan" data-kec="<?= $user['kecamatan'] ?>"
                                                required="required">
                                                <option value="" disabled selected>Pilih Kecamatan</option>
                                            </select>
                                        </div>

                                        <div class="form_group">
                                            <label for="">Kelurahan</label>
                                            <select name="kelurahan" id="select-kelurahan" data-kel="<?= $user['kelurahan'] ?>"
                                                required="required">
                                                <option value="" disabled selected>Pilih Kelurahan</option>
                                            </select>
                                        </div>

                                        <div class="form_group">
                                            <label for="">Role</label>
                                            <select name="role_id" id="select-role">
                                                <option value="" disabled selected>Pilih role</option>
                                                <?php 
                                                if($role_get != []){
                                                foreach($role_get as $roles){ ?>
                                                    <option value="<?= $roles['role_id'] ?>" <?= $user['role_id'] == $roles['role_id'] ? 'selected' : '' ?>><?= $roles['nama_role'] ?></option>
                                                <?php } }else{?>
                                                    <option value="" disabled>Data role tidak ditemukan</option>
                                                <?php } ?>
                                            </select>
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
    <script src="../../js/jquery.min.js"></script>
    <script>
        $(window).on('load', function () {
            setTimeout(function () {
                $('.loading_wrapper').fadeOut(500);
            }, 500);
        });
    </script>
    <script src="../../js/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function () {

            let provName = $('#select-provinsi').data('prov');

            $.ajax({
                url: "../../json/provinces.json", dataType: 'json',
                success: function (data) {
                    let prov = data;
                    prov.forEach(function (prov) {
                        let selected = (prov.name == provName) ? 'selected' : '';
                        $('#select-provinsi').append('<option value="' + prov.name + '" data-id="' + prov.id + '" ' + selected + '>' + prov.name + '</option>');
                    });
                }
            });

            setTimeout(function () {
                let provId = $('#select-provinsi').find(":selected").data("id");
                let kabName = $('#select-kabupaten').data('kab');

                $.ajax({
                    url: "../../json/regencies.json", dataType: 'json',
                    success: function (data) {
                        let kab = data;
                        kab.forEach(function (kab) {
                            let selected = (kab.name == kabName) ? 'selected' : '';
                            if (kab.province_id == provId) {
                                $('#select-kabupaten').append('<option value="' + kab.name + '" data-id="' + kab.id + '" ' + selected + '>' + kab.name + '</option>');
                            }
                        });
                    }
                });
            }, 50);

            setTimeout(function () {
                let kabId = $('#select-kabupaten').find(":selected").data("id");
                let kecName = $('#select-kecamatan').data('kec');


                $.ajax({
                    url: "../../json/districts.json", dataType: "json", success: function (data) {
                        let kec = data;
                        kec.forEach(function (kec) {
                            let selected = (kec.name == kecName) ? 'selected' : '';
                            if (kec.regency_id == kabId) {
                                $('#select-kecamatan').append('<option value="' + kec.name + '" data-id="' + kec.id + '" ' + selected + '>' + kec.name + '</option>');
                            }
                        });
                    }
                });
            }, 100);

            setTimeout(function () {
                let kecId = $('#select-kecamatan').find(":selected").data("id");
                let kelName = $('#select-kelurahan').data('kel');

                $.ajax({
                    url: "../../json/villages.json", dataType: "json",
                    success: function (data) {
                        let kel = data;
                        kel.forEach(function (kel) {
                            let selected = (kel.name == kelName) ? 'selected' : '';
                            if (kel.district_id == kecId) {
                                $("#select-kelurahan").append('<option value="' + kel.name + '" ' + selected + '>' + kel.name + '</option>')
                            }
                        });
                    }
                });
            }, 150);


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

            $('#select-provinsi').change(function () {
                let provId = $(this).find(":selected").data("id");

                if ($('#select-kabupaten').find(":selected").val() != "") {
                    $('#select-kabupaten').empty().append('<option disabled selected>Pilih Kabupaten</option>');
                    $('#select-kecamatan').empty().append('<option disabled selected>Pilih Kecamatan</option>');
                    $('#select-kelurahan').empty().append('<option disabled selected>Pilih Kelurahan</option>');
                }

                $.ajax({
                    url: "../../json/regencies.json", dataType: 'json',
                    success: function (data) {
                        let kab = data;
                        kab.forEach(function (kab) {
                            if (kab.province_id == provId) {
                                $('#select-kabupaten').append('<option value="' + kab.name + '" data-id="' + kab.id + '">' + kab.name + '</option>');
                            }
                        });
                    }
                });
            });


            $('#select-kabupaten').change(function () {
                let kabId = $(this).find(":selected").data("id");

                if ($('#select-kecamatan').find(":selected").val() != "") {
                    $('#select-kecamatan').empty().append('<option disabled selected>Pilih Kecamatan</option>');
                    $('#select-kelurahan').empty().append('<option disabled selected>Pilih Kelurahan</option>');
                }

                $.ajax({
                    url: "../../json/districts.json", dataType: "json", success: function (data) {
                        let kec = data;
                        kec.forEach(function (kec) {
                            if (kec.regency_id == kabId) {
                                $('#select-kecamatan').append('<option value="' + kec.name + '" data-id="' + kec.id + '">' + kec.name + '</option>');
                            }
                        });
                    }
                });
            });


            $("#select-kecamatan").change(function () {
                let kecId = $(this).find(":selected").data("id");

                if ($("#select-kelurahan").find(":selected").val() != "") {
                    $("#select-kelurahan").empty().append('<option disabled selected>Pilih Kelurahan</option>');
                }

                $.ajax({
                    url: "../../json/villages.json", dataType: "json",
                    success: function (data) {
                        let kel = data;
                        kel.forEach(function (kel) {
                            if (kel.district_id == kecId) {
                                $("#select-kelurahan").append('<option value="' + kel.name + '">' + kel.name + '</option>')
                            }
                        });
                    }
                });
            });

            let id = $('#input_hidden').val();

            $('#form_edit').validate({
                rules: {
                    email: {
                        remote: {
                            url: '../../action/check_email_edit.php',
                            type: 'POST',
                            data: {
                                id: id
                            }
                        },
                    },
                    no_telp: {
                        number: true,
                        rangelength: [10, 13],
                        remote: {
                            url: '../../action/check_telp_edit.php',
                            type: 'POST',
                            data: {
                                id: id
                            }
                        }
                    }
                },
                messages: {
                    nama: 'Nama Lengkap Harus Diisi',
                    kelas: 'Kelas Harus Dipilih',
                    asal_sekolah: 'Asal Sekolah Harus Diisi',
                    email: {
                        required: 'Email Harus Diisi',
                        email: 'Harus Diisi Dengan Format example@gmail.com',
                        remote: 'Email Ini Sudah Digunakan'
                    },
                    no_telp: {
                        required: 'Nomor Handphone Harus Diisi',
                        number: 'Hanya Bisa Diisi Dengan Angka',
                        rangelength: 'Nomor Harus Antara 10 - 13 Digits',
                        remote: 'Nomor Ini Sudah Digunakan'
                    },
                    provinsi: 'Provinsi Harus Dipilih',
                    kabupaten: 'Kabupaten Harus Dipilih',
                    kecamatan: 'Kecamatan Harus Dipilih',
                    kelurahan: 'Kelurahan Harus Dipilih'
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
                                $('#nama').val('');
                                $('#kelas').val('');
                                $('#asal_sekolah').val('');
                                $('#email').val('');
                                $('#no_telp').val('');
                                $('#select-provinsi').val('');
                                $('#select-kabupaten').empty().append('<option value="" disabled selected>Pilih Kabupaten</option>');
                                $('#select-kecamatan').empty().append('<option value="" disabled selected>Pilih Kecamatan</option>');
                                $('#select-kelurahan').empty().append('<option value="" disabled selected>Pilih Kelurahan</option>');
                                $('#select-role').empty().append('<option value="" disabled selected>Pilih Role</option>');
                                setTimeout(function () {
                                    location.href = response.redirect;
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

</html>