<ul class="sidebar">
    <?php
    $directory = basename(dirname($_SERVER['REQUEST_URI']));
    $url_file = basename($_SERVER['REQUEST_URI']);

    if($directory == 'dashboard'){
        $dashboard = 'home';
        $event = 'event';
        $create_events = 'create_events';
        $users = 'user';
        $roles = 'role';
        $question = 'question';
    }elseif($url_file == 'participants'){
        $dashboard = '../../home';
        $event = '../../event';
        $create_events = '../../create_events';
        $users = '../../user';
        $roles = '../../role';
        $question = '../../question';
    }else{
        $dashboard = '../home';
        $event = '../event';
        $create_events = '../create_events';
        $users = '../user';
        $roles = '../role';
        $question = '../question';
    }
    ?>
    <div class="sidebar_list">
        <div class="nav_header">
            Core
        </div>
        <li>
            <a href="<?= $dashboard ?>"
                class="nav_link <?= $url_file == 'home' || $url_file == 'profile' ? 'active' : '' ?>">
                <i class="fa-solid fa-house <?= $url_file == 'home' || $url_file == 'profile' ? 'active' : '' ?>"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="<?= $event ?>"
                class="nav_link <?= $url_file == 'event' ? 'active' : '' ?>">
                <i class="fa-solid fa-calendar-days <?= $url_file == 'event' ? 'active' : '' ?>"></i> Event
            </a>
        </li>
        <?php if(authorization('create event') == false){ ?>
            <li>
                <a href="<?= $create_events ?>"
                    class="nav_link <?= $directory == 'create_events' || $url_file == 'create_events' || $url_file == 'participants' ? 'active' : '' ?>">
                    <i class="fa-solid fa-calendar-plus <?= $directory == 'create_events' || $url_file == 'create_events' || $url_file == 'participants' ? 'active' : '' ?>"></i>
                    Create Events
                </a>
            </li>
        <?php } ?>
        <?php if(authorization("user") == false || authorization("role") == false) {?>
            <div class="nav_header">
                Data
            </div>
        <?php } ?>
        <?php if(authorization('user') == false){ ?>
            <li>
                <a href="<?= $users ?>"
                    class="nav_link <?= $directory == 'user' || $url_file == 'user' ? 'active' : '' ?>">
                    <i class="fa-solid fa-users <?= $directory == 'user' || $url_file == 'user' ? 'active' : '' ?>"></i>
                    Users
                </a>
            </li>
        <?php } ?>
        <?php if(authorization('role') == false){ ?>
            <li>
                <a href="<?= $roles ?>"
                    class="nav_link <?= $directory == 'role' || $url_file == 'role' ? 'active' : '' ?>">
                    <i class="fa-solid fa-user-gear <?= $directory == 'role' || $url_file == 'role' ? 'active' : '' ?>"></i>
                    Roles
                </a>
            </li>
        <?php } ?>
        <?php if(authorization('question') == false){ ?>
            <li>
                <a href="<?= $question ?>"
                    class="nav_link <?= $directory == 'question' || $url_file == 'question' ? 'active' : '' ?>">
                    <i class="fa-solid fa-circle-question <?= $directory == 'question' || $url_file == 'question' ? 'active' : '' ?>"></i>
                    Question
                </a>
            </li>
        <?php } ?>
    </div>
    
</ul>