<?php

require_once "framework/Controller.php";
require_once "ValidationError.php";
require_once "model/Card.php";
require_once "model/User.php";


class ControllerComment extends Controller {

    public function index() {
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    public function delete() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST['id'])) {   
            $comment_id = $_POST['id'];       
            $comment = Comment::get_by_id($comment_id);

            if($comment->can_be_deleted($user)){
                $comment->delete(); 
            }
            $this->redirect("card", "view", $comment->get_card_id());
        }

        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function edit() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST['id'])) {            
            $comment_id = $_POST['id'];
            $comment = Comment::get_by_id($comment_id);

            if(!is_null($comment) && ($comment->can_be_modified($user))){

                if(isset($_POST['edit'])) {
                    $this->redirect("card","edit", $comment->get_card_id(), $comment_id);
                } else {
                    $this->redirect("card","view", $comment->get_card_id(), $comment_id);
                }
            }
        }
        $this->redirect();        
    }

    public function edit_confirm() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST['id'])){            
            $comment_id = $_POST['id'];
            $comment = Comment::get_by_id($comment_id);

            if(!is_null($comment) && ($comment->can_be_modified($user))){

                if(isset($_POST['validate'])){

                    if(!empty($_POST['body'])){
                        $body = $_POST['body'];
                        $comment->set_body($body);

                        if($comment->validate()){
                            $comment->update();
                        }
                    }
                }
                $this->card_redirect($comment->get_card_id());
            }
        }
        $this->redirect();        
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function add(){
        $user = $this->get_user_or_redirect();

        if(isset($_POST['card_id'])) {
            $card_id = $_POST['card_id'];
            $card = Card::get_by_id($card_id);

            if(!is_null($card)) {
                $board = $card->get_board();

                if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) { 

                    if(!empty($_POST['body'])) {
                        $body = $_POST['body'];
                        $comment = new Comment($body, $user, $card);

                        if($comment->validate()){
                            $comment->insert();
                        }
                    }
                }
            }
            $this->card_redirect($card_id);
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    private function card_redirect($card_id) {
        if(isset($_POST['edit'])){
            $this->redirect("card", "edit", $card_id);
        } else {
            $this->redirect("card", "view", $card_id);
        }
    }
}

?>