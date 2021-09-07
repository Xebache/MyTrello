<?php

require_once "framework/Controller.php";
require_once "model/Collaboration.php";
require_once "CtrlTools.php";

class ControllerCollaboration extends Controller {

    public function get_collaborators_service() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["board_id"])) {
            $board_id = $_POST["board_id"];
            $board = Board::get_by_id($board_id);

            if($board && $user->is_admin_or_owner($board)) {
                Collaboration::collaborations_to_JSON($board);
            }
        }
    }

    public function get_others_service() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["board_id"])) {
            $board_id = $_POST["board_id"];
            $board = Board::get_by_id($board_id);

            if($board && $user->is_admin_or_owner($board)) {
                Collaboration::others_to_JSON($board, $user);
            }
        }
    }

    public function delete_collaboration_service() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST['board_id']) && isset($_POST['collaborator_id'])) {
            $board_id = $_POST['board_id'];
            $collaborator_id = $_POST['collaborator_id'];
            $board = Board::get_by_id($board_id);
            $collaborator = User::get_by_id($collaborator_id);

            if(!is_null($board) && !is_null($collaborator)) {
                $collaboration = new Collaboration($collaborator, $board);

                if($user->is_admin_or_owner($board)) {
                    $collaboration->delete();
                }
            }
        }
    }

    public function add_collaboration_service() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST['board_id']) && isset($_POST['collaborator_id'])) {
            $board_id = $_POST['board_id'];
            $collaborator_id = $_POST['collaborator_id'];
            $board = Board::get_by_id($board_id);
            $collaborator = User::get_by_id($collaborator_id);

            if(!is_null($board) && !is_null($collaborator)) {
                $collaboration = new Collaboration($collaborator, $board);

                if($user->is_admin_or_owner($board)) {
                    $collaboration->insert();
                }
            }
        }
    }


    public function index() {
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function add() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["board_id"])) {
            $board_id = $_POST["board_id"];
            $board = Board::get_by_id($board_id);
            if(!is_null($board) && $user->is_admin_or_owner($board)) {
                if(isset($_POST["user_id"]) && !is_null($_POST["user_id"])) {
                    $user_id = $_POST["user_id"];
                    $collaborator = User::get_by_id($user_id);
                    $collaborators = $board->get_others($user);
                    if(count($collaborators) != 0 && !is_null($collaborator)) {
                        $collaboration = new Collaboration($collaborator, $board);
                        $collaboration->insert();
                    }
                }
                $this->redirect("board", "collaborators", $board_id);
            }
        } 
        $this->redirect();    
    }
        

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function delete() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST['board_id']) && isset($_POST['user_id'])) {
            $board_id = $_POST['board_id'];
            $collaborator_id = $_POST['user_id'];
            $board = Board::get_by_id($board_id);
            $collaborator = User::get_by_id($collaborator_id);

            if(!is_null($board) && !is_null($collaborator)) {
                $collaboration = new Collaboration($collaborator, $board);
                $participations = $collaboration->get_participations();

                if($user->is_admin_or_owner($board)) {

                    if(count($participations) == 0 ) {
                        $collaboration->delete();
                        $this->redirect("board", "collaborators", $board_id);
                    }

                    $this->redirect("collaboration", "delete_confirm", $collaborator_id, $board_id); 
                }
            }
        }
        $this->redirect();
    }

    public function delete_confirm() {
        $user = $this->get_user_or_redirect();

        if(isset($_GET["param1"]) && isset($_GET["param2"])) {
            $collaborator_id = $_GET["param1"];
            $board_id = $_GET["param2"];
            $collaborator = User::get_by_id($collaborator_id);
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && !is_null($collaborator)) {
                $collaboration = new Collaboration($collaborator, $board);

                if($user->is_admin_or_owner($board)) {
                    (new View("delete_confirm"))->show(array(
                        "user" => $user, 
                        "instance" => $collaboration)
                    );
                    die;
                }
            }
            $this->redirect("board", "board", $board_id);
        }
        $this->redirect();
    }

    public function remove() {
        $user = $this->get_user_or_redirect();
        if(isset($_POST['board_id']) && isset($_POST['id'])) {
            $collaborator_id = $_POST['id'];
            $board_id = $_POST['board_id'];
            $collaborator = User::get_by_id($collaborator_id);
            $board = Board::get_by_id($board_id);

            if(!is_null($board) && !is_null($collaborator)) {
                $collaboration = new Collaboration($collaborator, $board);
            
                if(isset($_POST["delete"]) && $user->is_admin_or_owner($board)) {
                    $collaboration->delete();
                }
            }
            $this->redirect("board", "collaborators", $board_id);
        }
        $this->redirect();
    }

}