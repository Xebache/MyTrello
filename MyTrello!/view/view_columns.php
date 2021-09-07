<ul class="flex_row" id="sortableColumnsList">
    <?php foreach($board->get_columns() as $column): ?>
    <li id="<?= $column->get_id() ?>">
        <section class="column flex_column background3">
            <header class="column_title">
                <ul class="flex_row column_icons_list icons_list">
                    <li class="editColumnJS flex_row" hidden>
                        <h3 class="title"><?= $column->get_truncated_title(17) ?></h3>
                        <form action="column/edit/<?= $board->get_id() ?>" method="post" class="display_none columnTitleForm">
                            <input type="text" name="title" value="<?= $column->get_title() ?>">
                            <input type="text" name="id" value="<?= $column->get_id() ?>" hidden>
                        </form>
                    </li>
                    <li class="editColumn">
                        <form class="flex_row left edit_title editColumnTitle" action="column/edit" method="post" column_edit_id="<?= $column->get_id() ?>" >  
                            <input type="text" name="id" value="<?= $column->get_id() ?>" hidden>
                            <input type ="checkbox" class="column_checkbox" name="column_checkbox_<?= $column->get_id() ?>" id="column_checkbox_<?= $column->get_id() ?>" hidden>
                            <div class="shown flex_row">
                                <h3 class="title"><?= $column->get_truncated_title(17) ?></h3>
                                <label for="column_checkbox_<?= $column->get_id() ?>"><i class="fas fa-edit"></i></label>
                            </div>
                            <div class="hidden flex_row start">
                                <input class="columnTitle" type="text" name="title" value="<?= $column->get_title() ?>">
                                <label class="flex_row center" for="column_checkbox_<?= $column->get_id() ?>"><i class="fas fa-arrow-left"></i></label>
                                <input type="submit" class="fas fa-check" value="&#xf00c">
                            </div>
                        </form>
                    </li>
                    <li>
                        <form action='column/delete' method='post'>                         
                            <input type='text' class="c" name='idColumn' value='<?= $column->get_id() ?>' hidden>
                            <input type='submit' value="&#xf2ed" class="far fa-trash-alt dlt_button_column" style="background:none" delete_column_id="<?= $column->get_id() ?>" delete_column_title="<?= $column->get_title() ?>">
                        </form>
                    </li>
                    <!-- pas de left pour la première colonne -->
                    <?php if(!$column->is_first()): ?>
                    <li class="move">
                        <form action='column/left' method='post'>
                            <input type='text' name='id' value='<?= $column->get_id() ?>' hidden>
                            <input type='submit' value="&#xf0a8" class="fas fa-arrow-circle-left" style="background:none">
                        </form>
                    </li>
                    <?php endif; ?>
                    <!-- pas de right pour la dernière colonne -->
                    <?php if(!$column->is_last()): ?>
                    <li class="move">
                        <form action='column/right' method='post'>
                            <input type='text' name='id' value='<?= $column->get_id() ?>' hidden>
                            <input type='submit' value="&#xf0a9" class="fas fa-arrow-circle-right" style="background:none">
                        </form>
                    </li>
                    <?php endif; ?>
                </ul>
                <div id="columnTitleError<?= $column->get_id() ?>"></div>
                <?php if ($errors->has_errors("column", "edit", $column->get_id())): ?>
                    <?php include('errors.php'); ?>
                <?php endif; ?>
            </header>
            <section class="cardsContainer">
                <?php include("view_cards.php"); ?>
            </section>
            <footer>   
                <form class="add add_card addCardForm" action="card/add" method="post">
                    <input class='column_id' type='text' name='column_id' value='<?= $column->get_id() ?>' hidden>
                    <input type="text" name="title" id='title<?= $column->get_id() ?>' placeholder="Add a card">
                    <input type="submit" value="&#xf067" class="fas fa-plus"> 
                </form>
                <div id="cardError<?= $column->get_id() ?>"></div>
                <?php if ($errors->has_errors("card", "add", $column->get_id())): ?>
                    <?php include('errors.php'); ?>
                <?php endif; ?>
            </footer>
        </section>
    </li>
    <?php endforeach; ?>
</ul>