<ul class="flex_column center sortableCardsList" custom_id="<?= $column->get_id() ?>">
 <?php foreach($column->get_cards() as $card): ?>
    <li custom_id="<?= $card->get_id() ?>">
        <section class="flex_column display_cards <?= $card->due_card() ?>">           
            <header class="flex_row">
                <h4><?= $card->get_truncated_title(30) ?></h4>
                <?php include("partilist_icons.php"); ?>
            </header>
            <footer>
                <ul class="flex_row icons_list">
                    <li>
                        <a href="card/view/<?= $card->get_id() ?>"><i class="far fa-eye"></i></a>
                    </li>
                    <?php if($card->has_comments()): ?>
                    <li>
                        <p>&#x28;<?= $card->get_comments_count() ?>&nbsp;<i class="fa fa-comment-o"></i>&#x29;</p>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="card/edit/<?= $card->get_id() ?>"><i class="fas fa-edit"></i></a>
                    </li>
                    <li>
                        <form action='card/delete' method='post'>
                            <input type='text' name='id' value='<?= $card->get_id() ?>' hidden>
                            <input type='submit' value="&#xf2ed" class="far fa-trash-alt dlt_button_card" style="background:none" delete_card_id="<?= $card->get_id() ?>" delete_card_title="<?= $card->get_title() ?>" delete_card_idBoard="<?= $card->get_board_id()?>">
                        </form>
                    </li>
                    <!-- pas de down pour la dernière carte de la colonne -->
                    <?php if(!$card->is_last()): ?>
                    <li class="move">
                        <form action='card/down' method='post'>
                            <input type='text' name='id' value='<?= $card->get_id() ?>' hidden>
                            <input type='submit' value="&#xf0ab" class="fas fa-arrow-circle-down" style="background:none">
                        </form>
                    </li>
                    <?php endif; ?>
                    <!-- pas de up pour la première carte de la colonne -->
                    <?php if(!$card->is_first()): ?>
                    <li class="move">
                        <form action='card/up' method='post'>
                            <input type='text' name='id' value='<?= $card->get_id() ?>' hidden>
                            <input type='submit' value="&#xf0aa" class="fas fa-arrow-circle-up" style="background:none">
                        </form>
                    </li>
                    <?php endif; ?>
                    <!-- pas de left pour les cartes de la première colonne -->
                    <?php if(!$column->is_first()): ?>
                    <li class="move">
                        <form action='card/left' method='post'>
                            <input type='text' name='id' value='<?= $card->get_id() ?>' hidden>
                            <input type='submit' value="&#xf0a8" class="fas fa-arrow-circle-left" style="background:none">
                        </form>
                    </li>
                    <?php endif; ?>
                    <!-- pas de right pour les cartes de la dernière colonne -->
                    <?php if(!$column->is_last()): ?>
                    <li class="move">
                        <form action='card/right' method='post'>
                            <input type='text' name='id' value='<?= $card->get_id() ?>' hidden>
                            <input type='submit' value="&#xf0a9" class="fas fa-arrow-circle-right" style="background:none">
                        </form>
                    </li>
                    <?php endif; ?>
                </ul>
            </footer> 
        </section>
    </li>
    <?php endforeach; ?>
</ul>