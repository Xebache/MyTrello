<?php
/**/
require_once "framework/Controller.php";
require_once "model/User.php";
require_once "model/Collaboration.php";
require_once "model/Board.php";
require_once "CtrlTools.php";
require_once "ValidationError.php";


class ControllerBoard extends Controller {

    public function board_title_exists_service() {
        $this->get_user_or_redirect();
        $res = "true";
        if(isset($_POST["title"]) && $_POST["title"] !== "") {
            $title = $_POST["title"];
            $board = Board::get_by_title($title);
            if($board)
                $res = "false";
                echo $res;
        }else{
            Tools::abort("Error : BoardController can not generate web page with that function");
        }
    }

    public function board_edit_service(){
        $this->get_user_or_redirect();
        $res = "true";
        if(isset($_POST["title"]) && $_POST["title"] !== "" && isset($_POST["board_id"])) {
            $title = $_POST["title"];
            $boardId = $_POST["board_id"];
            $board = Board::get_by_title($title);
            if($board && $board->get_id() != $boardId) {
                $res = "false";
            }
            echo $res;
        }else{
            Tools::abort("Error : BoardController can not generate web page with that function");
        }
    }

    public function delete_board_service(){
        $user = $this->get_user_or_redirect();
        if(!empty($_POST['id_board'])) {
            $board_id = $_POST['id_board'];
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                $board->delete();
            }
        } else{
            Tools::abort("Error : BoardController can not generate web page with that function");
        }    
    }

    

    public function index() {
        if(isset($_GET["param1"])) {
            $this->redirect();
        }

        $user = $this->get_user_or_false();
        $owners = [];
        $shared = [];
        $others = [];

        if($user) {
            $owners = $user->get_own_boards();
            $shared = $user->get_shared_boards();
            $others = $user->get_others_boards();
        }

        (new View("boardlist"))->show(array(
            "user"=>$user, 
            "owners" => $owners,
            "shared" => $shared,
            "others" => $others,
            "errors" => ValidationError::get_error_and_reset()
            )
        );
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function board() {
        $user = $this->get_user_or_redirect();

        if(isset($_GET["param1"])) {
            $board = Board::get_by_id($_GET["param1"]);

            if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) {
                $columns = $board->get_columns();
                (new View("board"))->show(array(
                        "user"=>$user,
                        "board" => $board,
                        "columns" => $columns,
                        "errors" => ValidationError::get_error_and_reset()
                    )
                );
                die;
            }
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // ajout nouveau Board
    public function add() {
        $user = $this->get_user_or_redirect();

        if (!empty($_POST["title"])) {
            $title = $_POST["title"];
            $board = new Board($title, $user, null, new DateTime(), null);

            $error = new ValidationError($board, "add");
            $error->set_messages_and_add_to_session($board->validate());

            if($error->is_empty()) {
                $board->insert();
                $this->redirect("board", "board", $board->get_id());
            }
        }
        $this->redirect();
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //edit titre Board
    public function edit() {
        $user = $this->get_user_or_redirect();
        $error = new ValidationError();

        if (isset($_POST["id"])) {

            $board_id = $_POST["id"];
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 

                if(!empty($_POST["title"])) {  
                    $title = $_POST["title"];

                    if ($board->get_title() != $title) {
                        $board->set_title($title);
                        $error = new ValidationError($board, "edit");
                        $error->set_messages_and_add_to_session($board->validate());
                    }

                    if($error->is_empty()) {
                        $board->update();
                    }

                    $this->redirect("board", "board", $board_id);
                }

                $error = new ValidationError($board, "edit");
                $msg[] = "Title can not be empty";
                $error->set_messages_and_add_to_session($msg);
                $this->redirect("board", "board", $board_id);
            }
        }
        $this->redirect();
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // si pas de colonne -> delete -> redirect index
    // sinon -> delete_confirm
    public function delete() {
        $user = $this->get_user_or_redirect();
        if(!empty($_POST['id'])) {
            $board_id = $_POST['id'];
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 
                $columns = $board->get_columns();

                if (count($columns) == 0) { 
                    $board->delete();
                    $this->redirect();
                } else {
                    $this->redirect("board", "delete_confirm", $board_id);
                }
            }
        } 
        $this->redirect();        
    }

    //mise en place de view_delete_confirm
    public function delete_confirm() {
        $user = $this->get_user_or_redirect();

        if(!empty($_GET["param1"])) {
            $board_id = $_GET["param1"];
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && $user->is_admin_or_owner($board)) {
                (new View("delete_confirm"))->show(array(
                    "user" => $user, 
                    "instance" => $board)
                );
                die;
            }
            $this->redirect("board", "board", $board_id);
        }
        $this->redirect();
    }

    //exécution du delete ou cancel de delete_confirm
    public function remove() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST["id"])) {
            $board_id = $_POST["id"];
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && isset($_POST["delete"]) && $user->is_admin_or_owner($board)) {
                $board->delete();
                $this->redirect();
            }
            $this->redirect("board", "board", $board->get_id());
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function collaborators() {
        $user = $this->get_user_or_redirect();
        if(!empty($_GET["param1"])) {
            $board_id = $_GET["param1"];
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && $user->is_admin_or_owner($board)) {
                (new View("collaborators"))->show(array(
                    "user" => $user,
                    "board" => $board)
                );
                die;
            }
        }
        $this->redirect();
    }   
    
}


