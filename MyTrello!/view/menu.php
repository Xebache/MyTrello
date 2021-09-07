<div class="menu flex_row">
    <div>
        <h1>Trello!</h1>
    </div>
    <div class="right flex_row">
        <?php if (isset($user) && $user instanceof User): ?>
            <?= CtrlTools::breadcrumb(); ?>
            <a class="customButton" href="calendar/index">Calendar</a>  
            <?php if($user->is_admin()): ?> 
                <a class="customButton" href="user/manage">Manage users</a>
                <p id="logged-user"><i class="fas fa-user-shield"></i><?= $user->get_fullName() ?></p>
            <?php else: ?>
                <p id="logged-user"><i class="fas fa-user"></i><?= $user->get_fullName() ?></p>
            <?php endif ?>
            <p><a href="user/logout"><i class="fas fa-sign-out-alt"></i></a></p>
        <?php else: ?>
            <p><a class="loginLink" href="user/login"><i class="fas fa-sign-in-alt"></i></a></p>
            <p><a class="signupLink" href="user/signup"><i class="fas fa-user-plus"></i></a></p>
        <?php endif;?>
    </div>
</div>