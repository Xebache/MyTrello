<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
        <title>Boards</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
        <link href="css/styles2.css" rel="stylesheet" type="text/css"/>
        <script src="lib/js/jquery-3.6.0/jquery-3.6.0.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>
        <script src="lib/js/boardlist-validation-form.js"></script>
    </head>
    <body class="home">
        <header id="main_header">
        <?php include('menu.php'); ?>
        </header>
        <?php if($user): ?>
        <main class="flex_column center">
            <article class="display_boards" id="main_article">
                <h2>Your boards</h2>
                <div class="flex_row start wrap">
                    <ul class="flex_row start wrap">
                    <?php foreach($owners as $board): ?>
                        <li class="flex_row center"><a class="blue" href="board/board/<?= $board['id'] ?>"><b><?= $board['title'] ?></b> <?= $board['columns'] ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                    <form id="addBoardForm" class="add" action="board/add" method="post">
                        <input type="text" name="title" id="title" placeholder="Add a board">
                        <input type="submit" value="&#xf067" class="fas fa-plus">
                        <?php if ($errors->has_errors()): ?>
                            <?php include('errors.php'); ?>
                        <?php endif; ?>
                        <div id="addBoardErrors"></div>
                    </form>
                </div>
            </article>
            <article class="display_boards">
                <h2>Boards shared with you</h2>
                <ul class="flex_row start wrap">
                <?php foreach($shared as $board): ?>
                    <li class="flex_row center"><a class="green" href="board/board/<?= $board['id'] ?>"><b><?= $board['title'] ?></b> <?= $board['columns'] ?> <br/>by <?= $board['fullName'] ?></a></li>
                <?php endforeach; ?>
                </ul>
            </article>
            <?php if($user->is_admin()): ?>
            <article class="display_boards">
                <h2>Others' boards</h2>
                <ul class="flex_row start wrap">
                <?php foreach($others as $board): ?>
                    <li class="flex_row center"><a class="gray" href="board/board/<?= $board['id'] ?>"><b><?= $board['title'] ?></b> <?= $board['columns'] ?> <br/>by <?= $board['fullName'] ?></a></li>
                <?php endforeach; ?>
                </ul>
            </article>
            <?php endif ?>
        </main>
        <?php else:?>
        <main class="welcome">
            <p>Hello guest ! Please <a href="user/login">login</a> or <a href="user/signup">signup</a>.</p>
        </main>
        <?php endif;?>
    </body>
</html>