<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
    <title>Board "<?= $board->get_title() ?>"</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
    <link href="css/styles2.css" rel="stylesheet" type="text/css"/>
    <script src="lib/js/jquery-3.6.0/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/js/jquery-ui-1.12.1.ui-lightness/jquery-ui.js" type="text/javascript"></script>
    <script src="lib/js/collaborators.js" type="text/javascript"></script>
</head>
<body>
    <header id="main_header">
     <?php include("menu.php"); ?>
    </header>
    <main class="collab flex_row center">
        <article class="background1 main-article">
            <h2><?= $board->get_title() ?> : Collaborators</h2>
            <section class="collab-php">
                <h3>Current collaborator(s):</h3>
                <ul class="collab_list">
                <?php foreach($board->get_collaborations() as $collaboration): ?>
                    <li class="flex_row">
                        <p><?= $collaboration->get_collaborator_fullName() ?> (<?= $collaboration->get_collaborator_email() ?>)</p>
                        <form class='link' action='collaboration/delete' method='post'>
                            <input type='text' name='user_id' value='<?= $collaboration->get_collaborator_id() ?>' hidden>
                            <input type='text' name='board_id' value='<?= $collaboration->get_board_id() ?>' hidden>
                            <input type='submit' value="&#xf2ed" class="trash far fa-trash-alt" style="background:none">
                        </form>
                    </li>
                <?php endforeach ?>
                </ul>
            </section>
            <section class="collab-php">
                <h3>Add a new collaborator</h3>
                <form class="add" action="collaboration/add" method="post">
                    <input type="text" name="board_id" value="<?= $board->get_id() ?>" hidden>
                    <select name="user_id" id="others">
                    <?php foreach($board->get_others($user) as $other): ?>
                        <option value='<?= $other->get_id() ?>'><?= $other->get_fullName() ?> (<?= $other->get_email() ?>)</option>
                    <?php endforeach ?>
                    </select>
                    <input type="submit" value="&#xf067" class="fas fa-plus">
                </form>
            </section>
        </article>
        <div class="popup_window" id="confirmDialogCollab" hidden></div>
    </main>
</body>