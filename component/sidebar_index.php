<div class="sidebar_index">
    <ul class="wrap_sidebar">
        <li><a href="" class="sidebar_link">Beranda</a></li>
        <li><a href="" class="sidebar_link">Webinar</a></li>
        <li class="profile_list">
            <?php if ($authentikasi == false) { ?>
                <div class="profile_user_sidebar">
                    <div class="button_dropdown">
                        <span class="nama_profile">
                            <span class="nama_user">
                                <?php
                                print_r($user['nama']);
                                ?>
                            </span>
                            <i class="fa-solid fa-user foto_profile"></i>
                        </span>
                    </div>
                    <div class="dropdown_profile">
                        <div class="wrap_dropdown">
                            <ul>
                                <div class="navbar_top">
                                    <div class="user_foto">
                                        <i class="fa-solid fa-user foto_user"></i>
                                    </div>
                                    <li><span><?php print_r($user['nama']); ?></span></li>
                                </div>
                                <div class="navbar_list">
                                    <li><a href="dashboard/home"><i class="fa-solid fa-house"></i> My Dashboard</a></li>
                                    <li><a href=""><i class="fa-solid fa-user"></i> My Profile</a></li>
                                </div>
                                <div class="navbar_logout">
                                    <li>
                                        <a href="action/action_logout.php" class="nav_logout">
                                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                                        </a>
                                    </li>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <ul class="nav_items nav_right_dekstop">
                    <li class="nav_item"><a href="login" class="nav_link btn btn_masuk">Masuk</a></li>
                    <li class="nav_item"><a href="registrasi" class="nav_link btn btn_daftar">Daftar</a></li>
                </ul>
            <?php } ?>
        </li>
    </ul>
</div>