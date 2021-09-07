<?php

require_once "framework/Controller.php";
require_once "model/Column.php";
require_once "model/User.php";
require_once "ValidationError.php";
require_once "CtrlTools.php";

class ControllerColumn extends Controller {
    
    public function is_column_empty_service() {
        $this->get_user_or_redirect();
        $res = "false";
        if(isset($_POST["id_column"])) {
            $column_id = $_POST["id_column"];
            $column = Column::get_by_id($column_id);
            if($column->is_empty()){
                $res = "true";
            }
        }
        echo $res;
    }

    public function column_title_exists_service() {
        $this->get_user_or_redirect();
        $res = "true";
        if(isset($_POST["title"]) && $_POST["title"] !== "" && isset($_POST["id"])) {
            $board_id = $_POST["id"];
            $title = $_POST["title"];
            $board = Board::get_by_id($board_id);
            $column = Column::get_by_title($title, $board);
            if($column)
                $res = "false";
            echo $res;
        }else{
            Tools::abort("Error : ColumnController can not generate web page with that function");
        }
    }

    public function column_edit_service(){
        $this->get_user_or_redirect();
        $res = "true";
        if(isset($_POST["title"]) && $_POST["title"] !== "" && isset($_POST["column_id"]) && isset($_POST["board_id"])) {
            $board_id = $_POST["board_id"];
            $column_id = $_POST["column_id"];
            $title = $_POST["title"];
            $board = Board::get_by_id($board_id);
            $column = Column::get_by_title($title, $board);
            if($column && $column->get_id() != $column_id) {
                $res = "false";
            }
            echo $res;
        }
        else{
            Tools::abort("Error : ColumnController can not generate web page with that function");
        }
    }

    public function column_move_service() {
        $user = $this->get_user_or_redirect();
        if(isset($_POST['data'])) {
            $ids = $_POST['data'];
            for($i = 0; $i < count($ids); ++$i) {
                $column_id = $ids[$i];
                $column = Column::get_by_id($column_id);
                if(!is_null($column) &&  $user->is_admin_or_owner_or_collaborator($column->get_board())){
                    $column->set_position($i);
                    $column->update();
                }
            }
        }else{
            Tools::abort("Error : ColumnController can not generate web page with that function");
        }
    }

    public function column_delete_service(){
        $user = $this->get_user_or_redirect();
        if(!empty($_POST['id_column'])){
            $column_id=$_POST['id_column'];
            $column = Column::get_by_id($column_id);
            $board = $column->get_board();

            if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                $column->delete();
                Column::decrement_following_columns_position($column);
            }
        }else{
            Tools::abort("Error : ColumnController can not generate web page with that function");
        }
    }

    public function index() {
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function right() { 
        $user = $this->get_user_or_redirect();
        $board = null;

        if (isset($_POST["id"])) {
            $column = Column::get_by_id($_POST["id"]);

            if(!is_null($column)){
                $board = $column->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $column->move_right();
                    $this->redirect("board", "board", $column->get_board_id());
                }
            }
        }
        $this->redirect();
    }

    public function left() {
        $user = $this->get_user_or_redirect();
        $board = null;

        if (isset($_POST["id"])) {

            $column = Column::get_by_id($_POST["id"]);
            if(!is_null($column)){
                $board = $column->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $column->move_left();
                    $this->redirect("board", "board", $column->get_board_id());
                }
            }
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function delete() {
        $user = $this->get_user_or_redirect();
        $board = null;

        if(isset($_POST['idColumn'])) {
            $column_id = $_POST['idColumn'];
            $column = Column::get_by_id($column_id);

            if(!is_null($column)){
                $board = $column->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $cards = $column->get_cards();

                    if (count($cards) == 0) {
                        $column->delete();
                        Column::decrement_following_columns_position($column);
                        $this->redirect("board", "board", $column->get_board_id());
                    } 
                    
                    else {
                        $this->redirect("column", "delete_confirm", $column->get_id());
                    }
                }
            }
        } 
        $this->redirect();        
    }

    public function delete_confirm() {
        $user = $this->get_user_or_redirect();
        $board = null;

        if (isset($_GET["param1"])) {
            $column_id = $_GET["param1"];
            $column = Column::get_by_id($column_id);

            if(!is_null($column)){
                $board = $column->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $cards = $column->get_cards();

                    if (count($cards)) {
                        (new View("delete_confirm"))->show(array(
                            "user"=>$user, 
                            "instance"=>$column
                            ));
                        die;
                    }
                }
                $this->redirect("board", "board", $column->get_board_id());
            }
        }
        $this->redirect();
    }

    //exécution du delete ou cancel de delete_confirm
    public function remove() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST["id"])) {
            $column = Column::get_by_id($_POST["id"]);
           if(!is_null($column)){
                $board = $column->get_board();

                if(isset($_POST["delete"]) && !is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    $column->delete();
                    Column::decrement_following_columns_position($column);
                }
                $this->redirect("board", "board", $column->get_board_id());
            }
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function add() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["id"]) && !empty($_POST["title"])) {
            $board_id = $_POST["id"];
            $title = $_POST["title"];
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                $column = Column::create_new($title, $board);
                $error = new ValidationError($column, "add");
                $error->set_messages_and_add_to_session($column->validate());

                if($error->is_empty()) {
                    $column->insert();
                }  
                $this->redirect("board", "board", $board_id);
            }
               
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // edit titre Column
    public function edit() {
        $user = $this->get_user_or_redirect();
        $error = new ValidationError();

        if (isset($_POST["id"]) && !empty($_POST["title"])) {
            $id = $_POST["id"];
            $title = $_POST["title"];
            $column = Column::get_by_id($id);

            if(!is_null($column)){
                $board = $column->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                    if ($column->get_title() !== $title) {
                        $column->set_title($title);
                        $error = new ValidationError($column, "edit");
                        $error->set_messages_and_add_to_session($column->validate());
                    }

                    if ($error->is_empty()) {
                        $column->update();
                    }
                }
                $this->redirect("board", "board", $column->get_board_id());
            }
        }
        $this->redirect();
    }

}