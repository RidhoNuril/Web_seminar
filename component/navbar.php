<nav class="navbar">
    <?php
        $directory = basename(dirname($_SERVER['REQUEST_URI']));
        $url_file = basename($_SERVER['REQUEST_URI']);

        if($directory == 'dashboard'){
            $web_seminar = '../../web_seminar';
            $logout = '../action/action_logout.php';
        }elseif($url_file == 'participants'){
            $web_seminar = '../../../../web_seminar';
            $logout = '../../../action/action_logout.php';
        }else{
            $web_seminar = '../../../web_seminar';
            $logout = '../../action/action_logout.php';
        }
    ?>
    <div class="wrap_navbar">
        <div class="navbar_left">
            <button type="button" class="btn_hamburger">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <a href="<?= $web_seminar ?>" class="title_nav">Web Seminar</a>
        </div>
        <div class="navbar_right">
            <div class="profile_user">
                <div class="button_dropdown">
                    <span class="nama_user">
                    <?php
                        print_r($user_session['nama']); 
                    ?>
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
                                <li><span><?php print_r($user_session['nama']); ?></span></li>
                            </div>
                            <div class="navbar_list">
                                <li><a href=""><i class="fa-solid fa-user"></i> My Profile</a></li>
                            </div>
                            <div class="navbar_logout">
                                <li>
                                    <a href="<?= $logout ?>"
                                        class="nav_logout">
                                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>