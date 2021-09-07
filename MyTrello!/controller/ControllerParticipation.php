<?php

require_once "framework/Controller.php";
require_once "model/Participation.php";
require_once "CtrlTools.php";

class ControllerParticipation extends Controller {

    public function index() {
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function add() {
        $user = $this->get_user_or_redirect();

        if (isset($_POST["card_id"])) {
            $card_id = $_POST["card_id"];
            $card = Card::get_by_id($card_id);
            $board = $card->get_board();
            if(!is_null($board) && $user->is_admin_or_owner_or_collaborator($board)) {
                if(isset($_POST["user_id"]) && !is_null($_POST["user_id"])) {
                    $user_id = $_POST["user_id"];
                    $participant = User::get_by_id($user_id);
                    if($participant->is_collaborator($board)){
                        $participation = new Participation($participant, $card);
                        $participation->insert();
                    }
                }
            }
            $this->redirect("card", "edit", $card_id);
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function delete() {
        $user = $this->get_user_or_redirect();
        if (isset($_POST["card_id"]) && isset($_POST["user_id"])) {
            $user_id = $_POST["user_id"];
            $card_id = $_POST["card_id"]; 
            $card = Card::get_by_id($card_id);
            $participant = User::get_by_id($user_id);

            if(!is_null($card) && !is_null($participant)) {
                $board = $card->get_board();

                if(!is_null($board) && $participant->is_collaborator($board)) {
                    $participation = new Participation($participant, $card);

                    if($user->is_admin_or_owner_or_collaborator($board)) {
                        $participation->delete();
                        $this->redirect("card", "edit", $card_id);
                    }
                }
            }
        }
        $this->redirect();
    }

}