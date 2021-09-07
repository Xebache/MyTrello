<?php

require_once "framework/Controller.php";
require_once "model/User.php";
require_once "model/Participation.php";
require_once "model/Card.php";
require_once "CtrlTools.php";
require_once "ValidationError.php";

class ControllerCard extends Controller {

    public function card_title_exists_service() {
        $this->get_user_or_redirect();
        $res = "true";
        if(isset($_POST["title"]) && $_POST["title"] !== "" && isset($_POST["column_id"])) {
            $column_id = $_POST["column_id"];
            $title = $_POST["title"];
            $column = Column::get_by_id($column_id);
            $card = Card::get_by_title($title, $column);
            if($card)
                $res = "false";
            echo $res;
        }else{
            Tools::abort("Error : CardController can not generate web page with that function");
        }
    }

    public function card_edit_service() {
        $this->get_user_or_redirect();
        $res = "true";
        if(isset($_POST["title"]) && $_POST["title"] !== "" && isset($_POST["card_id"])) {
            $cardId = $_POST["card_id"];
            $card=Card::get_by_id($cardId);
            if(!is_null($card)){
                $card->set_title($_POST["title"]);
                if(!$card->title_is_unique_update())
                    $res = "false";
            }
            echo $res;
        }else{
            Tools::abort("Error : CardController can not generate web page with that function");
        }
    }

    public function card_move_service() {
        $user =  $this->get_user_or_redirect();
        if(isset($_POST["card_id"]) && isset($_POST["column_id"]) && isset($_POST["pos"])) {
            $card_id = $_POST["card_id"];
            $card = Card::get_by_id($card_id);
            $column_id = $_POST["column_id"];
            $end_column = Column::get_by_id($column_id);
            $end_pos = $_POST["pos"];

            if(!is_null($card) && !is_null($end_column) &&  $user->is_admin_or_owner_or_collaborator($end_column->get_board())){
                Card::decrement_following_cards_position($card);
                $card->set_column($end_column);
                $card->set_position($end_pos);
                Card::increment_following_cards_position($card);
                $card->update();
            }               
        }else{
            Tools::abort("Error : CardController can not generate web page with that function");
        }
    }

    public function card_delete_service(){
        $user= $this->get_user_or_redirect();
        if(!empty($_POST['id_card'])){
            $card_id = $_POST['id_card'];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)) {
                $board = $card->get_board();
                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    Card::decrement_following_cards_position($card);
                    $card->delete();
                }
            }
        }else{
            Tools::abort("Error : CardController can not generate web page with that function");   
        }
    }

    
    public function index() {
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function left() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["id"])) {
            $card_id = $_POST["id"];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)){
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $card->move_left();
                    $this->redirect("board", "board", $card->get_board_id());
                }
            }
        }
        $this->redirect();
    }

    public function right() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["id"])) {
            $card_id = $_POST["id"];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)){
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $card->move_right();
                    $this->redirect("board", "board", $card->get_board_id());
                }
            }
        }
        $this->redirect();
    }

    public function up() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["id"])) {
            $card_id = $_POST["id"];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)){
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $card->move_up();
                    $this->redirect("board", "board", $card->get_board_id());
                }
            }
        }
        $this->redirect();
    }

    public function down() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["id"])) {
            $card_id = $_POST["id"];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)){
                $board = $card->get_board();
    
                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $card->move_down();
                    $this->redirect("board", "board", $card->get_board_id());
                }
            }
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function add() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["column_id"])) {
            $column_id = $_POST["column_id"];
            $column = Column::get_by_id($column_id);
            $board = $column->get_board();

            if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 

                if (!empty($_POST["title"])) {
                    $title = $_POST["title"];
                    
                    $card = Card::create_new($title, $user, $column_id);

                    if(!is_null($card)){
                        $error = new ValidationError($card, "add");
                        $error->set_messages_and_add_to_session($card->validate());
                        $error->set_id($column_id);

                        if($error->is_empty()){                
                            $card->insert(); 
                        }
                    }
                }
                $this->redirect("board", "board", $board->get_id());
            }
        } 
        $this->redirect();
    }
        
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function update(){
        $user = $this->get_user_or_redirect();
        $board = null;

        if (isset($_POST['id'])) {

            $card_id = $_POST['id'];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)){
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    if(isset($_POST['body'])) {
                        $body = $_POST['body'];
                        $card->set_body($body);
                    }
        
                    if(isset($_POST['title'])) {
                        $title = $_POST['title'];
                        $card->set_title($title);
                    }
        
                    if(!empty($_POST['dueDate'])) {
                        $dueDate = $_POST['dueDate'];
                        $card->set_dueDate(new DateTime($dueDate));
                    }
        
                    $error = new ValidationError($card, "update");
                    $error->set_messages_and_add_to_session($card->validate_update());

                    if($error->is_empty()) {  
                        $card->update(); 
                    }
                    $this->redirect("card", "view", $card_id);
                }
            }
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function delete() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST['id'])) {
            $card_id = $_POST['id'];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)) {
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $this->redirect("card","delete_confirm",$card->get_id());
                }
            }
        }
        $this->redirect();
    }

    public function delete_confirm(){
        $user = $this->get_user_or_redirect();

        if (isset($_GET['param1'])) {
            $card_id = $_GET['param1'];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)) {
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    (new View("delete_confirm"))->show(array(
                        "user"=>$user, 
                        "instance"=>$card
                        ));
                    die;
                }
            }
        }
        $this->redirect();
    }

    public function remove() {

        $user = $this->get_user_or_redirect();

        if(isset($_POST["id"])) {
            $card_id = $_POST["id"];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)) {
                $board = $card->get_board();

                if(isset($_POST["delete"]) && !is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    Card::decrement_following_cards_position($card);
                    $card->delete();
                }
            }
            $this->redirect("board", "board", $card->get_column()->get_board_id());
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function edit_link(){
        $user = $this->get_user_or_redirect();

        if (isset($_POST['id'])) {
            $card_id = $_POST['id'];
            $card = Card::get_by_id($card_id);
            $card = Card::get_by_id($card_id);

            if(!is_null($card)) {
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $this->redirect("card", "edit", $card->get_id());
                }
            }
        }
        $this->redirect();
    }

    public function edit(){
        $user = $this->get_user_or_redirect();
        $card = null;

        if (isset($_GET['param1'])) { 
            $card_id = $_GET['param1'];
            $card = Card::get_by_id($card_id);
            $card = Card::get_by_id($card_id);

            if(!is_null($card)) {
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $comments = $card->get_comments();
                    $edit="yes";

                    if(isset($_GET['param2'])){
                        (new View("card_edit"))->show(array(
                            "user" => $user, 
                            "card" => $card, 
                            "comment" => $comments,
                            "show_comment" => $_GET['param2'],
                            "edit" => $edit,
                            "errors" => ValidationError::get_error_and_reset()
                            )
                        );
                        die;
                    } 
                    
                    else {
                        (new View("card_edit"))->show(array(
                            "user" => $user, 
                            "card" => $card, 
                            "comment" => $comments,
                            "edit" => $edit,
                            "errors" => ValidationError::get_error_and_reset()
                            )
                        );
                        die;
                    }
                }
            }
        }
        $this->redirect();       
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function view(){

        $user = $this->get_user_or_redirect();
        $card = null;

        if (isset($_GET['param1'])) { 
            $card_id = $_GET['param1'];
            $card = Card::get_by_id($card_id);
            $card = Card::get_by_id($card_id);

            if(!is_null($card)) {
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $comments = $card->get_comments();

                    if(isset($_GET['param2'])){
                        (new View("card"))->show(array(
                            "user" => $user, 
                            "card" => $card, 
                            "comment" => $comments,
                            "show_comment" => $_GET['param2'],
                            "errors" => ValidationError::get_error_and_reset()
                            )
                        );
                        die;
                    } 
                    
                    else {
                    (new View("card"))->show(array(
                        "user" => $user, 
                        "card" => $card, 
                        "comment" => $comments,
                        "errors" => ValidationError::get_error_and_reset()
                            )
                        );
                        die;
                    }
                }
            } 
        }
        $this->redirect();       
    } 

}
