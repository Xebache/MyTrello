<?php

class CtrlTools {

    public static function breadcrumb(): string {
        $class_name = ucfirst($_GET["controller"]);
        $action_name = $_GET['action'];
        
        switch ($class_name) {

            case "Calendar":
                return "<div class='breadcrumb'>
                            <span class='breadcrumb-current'>Calendar</span>
                            <span class='breadcrumb-separator'>&lt;</span>
                            <span><a href='board/index'> Boards</a></span>
                        </div>";

            case "Board":

                if(isset($_GET["param1"])) {
                    $board_ID = $_GET["param1"];
                    $board = Board::get_by_id($board_ID);

                    switch($action_name) {

                        case "collaborators":
                            return "<div class='breadcrumb'>
                                        <span class='breadcrumb-current'>Collaborators</span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/board/" . $board->get_id() . "'>Board \"" . $board->get_title() .  "\"</a></span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/index'> Boards</a></span>
                                    </div>"; 

                        case "delete_confirm":
                            return "<div class='breadcrumb'>
                                        <span class='breadcrumb-current'>Delete</span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/board/" . $board->get_id() . "'>Board \"" . $board->get_title() .  "\"</a></span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/index'> Boards</a></span>
                                    </div>"; 
                                           
                        case "board" :
                            return "<div class='breadcrumb'>
                                        <span class='breadcrumb-current'>Board \"" . $board->get_title() .  "\"</span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/index'> Boards</a></span>
                                    </div>";
                    }
                }
                else {
                    return "<div class='breadcrumb'>
                                <span class='breadcrumb-current'>Boards</span>
                            </div>";
                }
                
            case "Card" :

                if(isset($_GET["param1"])) {
                    $card_ID = $_GET["param1"];
                    $card = Card::get_by_id($card_ID);
                    $board = $card->get_board();

                    switch($action_name) {
                        case "view":
                        case "edit":

                            return "<div class='breadcrumb'>
                                        <span class='breadcrumb-current'>Card \"" . $card->get_title() . "\"</span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/board/" . $board->get_id() . "'>Board \"" . $board->get_title() .  "\"</a></span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/index'> Boards</a></span>
                                    </div>"; 

                        case "delete_confirm":

                            return "<div class='breadcrumb'>
                                        <span class='breadcrumb-current'>Delete</span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='card/view/" . $card->get_id() . "'>Card \"" . $card->get_title() . "\"</a></span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/board/" . $board->get_id() . "'>Board \"" . $board->get_title() .  "\"</a></span>
                                        <span class='breadcrumb-separator'>&lt;</span>
                                        <span><a href='board/index'> Boards</a></span>
                                     </div>"; 
                    }

                }

            case "Column":
  
                if(isset($_GET["param1"])) {
                    $column_ID = $_GET["param1"];
                    $column = Column::get_by_id($column_ID);
                    $board = $column->get_board();

                    return "<div class='breadcrumb'>
                                <span class='breadcrumb-current'>Delete Column \"" . $column->get_title() . "\"</span>
                                <span class='breadcrumb-separator'>&lt;</span>
                                <span><a href='board/board/" . $board->get_id() . "'>Board \"" . $board->get_title() .  "\"</a></span>
                                <span class='breadcrumb-separator'>&lt;</span>
                                <span><a href='board/index'> Boards</a></span>
                            </div>";

                }

            case "User":
                return "<div class='breadcrumb'>
                            <span class='breadcrumb-current'>Manage Users</span>
                            <span class='breadcrumb-separator'>&lt;</span>
                            <span><a href='board/index'> Boards</a></span>
                        </div>";
                        
        }
        return "";
               
    }
    

}
       

