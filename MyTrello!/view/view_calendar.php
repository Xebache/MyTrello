<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
    <title>Calendar</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
    
    <link href='lib/js/fullcalendar-5.6.0/main.css' rel='stylesheet' />
    <link href="css/styles2.css" rel="stylesheet" type="text/css"/>
    <script src="lib/js/jquery-3.6.0/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="lib/js/jquery-ui-1.12.1.ui-lightness/jquery-ui.js" type="text/javascript"></script>
    <script src='lib/js/fullcalendar-5.6.0/main.js'></script>
    <script src="lib/js/calendar.js"></script>
    <link href='lib/js/fullcalendar-5.6.0/main.css' rel='stylesheet' />
</head>
<body class="calendarDisplay">
    <header id="main_header">
        <?php include("menu.php"); ?>
    </header>
    <main class="flex_column center">
        <ul class="flex_row center calendarCheckboxes">
            <?php foreach($boards as $board): ?>
            <li>
                <input type="checkbox" value="<?= $board->get_id() ?>" class="board_checkbox" checked="true">
                <label><?= $board->get_title() ?></label>
            </li>
            <?php endforeach ?>
        </ul> 
        <div class="container">
            <div id='calendar'></div>
            <div class="popup_card" id="showCard" hidden>
                <div class="popup_card_info"></div>
            </div>
        </div>
    </main>
    <footer>
       
    </footer>
</body>
</html>
