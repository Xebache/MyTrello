<?php

require_once "User.php";
require_once "framework/Model.php";
require_once "Participation.php";

class Collaboration extends Model {
    private User $collaborator;
    private Board $board;

    public function __construct(User $collaborator, Board $board) {
        
        if(is_null($collaborator) || is_null($board)){
            return null;
        }

        $this->collaborator = $collaborator;
        $this->board = $board;
    }


    //    GETTERS    //

    public function get_collaborator(): User {
        return $this->collaborator;
    }

    public function get_collaborator_id(): string {
        return $this->collaborator->get_id();
    }

    public function get_collaborator_first_letters(): string {
        return $this->collaborator->get_first_letters();
    }

    public function get_board(): Board {
        return $this->board;
    }

    public function get_board_id(): string {
        return $this->board->get_id();
    }

    public function get_collaborator_fullName() : string {
        return $this->collaborator->get_fullName();
    }

    public function get_collaborator_email() : string {
        return $this->collaborator->get_email();
    }


    //    QUERIES    //

    public static function get_collaboration(int $board_id, int $collaborator_id) {
        $sql = "SELECT Collaborator
                FROM collaborate
                WHERE Board=:board
                AND Collaborator=:collaborator";
        $params = array(
            "board" => $board_id,
            "collaborator" => $collaborator_id
        );
        $query = self::execute($sql, $params);
        $data = $query->fetch();
    }

    public static function get_collaborations(Board $board): array {
        $sql = "SELECT Collaborator
                FROM collaborate
                JOIN user ON Collaborator = ID
                WHERE Board=:board
                ORDER BY FullName";
        $params = array("board" => $board->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $collaborations = [];
        foreach ($data as $rec) {
            $collaborator = User::get_by_id($rec['Collaborator']);
            $collaboration = new Collaboration($collaborator, $board);
            array_push($collaborations, $collaboration);
        }
        return $collaborations;
    }

    public static function get_users_collaborations(User $user): array {
        $sql = "SELECT Board
                FROM collaborate
                WHERE Collaborator=:collaborator";
        $params = array("collaborator" => $user->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $collaborations = [];
        foreach ($data as $rec) {
            $board = Board::get_by_id($rec['Board']);
            $collaboration = new Collaboration($user, $board);
            array_push($collaborations, $collaboration);
        }
        return $collaborations;
    }

    public function is_user_collaborator(): bool {
        $collaborator = $this->get_collaborator();
        $board = $this->get_board();

        if($collaborator->is_admin_or_owner($board)) {
            return true;
        }

        $sql = "SELECT Collaborator
                FROM collaborate
                WHERE Board = :boardId AND Collaborator = :userId";
        $params = array(
            "boardId" => $board->get_id(), 
            "userId" => $collaborator->get_id()
        );
        $query = self::execute($sql, $params);
        $data = $query->fetchall();

        return count($data) == 1;

    }
    
    public static function get_others(Board $board, User $user): array {
        $sql = "SELECT * 
                FROM `user` 
                WHERE ID NOT IN ( 
                    SELECT Collaborator 
                    FROM collaborate 
                    WHERE Board = :board)
                AND ID<>:user"
                ;
        $params = array("board" => $board->get_id(), 
                        "user" => $user->get_id()
                    );
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $others = [];
        foreach ($data as $rec) {
            $user = User::get_by_id($rec['ID']);
            array_push($others, $user);
        }
        return $others;
    }

    public function insert() {
        $sql = "INSERT INTO collaborate(Collaborator, Board) 
                VALUES(:collaborator, :board)";
        $params = array(
            "collaborator" => $this->get_collaborator_id(), 
            "board" => $this->get_board_id(),
        );
        $this->execute($sql, $params);
    }

    public function delete() {
        $this->delete_all_participations();
        $sql = "DELETE FROM collaborate 
                WHERE Collaborator = :collaborator
                AND Board = :board";
        $params = array(
            "collaborator" => $this->get_collaborator_id(), 
            "board" => $this->get_board_id()
        );
        $this->execute($sql, $params);
    }

    public function get_participations() : array {
        $sql = "SELECT *
                FROM participate 
                WHERE CARD IN(
                    SELECT ID 
                    FROM card 
                    WHERE `Column` IN(
                        SELECT ID 
                        FROM `column` 
                        WHERE board IN(
                            SELECT ID 
                            FROM Board 
                            WHERE ID = :board 
                            )
                        ) 
                    ) 
                AND Participant = :collaborator";
        $params = array(
            "collaborator" => $this->get_collaborator_id(), 
            "board" => $this->get_board_id()
        );
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $participations = [];
        foreach ($data as $rec) {
            $participant = USER::get_by_id($rec['Participant']);
            $card = Card::get_by_id($rec['Card']);
            $participation = new Participation($participant, $card);
            array_push($participations, $participation);
        }
        return $participations;
    }

    public function delete_all_participations() {
        $sql = "DELETE FROM participate 
                WHERE CARD IN (
                    SELECT ID 
                    FROM card 
                    WHERE `Column` IN (
                        SELECT ID 
                        FROM `column` 
                        WHERE board IN (
                            SELECT ID 
                            FROM Board 
                            WHERE ID = :board 
                        )
                    ) 
                ) 
                AND Participant = :collaborator";
        $params = array(
            "collaborator" => $this->get_collaborator_id(), 
            "board" => $this->get_board_id()
        );
        $this->execute($sql, $params);
    }

    public static function collaborations_to_JSON(Board $board) {
        $collaborations = Collaboration::get_collaborations($board);
        $res = array();
        foreach($collaborations as $collaboration) {
            $user = $collaboration->get_collaborator()->prepare_JSON();
            array_push($res, $user);
        }
        echo json_encode($res);
    }

    public static function others_to_JSON(Board $board, User $user) {
        $others = Collaboration::get_others($board, $user);
        $res = array();
        foreach($others as $collaborator) {
            $user = $collaborator->prepare_JSON();
            array_push($res, $user);
        }
        echo json_encode($res);
    }

}