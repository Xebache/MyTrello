<section class="comment">
    <header>
        <h3>Comments</h3>
    </header>
    <div id="errorCommentEdit"> </div>
    <div>
        <ul class="background2">
        <?php if($card->has_comments()): ?>
            <?php foreach($card->get_comments() as $comment):?>
                <li class="flex_row start wrap">
                    <?php if(isset($show_comment) && $comment->can_be_show($show_comment)): ?>
                        <form action="comment/edit_confirm" method="post" class="commentAllForm">
                            <input type='text' name='id' value='<?= $comment->get_id() ?>' hidden>
                            <?php if(isset($edit)): ?>
                                <input type='text' name='edit' value='yes' hidden>
                            <?php endif;?>
                            <input type="text" name="body" value='<?= $comment->get_body() ?>' >
                            <input class="fas fa-paper-plane" type="submit" name="validate" value="&#xf1d8">
                            <input class="fas fa-arrow-left" type="submit" name="cancel" value="&#xf060">
                        </form>
                    <?php else: ?>
                        <p class="display"><?= $comment->get_body() ?> <b>by <strong><?= $comment->get_author_fullName() ?></strong>. <?= $comment->get_date_string() ?>.</b></p>
                    <?php endif; ?>
                    <ul class="flex_row start icon">
                    <?php if($user->is_author($comment) || $user->is_admin()): ?>
                        <li>
                            <form action='comment/edit' method='post' class="commentAllForm">
                                <input type='text' name='id' value='<?= $comment->get_id() ?>' hidden>
                                <?php if(isset($edit)): ?>
                                    <input type='text' name='edit' value='yes' hidden>
                                <?php endif;?>
                                <input type='submit' name='show' value="&#xf044"class="fas fa-edit" style="background:none">
                            </form>
                        </li>
                    <?php endif; ?>
                    <?php if($user->can_delete_comment($card,$comment)): ?>
                        <li>
                        <form action='comment/delete' method='post' class="commentAllForm">
                            <input type='text' name='id' value='<?= $comment->get_id() ?>' hidden>
                            <?php if(isset($edit)): ?>
                                <input type='text' name='edit' value='yes' hidden>
                            <?php endif;?>
                            <input type='submit' value="&#xf2ed" class="far fa-trash-alt" style="background:none">
                        </form>
                        </li>
                    <?php endif; ?>
                    </ul>
                </li>
            <?php endforeach ?>
        <?php else: ?>   
            <p class="display">This card has no comment yet.<p> 
        <?php endif ?>
        </ul>
    </div>
    <div id="commentErrorContainer"></div>
    <footer>
        <form class="add" action="Comment/add" method="post" id="addCommentForm">
            <input type='text' name='card_id' value='<?= $card->get_id() ?>' hidden>
            <input type='text' name='title' id="title" hidden>
            <?php if(isset($edit)): ?>
                <input type='text' name='edit' value='yes' hidden>
            <?php endif;?>
            <input type="text" name="body">
            <input type="submit" value="Add a comment">
        </form>
    </footer>
</section>