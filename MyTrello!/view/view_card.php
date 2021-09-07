<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
        <title>Card "<?= $card->get_title() ?>"</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
        <script src="lib/js/jquery-3.6.0/jquery-3.6.0.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery-ui-1.12.1.ui-lightness/jquery-ui.js" type="text/javascript"></script>
        <script src="lib/js/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>        
        <script src="lib/js/comment-validation.js"></script>
        <link href="css/styles2.css" rel="stylesheet" type="text/css"/>     
        
    </head>
    <body class="card">
        <header id="main_header">
            <?php include('menu.php'); ?>
        </header>
        <main >
            <article>
                <header>
                    <div class="flex_row">
                        <h2>Card "<?= $card->get_title() ?>"</h2>
                        <ul class="flex_row icon">
                            <li>
                                <form action='card/edit_link' method='post'>
                                    <input type='text' name='id' value='<?= $card->get_id() ?>' hidden>
                                    <input type='submit' value="&#xf044"class="fas fa-edit" style="background:none">
                                </form>
                            </li>
                            <li>
                                <form action='card/delete' method='post'>
                                    <input type='text' class="c" name='id' value='<?= $card->get_id() ?>' hidden>
                                    <input type='submit' value="&#xf2ed" class="far fa-trash-alt dlt_button_card" style="background:none" 
                                    delete_card_id="<?=$card->get_id()?>" delete_card_title="<?=$card->get_title()?>" delete_card_idBoard="<?= $card->get_board_id()?>">
                                </form>
                            </li>
                        </ul>
                    </div>
                    <p class="credit">Created <?=$card->get_created_intvl() ?> by <strong><?= $card->get_author_name()?></strong>. <?= $card->get_modified_intvl() ?>.</p>
                    <p class="credit">This card is on the board "<strong><a href="board/board/<?=$card->get_board_id() ?>"> <?= $card->get_board_title() ?></a></strong>", column "<strong><?= $card->get_column_title() ?></strong>" at position <?= $card->get_position() ?>.</p>
                </header>
                <div class="background3">
                    <?php if ($errors->has_errors()): ?>
                        <?php include('errors.php'); ?>
                    <?php endif; ?>
                    <section class="main_card">
                        <div>
                            <h3>Body</h3>
                            <textarea class="background2" name="body" id="body" rows="5" disabled><?= $card->get_body() ?></textarea>
                        </div>
                        <div class="duedate">
                            <h3>Due date</h3>
                            <?php if($card->has_dueDate()): ?>
                                <p class="display background2">This card is due on the <?= $card->get_dueDate_format() ?>.</p>
                            <?php else: ?>
                                <p class="display background2">This card has no due date yet.</p>
                            <?php endif ?>
                        </div>
                        <div>
                            <h3>Current Participant(s)</h3>
                            <?php if(empty($card->get_participations())): ?>
                                <p class="display background2">This card has no participant yet.</p>
                            <?php else: ?>
                                <ul class="collab_list background2">
                                <?php foreach($card->get_participations() as $participation): ?>
                                    <li><?= $participation->get_participant_fullName() ?> (<?= $participation->get_participant_email() ?>)</li>
                                <?php endforeach ?>
                                </ul>
                        <?php endif ?> 
                        </div>
                    </section>
                    <div class="popup_window " id="confirmDialogCard" hidden>
                        <h2 class="window_title"><i class="far fa-trash-alt"></i></h2>
                        <p id="popup_window_card_content"> ISSOU </p>
                    </div>  
                    <?php include('view_comments.php'); ?>
                </div>
            </article>
        </main>
    </body>
</html>