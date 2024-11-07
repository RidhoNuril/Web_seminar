<header class="navbar_index">
    <?php
        $directory = basename(dirname($_SERVER['REQUEST_URI']));
        $url_file = basename($_SERVER['REQUEST_URI']);
    ?>
    <div class="center_navbar">
        <div class="wrap_navbar">
            <div class="navbar_left">
                <ul class="nav_items">
                    <li class="nav_item"><a href="../web_seminar" class="nav_link title_nav"> Web Seminar</a></li>
                </ul>
            </div>
            <div class="navbar_mid">
                <ul class="nav_items">
                    <li class="nav_item item_mid"><a href="../web_seminar" class="nav_link <?= $url_file == 'web_seminar' ? 'active_link' : '' ?>">Beranda</a></li>
                    <li class="nav_item item_mid"><a href="events_mendatang" class="nav_link <?= $url_file == 'events_mendatang' ? 'active_link' : '' ?>">Webinar</a></li>
                </ul>
            </div>
            <div class="navbar_right">
                <?php if($authentikasi == false){ ?>
                    <div class="profile_user">
                        <div class="button_dropdown">
                            <span class="nama_user">
                                <?= $user['nama'] ?>
                            </span>
                            <i class="fa-solid fa-user foto_profile"></i>
                        </div>
                        <div class="dropdown_profile">
                            <div class="wrap_dropdown">
                                <ul>
                                    <div class="navbar_top">
                                        <div class="user_foto">
                                            <i class="fa-solid fa-user foto_user"></i>
                                        </div>
                                        <li><span><?= $user['nama'] ?></span></li>
                                    </div>
                                    <div class="navbar_list">
                                        <li><a href="dashboard/home"><i class="fa-solid fa-house"></i> My Dashboard</a></li>
                                        <li><a href="dashboard/profile"><i class="fa-solid fa-user"></i> My Profile</a></li>
                                        <li><a href="dashboard/ubah-password"><i class="fa-solid fa-lock"></i> Ubah Password</a></li>
                                    </div>
                                    <div class="navbar_logout">
                                        <li>
                                            <a href="action/action_logout.php"
                                                class="nav_logout">
                                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                                            </a>
                                        </li>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php }else{ ?>
                    <ul class="nav_items nav_right_dekstop">
                        <li class="nav_item"><a href="login" class="nav_link btn btn_masuk">Masuk</a></li>
                        <li class="nav_item"><a href="registrasi" class="nav_link btn btn_daftar">Daftar</a></li>
                    </ul>
                <?php } ?>
                <ul class="nav_items btn_hamburger">
                    <li class="nav_item"><i class="fa-solid fa-bars nav_link"></i></li>
                </ul>
            </div>
        </div>
    </div>
</header>