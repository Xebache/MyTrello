<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
        <title>Login</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
        <link href="css/styles2.css" rel="stylesheet" type="text/css"/>
        <script src="lib/js/jquery-3.6.0/jquery-3.6.0.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery-ui-1.12.1.ui-lightness/jquery-ui.js" type="text/javascript"></script>
        <script src="lib/js/login-validation-form.js"></script>
    </head>
    <body class="flex_column">
        <header id="main_header">
            <?php include('menu.php'); ?>
        </header>
        <main>
            <form class="main_form background1" id='loginForm' action="user/login" method="post">
                <h2 class="window_title">Sign in</h2>
                <hr>
                <?php if ($errors->has_errors()): ?>
                    <?php include('errors.php'); ?>
                <?php endif; ?>
                <div id="errors"></div>
                <ul>
                    <li class="flex_row center form_row">
                        <label for="email"><i class="fas fa-user"></i></label>
                        <input type="email" name="email" placeholder="Email" value="<?=$email?>" id="email">
                    </li>
                    <li class="flex_row center form_row">
                        <label for="password"><i class="fas fa-lock"></i></label>
                        <input type="password" name="password" placeholder="Password" value="<?=$password?>">
                    </li>
                    <li class="flex_row center form_row submit">
                        <input type="submit" value="Login">
                    </li>
                </ul>
                
            </form>
            
        </main>
    </body>
</html>