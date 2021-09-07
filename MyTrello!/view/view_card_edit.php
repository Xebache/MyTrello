<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
        <title>Edit a card</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
        <script src="lib/js/jquery-3.6.0/jquery-3.6.0.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery-ui-1.12.1.ui-lightness/jquery-ui.js" type="text/javascript"></script>
        <script src="lib/js/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>        
        <script src="lib/js/comment-validation.js"></script>
        <script src="lib/js/card-edit-validation.js"></script>
        <link href="css/styles2.css" rel="stylesheet" type="text/css"/>
    </head>
    <body class="card">
        <header id="main_header">
            <?php include('menu.php') ?>
        </header>
        <main>
            <article>
                <header>
                    <h2>Edit a card</h2>
                    <p class="credit">Created <?= $card->get_created_intvl() ?> by <strong>'<?= $card->get_author_name() ?>'</strong>. <?= $card->get_modified_intvl() ?>.</p>
                </header>
                <div class="background3">
                <?php if ($errors->has_errors()): ?>
                    <?php include('errors.php'); ?>
                <?php endif; ?>
                    <section class="main_card">
                        <div id="edit_card_error"></div>
                        <form id="edit_card" action="card/update" method="post">                         
                            <input id="cardId" value="<?=$card->get_id()?>" hidden>
                            <div class="edit_card flex_column">
                                <label for="title" form="edit_card"><h3>Title</h3></label>
                                <input type="text" name="title" id="title" maxlength="128" value='<?= $card->get_title() ?>' placeholder='<?= $card->get_title() ?>' >
                            </div>
                            <div class="edit_card flex_column">
                                <label for="body" form="edit_card"><h3>Body</h3></label>
                                <textarea class="background2" name="body" id="body" rows="5" ><?= $card->get_body() ?></textarea>
                            </div>
                            <div class="edit_card flex_column">
                                <label for="dueDate" form="edit_card"><h3>Due date</h3></label>
                                <input type="date" name="dueDate" id="dueDate" value="<?= $card->get_dueDate_html_format() ?>" min="<?= $card->createdAt_format() ?>">
                            </div>
                        </form>
                        <div class="participant flex_column">
                            <h3>Participants</h3>
                        <?php if(!$card->has_participations()): ?>
                            <p class="display background2">This card has no participant yet</p>
                        <?php else: ?>
                            <ul class="collab_list background2">
                            <?php foreach($card->get_participations() as $participation): ?>
                                <li class="flex_row">
                                    <p><?= $participation->get_participant_fullName() ?> (<?= $participation->get_participant_email() ?>)</p>
                                    <form action='participation/delete' method='post'>
                                        <input type='text' name='user_id' value='<?= $participation->get_participant_id() ?>' hidden>
                                        <input type='text' name='card_id' value='<?= $card->get_id() ?>' hidden>
                                        <input type='submit' value="&#xf2ed" class="far fa-trash-alt" style="background:none">
                                    </form>
                                </li>
                            <?php endforeach ?>
                            </ul>
                        <?php endif ?> 
                        </div>
                        <div class="participant flex_column">   
                            <form class="add flex_column" action="participation/add" method="post">
                                <h3>Add a new participant</h3>
                                <input type="text" name="card_id" value="<?= $card->get_id() ?>" hidden>
                                <div class="flex_row select">
                                    <select name="user_id" id="others">
                                    <?php foreach($card->get_others() as $other): ?>
                                        <option value='<?= $other->get_id() ?>'><?= $other->get_fullName() ?> (<?= $other->get_email() ?>)</option>
                                    <?php endforeach ?>
                                    </select>
                                    <input type="submit" value="&#xf067" class="fas fa-plus">
                                </div>
                            </form>
                        </div>
                        <div class="edit_card flex_column">
                            <label for="board" form="edit_card"><h3>Board</h3></label>
                            <input class="background2" type ="text" name="title_board" id="title_board" value='<?= $card->get_board_title() ?>' disabled form="edit_card">
                        </div>
                        <div class="edit_card flex_column" >
                            <label for="title_column" form="edit_card"><h3>Column</h3></label>
                            <input class="background2" type ="text" name="title_column" id="title_column" value='<?= $card->get_column_title() ?>' disabled form="edit_card">
                        </div>
                        <div class="edit_card submit">
                            <input type="text" name="id" value='<?= $card->get_id() ?>' hidden form="edit_card">
                            <input type="submit" value="Cancel" form="edit_card" name="edit" form="edit_card">
                            <input type="submit" value="Edit this card" form="edit_card" name="edit" form="edit_card">
                        </div>
                    </section>
                    <?php include('view_comments.php') ?>
                </div>
            </article>
        </main>
    </body>
</html>