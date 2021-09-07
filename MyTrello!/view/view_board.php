<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
    <title>Boards "<?= $board->get_title() ?>"</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
    <link href="css/styles2.css" rel="stylesheet" type="text/css"/>
    <script src="lib/js/jquery-3.6.0/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/js/jquery-ui-1.12.1.ui-lightness/jquery-ui.js" type="text/javascript"></script>
    <script src="lib/js/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>
    <script src="lib/js/board-edit.js" type="text/javascript"></script>
    <script src="lib/js/board-add.js" type="text/javascript"></script>
    <script src="lib/js/board-delete.js" type="text/javascript"></script>
    <script src="lib/js/board-drag.js" type="text/javascript"></script>
</head>
<body class="boardMain">
	<header id="main_header">
     <?php include("menu.php"); ?>
	</header>
	<main class="board">
        <article>
            <header class="flex_row">
            <div>
                <div class="flex_row board_title">
                    <ul class="flex_row">
                        <li class="editBoardJS flex_row" hidden>
                            <h2 class="title">Board "<?= $board->get_title() ?>"</h2>
                            <form action="board/edit/<?= $board->get_id() ?>" method="post" class="display_none" id="boardTitleForm">
                                <input type="text" name="title" value="<?= $board->get_title() ?>">
                                <input id="board_id" type="text" name="id" value="<?= $board->get_id() ?>" hidden>
                            </form>
                            <?php include("collablist_icons.php"); ?>
                        </li>
                        <?php if ($user->is_admin_or_owner_or_collaborator($board)): ?>
                        <li class="editBoard">
                            <form class="flex_row edit_title" action="board/edit/<?= $board->get_id() ?>" method="post">
                                <input id="board_id" type="text" name="id" value="<?= $board->get_id() ?>" hidden>
                                <input type ="checkbox" class="board_checkbox" id="board_checkbox" hidden>
                                <div class="shown flex_row">
                                    <h2 class="title">Board "<?= $board->get_title() ?>"</h2>
                                    <?php include("collablist_icons.php"); ?>
                                    <label for="board_checkbox" class="editBoard"><i class="fas fa-edit"></i></label>
                                </div>
                                <div class="hidden flex_row start">
                                    <input type="text" name="title" value="<?= $board->get_title() ?>">
                                    <label class="flex_row center" for="board_checkbox"><i class="fas fa-arrow-left"></i></label>
                                    <input class="fas fa-check" type="submit" value="&#xf00c">
                                </div>
                            </form>
                        </li>
                        <?php endif; ?>
                        <?php if ($user->is_admin_or_owner($board)): ?>
                        <li class="link">
                            <a class="collab" href="board/collaborators/<?= $board->get_id() ?>"><i class="fas fa-users"></i></a>
                        </li>
                        <li>
                            <form action="board/delete" method="post">
                                <input type="text" id='boardId' name="id" value="<?= $board->get_id() ?>" hidden>
                                <button class="trash far fa-trash-alt" id="delete_btn" type="submit" value="&#xf2ed" style="background:none"></button>
                            </form>    
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <div id="boardTitleError"></div>
                    <?php if ($errors->has_errors("board", "edit", $board->get_id())): ?>
                        <?php include("errors.php"); ?>
                    <?php endif; ?>
                </div>
                <p class="credit">Created <?= $board->get_created_intvl() ?> by <strong>'<?= $board->get_owner_fullName() ?>'</strong>. <?= $board->get_modified_intvl() ?>.</p>
            </div>
                
            </header>
            <div class="flex_row"> 
                <div class="columnsContainer"> 
                    <?php include("view_columns.php"); ?>
                </div>
                <div class="flex_column">
                    <aside class="flex_column center background3">
                        <form id="addColumnForm" class="add" action="column/add" method="post">
                            <input id="id" type="text" name="id" value="<?= $board->get_id() ?>" hidden>
                            <input type="text" name="title" placeholder="Add a column">
                            <input type="submit" value="&#xf067" class="fas fa-plus">
                        </form>
                        <?php if ($errors->has_errors("column", "add")): ?>
                            <?php include("errors.php"); ?>
                        <?php endif; ?>  
                    </aside>  
                    <div id="addColumnErrors"></div>
                </div>
            </div>
            <div class="popup_window" id="confirmDialog" hidden>
                 <h2 class="window_title"><i class="far fa-trash-alt"></i></h2>
                <p class="subtitle">Are you sure ?</h4>
                <hr>
                <p>Do you really want to delete this board (<?= $board->get_title() ?>) ?</p>
                <p>This process cannot be undone.</p>
            </div>
            <div class="popup_window" id="confirmDialogColumn" hidden>
                <h2 class="window_title"><i class="far fa-trash-alt"></i></h2>
                <p id="popup_window_column_content"> ISSOU </p>
            </div> 
            <div class="popup_window" id="confirmDialogCard" hidden>
                <h2 class="window_title"><i class="far fa-trash-alt"></i></h2>
                <p id="popup_window_card_content"> ISSOU </p>
            </div>   
        </article>
    </main>
    <footer>
        
    </footer>
</body>
</html>