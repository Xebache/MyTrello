<?php

require_once "framework/Controller.php";
require_once "model/User.php";
require_once "model/Board.php";
require_once "model/Card.php";
require_once "CtrlTools.php";

class ControllerCalendar extends Controller {

    public function index() {
        $user = $this->get_user_or_redirect();

        $boards = [];
        if($user->is_admin()) {
            $boards = Board::get_all_boards();
        }
        else {
            $boards = Board::get_all_users_boards($user);
        }
        
        (new View("calendar"))->show(array(
            "user" => $user, 
            "boards" => $boards)
        );
    }

    public function get_boards() {
        $user = $this->get_user_or_redirect();

        $boards = [];
        if($user->is_admin()) {
            $boards = Board::get_all_boards();
        }
        else {
            $boards = Board::get_all_users_boards($user);
        }
        $res = [];
        foreach($boards as $board){
            $id = $board->get_id();
            array_push($res, $id);
        }
        echo json_encode($res);
    }


    private function make_events(array $boards, $start, $end) {
        $events = [];
        foreach($boards as $board) {
            $cards = Card::get_cards_with_duedate($board, $start, $end);
            foreach($cards as $card){
                $event = $card->card_to_calendar_event();
                array_push($events, $event);
            }
        }
        return $events;
    }

    public function boards_filter_service() {
        $this->get_user_or_redirect();
        $boards = [];
        if(isset($_POST['checked']) && isset($_POST['start']) && isset($_POST['end'])) {
            $start = $_POST['start'];
            $end = $_POST['end'];
            $ids = $_POST['checked'];
            $board_ids = explode( ',', $ids );
            foreach($board_ids as $board_id) {
                $board = Board::get_by_id($board_id);
                array_push($boards, $board);
            }
            $events = $this->make_events($boards, $start, $end);
    
            echo json_encode($events);
        }else {
            Tools::abort("Error : CalendarController can not generate web page with that function");
        }

    }

}