<?php

require_once "User.php";
require_once "framework/Model.php";

class Participation extends Model {
    private User $participant;
    private Card $card;

    public function __construct(User $participant, Card $card) {
        $this->participant = $participant;
        $this->card = $card;
    }


    //    GETTERS    //

    public function get_participant(): User {
        return $this->participant;
    }

    public function get_participant_id(): string {
        return $this->participant->get_id();
    }

    public function get_participant_fullName(): string {
        return $this->participant->get_fullName();
    }

    public function get_participant_first_letters(): string {
        return $this->participant->get_first_letters();
    }
 
    public function get_participant_email(): string {
        return $this->participant->get_email();
    }

    public function get_card(): Card {
        return $this->card;
    }

    public function get_card_id(): string {
        return $this->card->get_id();
    }

    public function is_board_owner(Board $board): bool {
        return $this->participant == $board->get_owner();
    }

    public function board_owner(Board $board): string {
        if($this->is_board_owner($board)) {
            return "owner";
        }
        return "";
    }


    //    QUERIES    //

    public static function get_participations(Card $card): array {
        $sql = "SELECT Participant
                FROM participate
                JOIN user ON Participant = ID
                WHERE Card=:card
                ORDER BY FullName";
        $params = array("card" => $card->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $participations = [];
        foreach ($data as $rec) {
            $participant = User::get_by_id($rec['Participant']);
            $participation = new Participation($participant, $card);
            array_push($participations, $participation);
        }
        return $participations;
    }

    public static function get_others(Card $card): array {
        $sql = "SELECT * 
                FROM `user` 
                WHERE ID NOT IN ( 
                    SELECT Participant 
                    FROM participate 
                    WHERE Card = :card
                )
                AND (
                    ID IN (
                    SELECT Collaborator
                    FROM collaborate
                    WHERE Board = :board
                    )
                    OR ID IN (
                        SELECT Owner
                        FROM board
                        WHERE ID = :board
                    )
                )";
           
        $params = array(
            "card" => $card->get_id(), 
            "board" => $card->get_board_id()
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
        $sql = "INSERT INTO participate(Participant, Card) 
                VALUES(:participant, :card)";
        $params = array(
            "participant" => $this->get_participant_id(), 
            "card" => $this->get_card_id(),
        );
        $this->execute($sql, $params);
    }

    public function delete() {
        $sql = "DELETE FROM participate 
                WHERE Participant = :participant
                AND Card = :card";
        $params = array(
            "participant" => $this->get_participant_id(), 
            "card" => $this->get_card_id()
        );
        $this->execute($sql, $params);
    }


}