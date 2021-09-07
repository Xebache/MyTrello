<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
        <title>Delete</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
        <link href="css/styles2.css" rel="stylesheet" type="text/css"/>
    </head>
    <body class="home">
        <header>
            <?php include('menu.php'); ?>
        </header>
        <main class="delete_confirm">
            <form class="main_form background1" action="<?= strtolower(get_class($instance)) ?>/remove" method="post">
                <h2 class="window_title"><i class="far fa-trash-alt"></i></h2>
                <p class="subtitle">Are you sure ?</h4>
                <hr>
                <p>Do you really want to delete this <?= strtolower(get_class($instance)) ?> ?</p>
                <?php if(strtolower(get_class($instance)) == "collaboration"): ?>
                    <p>All participations will be deleted.</p>
                <?php endif ?>
                <p>This process cannot be undone.</p>
                <?php if(strtolower(get_class($instance)) == "collaboration"): ?>
                    <input type="text" name="board_id" value=<?= $instance->get_board_id()?> hidden>
                    <input type="text" name="id" value=<?= $instance->get_collaborator_id()?> hidden>
                <?php else: ?>
                    <input type="text" name="id" value=<?= $instance->get_id()?> hidden>
                <?php endif ?>
                <ul class="flex_row center">
                    <li>
                        <input class="cancel" type='submit' value='Cancel' name='cancel'>
                    </li>
                    <li>
                        <input class="delete" type='submit' value='Delete' name='delete'>
                    </li>
                </ul>
            </form>
        </main>
    </body>
</html>