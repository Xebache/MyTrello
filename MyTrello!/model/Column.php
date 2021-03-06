<?php

require_once "framework/Model.php";
require_once "Card.php";
require_once "TitleTrait.php";


class Column extends Model {
    use DateTrait, TitleTrait;

    private ?string $id;
    private string $position;
    private Board $board;
    private ?array $cards = null;


    public static function create_new(string $title, Board $board): Column {
        return new Column(
            $title,
            count($board->get_columns()),
            $board
        );
    }

    public function __construct(string $title, 
                                int $position, 
                                Board $board, 
                                string $id=null, 
                                ?DateTime $createdAt=null,
                                ?DateTime $modifiedAt=null) {
        $this->id = $id;
        $this->title = Validation::sanitize_string($title);
        $this->position = $position;
        $this->board = $board;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
    }


    //    GETTERS    //

    public function get_id(): ?string {
        return $this->id;
    }

    public function get_position(): string {
        return $this->position;
    }

    public function get_board(): Board {
        return $this->board;
    }

    public function get_board_id(): string {
        return $this->board->get_id();
    }

    public function get_board_title(): string {
        return $this->board->get_title();
    }

    public function get_board_owner(): User {
        return $this->board->get_owner();
    }

    public function get_board_columns(): array {
        return $this->board->get_columns();
    }

    public function get_cards(): array {
        if (is_null($this->cards)) {
            $this->cards = Card::get_cards_for_column($this);
        }
        return $this->cards;
    }


    //    SETTERS    //

    public function set_id(string $id): void {
        $this->id = $id;
    }

    public function set_position(int $position): void {
        $this->position = $position;
    }

    public function is_first(): bool {
        return $this->get_position() == 0;
    }

    public function is_last(): bool {
        return $this->get_position() == count($this->get_board_columns()) - 1;
    }

    //    VALIDATION    //

    public function validate(): array {
        $errors = [];
        if (!Validation::str_longer_than($this->title, 2)) {
            $errors[] = "Title must be at least 3 characters long";
        }
        if(!Validation::is_unique_column_title($this)) {
            $errors[] = "A column with the same title already exists in this board";
        }
        return $errors;
    }

    public function has_unique_title_in_board(): bool {
        $title = $this->get_title();
        $columns = $this->get_board_columns();
        $count = 0;
        foreach($columns as $column) {
            if($column->get_title() === $title){
                ++$count;
            }
        }
        return $count == 0;
    }

    public function is_empty(): bool {
        return count($this->get_cards()) == 0;
    }

    //    QUERIES    //

    protected static function get_instance($data, $board=null) :Column {
        list($createdAt, $modifiedAt) = self::get_dates_from_sql($data["CreatedAt"], $data["ModifiedAt"]);
        return new Column(
            $data["Title"],
            $data["Position"],
            Board::get_by_id($data["Board"]),
            $data["ID"],
            $createdAt,
            $modifiedAt
        );
    }

    public static function get_by_id(string $id): ?Column {
        $sql = "SELECT * 
                FROM `column` 
                WHERE ID = :id";
        $params = array("id" => $id);
        $query = self::execute($sql, $params);
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return null;
        } else {
            $column = self::get_instance($data);
            return $column;
        }
    }

    public static function get_by_title(string $title, Board $board): ?Column {
        $sql = "SELECT * 
                FROM `column` 
                WHERE Title = :title
                AND Board = :board";
        $params = array(
            "title" => $title,
            "board" => $board->get_id()
        );
        $query = self::execute($sql, $params);
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return null;
        } else {
            $column = self::get_instance($data);
            return $column;
        }
    }

    public static function get_all(Board $board): array {
        $sql = "SELECT * 
                FROM `column` 
                WHERE Board=:id ORDER BY Position";
        $params= array("id"=>$board->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $columns = array();
        foreach ($data as $rec) {
            array_push($columns, self::get_instance($rec));
        }
        return $columns;
    }

    public static function get_columns_for_board(Board $board): array {
        $sql = "SELECT * 
                FROM `column` 
                WHERE Board=:id ORDER BY Position";
        $params= array("id"=>$board->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $columns = array();
        foreach ($data as $rec) {
            $column = self::get_instance($rec);
            array_push($columns, $column);
        }
        return $columns;
    }

    public function insert() {
        $sql = "INSERT INTO `column`(Title, Position, Board) 
                VALUES(:title, :position, :board)";
        $params = array(
            "title" => $this->get_title(), 
            "position" => $this->get_position(),
            "board" => $this->get_board_id()
        );

        $this->execute($sql, $params);
        $id = $this->lastInsertId();
        $this->set_id($id);
        $this->set_dates_from_db();
    }

    public function update(): void {
        $sql = "UPDATE `column` 
                SET Title=:title, Position=:position, Board=:board, ModifiedAt=NOW() 
                WHERE ID=:id";
        $params = array(
            "id" => $this->get_id(), 
            "title" => $this->get_title(), 
            "position" => $this->get_position(),
            "board" => $this->get_board_id()
        );

        $this->execute($sql, $params);
        $this->set_dates_from_db();
    }

    public function delete(): void {
        $this->delete_all_cards();

        $sql = "DELETE 
                FROM `column` 
                WHERE ID = :id";
        $params = array("id"=>$this->get_id());
        $this->execute($sql, $params);
    }

    public function delete_all_cards() {
        foreach ($this->get_cards() as $card) {
            $card->delete_all_participations();
            $card->delete_all_comments();
        }
        $sql = "DELETE FROM `card` 
                WHERE `Column` = :column";
        $param = array('column' => $this->get_id());
        self::execute($sql, $param);
    }

    public function title_is_unique_update() {
        $sql = "SELECT * 
                FROM `column` co
                WHERE co.Title=:title AND co.Board=:board_id AND co.ID<>:column_id";
         $params = array(
             "title"=>$this->get_title(), 
             "board_id"=>$this->get_board_id(),
             "column_id"=>$this->get_id()
            );
         $query = self::execute($sql, $params);
         $data=$query->fetch();
         return $query->rowCount()==0 ;
    }
    // MOVE COLUMN //

    public function move_left(): void {
        $pos = $this->get_position();

        if ($pos > 0) {
            $target = $this->get_board_columns()[$pos - 1];
            $this->set_position($target->get_position());
            $target->set_position($pos);

            $this->update();
            $target->update();
        }
    }

    public function move_right(): void {
        $pos = $this->get_position();
        $columns = $this->get_board_columns();

        if ($pos < sizeof($columns) - 1) {
            $target = $columns[(int)$pos + 1];
            $this->set_position($target->get_position());
            $target->set_position($pos);

            $this->update();
            $target->update();;
        }
    }

    public static function decrement_following_columns_position(Column $column): void {
        $sql = "UPDATE `column` 
                SET Position = Position - 1
                WHERE Board=:board 
                AND Position>:pos";
        $params = array(
            "board" => $column->get_board_id(),
            "pos" => $column->get_position()
        );
        self::execute($sql,$params);
    }

}