<?php

require_once "framework/Model.php";
require_once "User.php";
require_once "Column.php";
require_once "TitleTrait.php";


class Board extends Model {
    use DateTrait, TitleTrait;

    private ?string $id;
    private User $owner;
    private ?array $columns = null;
    private ?array $collaborators = null;
    private ?array $others = null;


    public function __construct(string $title, User $owner, ?string $id=null, ?DateTime $createdAt=null,
                                ?DateTime $modifiedAt=null) {
        $this->id = $id;
        $this->title = Validation::sanitize_string($title);
        $this->owner = $owner;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
    }


    //    GETTERS    //

    public function get_id(): ?string {
        return $this->id;
    }

    public function get_owner(): User {
        return $this->owner;
    }

    public function get_owner_id(): string {
        return $this->owner->get_id();
    }

    public function get_owner_fullName(): string {
        return $this->owner->get_fullName();
    }

    public function get_owner_first_letters(): string {
        return $this->owner->get_first_letters();
    }

    public function get_columns(): array {
        if (is_null($this->columns)) {
            $this->columns = Column::get_columns_for_board($this);
        }
        return $this->columns;
    }

    public function get_collaborations(): array {
        if (is_null($this->collaborators)) {
            $this->collaborators = Collaboration::get_collaborations($this);
        }
        return $this->collaborators;
    }

    public function get_others(User $user): array {
        if (is_null($this->others)) {
            $this->others = Collaboration::get_others($this, $user);
        }
        return $this->others;
    }


    //    SETTERS    //

    public function set_id(string $id): void {
        $this->id = $id;
    }


    //    VALIDATION    //

    public function validate(): array {
        $errors = [];
        if (!Validation::str_longer_than($this->title, 2)) {
            $errors[] = "Title must be at least 3 characters long";
            
        }
        if (!Validation::is_unique_title($this->title)) {
            $errors[] = "A board with the same title already exists";
        }
        return $errors;
    }


    //    QUERIES    //

    protected static function get_instance($data): Board {
        list($createdAt, $modifiedAt) = self::get_dates_from_sql($data["CreatedAt"], $data["ModifiedAt"]);
        return new Board(
            $data["Title"],
            User::get_by_id($data["Owner"]),
            $data["ID"],
            $createdAt,
            $modifiedAt
        );
    }

    public static function get_by_id(string $id): ?Board{
        $sql = "SELECT * 
                FROM `board` 
                WHERE ID=:id";
        $params = array("id" => $id);
        $query = self::execute($sql, $params);
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return null;
        } else {
            $board = self::get_instance($data);
            return $board;
        }
    }

    public static function get_by_title(string $title): ?Board {
        $sql =  "SELECT * 
                FROM board 
                WHERE Title = :title";
        $params = array("title" => $title);
        $query = self::execute($sql, $params);
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return null;
        } else {
            $board = self::get_instance($data);
            return $board;
        }
    }

    //renvoie true si le titre de la carte est unique pour le tableau contenant la carte
    // version a utiliser en cas d'update
    public function title_is_unique_update() {
        $sql = "SELECT * 
                FROM `board`
                WHERE Title=:title AND ID<>:board_id";
         $params = array(
             "title"=>$this->get_title(), 
             "board_id"=>$this->get_id()
            );
         $query = self::execute($sql, $params);
         $data=$query->fetch();
         return $query->rowCount()==0 ;
    }
    

    public static function get_all_boards(): array {
        $sql = "SELECT * 
                FROM board";
        $params = [];
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $boards = array();
        foreach ($data as $rec) {
            array_push($boards, self::get_instance($rec));
        }
        return $boards;
    }

    public static function get_all_users_boards(User $user): array {
        return array_merge(
            Board::get_users_boards($user), 
            Board::get_shared_boards($user)
        );
         
    }
 
    public static function get_users_boards(User $user): array {
        $sql = "SELECT * 
                FROM board 
                WHERE Owner=:id";
        $params = array("id"=>$user->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $boards = array();
        foreach ($data as $rec) {
            array_push($boards, self::get_instance($rec));
        }

        return $boards;
    }

    public static function get_shared_boards(User $user): array {
        $sql = 
            "SELECT * 
             FROM board 
             WHERE ID IN (
                 SELECT Board
                 FROM collaborate
                 WHERE Collaborator = :id
                 )
            ORDER BY Title";
        $params = array("id"=>$user->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $boards = array();
        foreach ($data as $rec) {
            array_push($boards, self::get_instance($rec));
        }

        return $boards;
    }
    
    public static function get_others_boards(User $user): array {
        $sql = 
            "SELECT * 
             FROM board 
             WHERE Owner!=:id
             AND ID NOT IN (
                SELECT Board
                FROM collaborate
                WHERE Collaborator = :id
                )
            ORDER BY Title";
        $params = array("id" => $user->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $boards = array();
        foreach ($data as $rec) {
            array_push($boards, self::get_instance($rec));
        }

        return $boards;
    }
    
    public function insert() {
        $sql = "INSERT INTO board(Title, Owner) 
                VALUES(:title, :owner)";
        $params = array(
            "title" => $this->get_title(),
            "owner" => $this->get_owner_id(),
            );
        $this->execute($sql, $params);
        $id = $this->lastInsertId();
        $this->set_id($id);
        $this->set_dates_from_db();
    }

    public function update(): void {
        $sql = "UPDATE board 
                SET Title=:title, Owner=:owner, ModifiedAt=NOW() 
                WHERE ID=:id";
        $params = array(
            "id" => $this->get_id(), 
            "title" => $this->get_title(), 
            "owner" => $this->get_owner_id(),
        );
        
        $this->execute($sql, $params);
        $this->set_dates_from_db();
    }
    
    public function delete(): void {
        $this->delete_all_collaborations();
        $this->delete_all_columns();
        $sql = "DELETE FROM board 
                WHERE ID = :id";
        $params = array("id"=>$this->get_id());
        $this->execute($sql, $params);
    }

    private function delete_all_columns() {
        foreach ($this->get_columns() as $column) {
            $column->delete_all_cards();
        }
        $sql = "DELETE FROM `column` 
                WHERE Board = :board";
        $params = array("board"=>$this->get_id());
        $this->execute($sql, $params);
    }

    private function delete_all_collaborations() {
        $sql = "DELETE FROM `collaborate` 
                WHERE Board = :board";
        $params = array("board"=>$this->get_id());
        $this->execute($sql, $params);
    }

}