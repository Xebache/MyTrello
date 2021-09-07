<?php

require_once "framework/Model.php";
require_once "User.php";
require_once "Comment.php";
require_once "Participation.php";
require_once "TitleTrait.php";
require_once "DueDateTrait.php";


class Card extends Model {
    use DateTrait, TitleTrait, DueDateTrait;

    private ?string $id;
    private string $body;
    private string $position;
    private User $author;
    private Column $column;
    private ?array $comments = null;
    private ?array $participations = null;
    private ?array $others = null;

    public static function create_new(string $title, User $author, string $column_id, ?DateTime $dueDate=null ): ?Card {
        $column = Column::get_by_id($column_id);
        if($column == null){
            return null;
        }
        return new Card(
            $title,
            "",
            self::get_cards_count($column),
            $author,
            $column,
            $dueDate
        );
    }


    public function __construct(string $title, 
                                string $body, 
                                int $position,
                                User $author, 
                                Column $column,
                                ?DateTime $dueDate=null,
                                ?string $id = null,
                                ?DateTime $createdAt=null,
                                ?DateTime $modifiedAt=null) {

        $this->id = $id;
        $this->title = Validation::sanitize_string($title);
        $this->body = $body;
        $this->position = $position;
        $this->author = $author;
        $this->column = $column;
        $this->dueDate = $dueDate;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
    }


    //    GETTERS    //

    public function get_id(): ?string {
        return $this->id;
    }

    public function get_body(): string {
        return $this->body;
    }

    public function get_position(): string {

        return $this->position;
    }

    public function get_author(): User {
        return $this->author;
    }

    public function get_author_name(): string {
        return $this->author->get_fullName();
    }

    public function get_author_id(): string {
        return $this->author->get_id();
    }

    public function get_column(): Column {
        return $this->column;
    }

    public function get_column_title(): string {
        return $this->column->get_title();
    }

    public function get_column_cards(): array {
        return $this->column->get_cards();
    }

    public function get_column_id(): string {
        return $this->column->get_id();
    }

    public function get_column_position(): string {
        return $this->column->get_position();
    }

    public function get_all_columns(): array {
        return $this->column->get_board_columns();
    }  

    public function get_board() {
        return $this->column->get_board();
    }

    public function get_board_id(): string {
        return $this->column->get_board_id();
    }

    public function get_board_title(): string {
        return $this->column->get_board_title();
    }
 
    public function get_board_owner(): User{
        return $this->column->get_board_owner();
    }

    public function get_comments(): array {
        if (is_null($this->comments)) {
            $this->comments = Comment::get_comments_for_card($this);
        }
        return $this->comments;
    }

    public function get_participations(): array {
        if (is_null($this->participations)) {
            $this->participations = Participation::get_participations($this);
        }
        return $this->participations;
    }

    public function get_others(): array {
        if (is_null($this->others)) {
            $this->others = Participation::get_others($this);
        }
        return $this->others;
    }

    
    //    SETTERS    //

    public function set_id(string $id) {
        $this->id = $id;
    }

    public function set_body(string $body) {
        $this->body = $body;
    }

    public function set_column(Column $column) {
        $this->column = $column;
    }

    public function set_position(string $position) {
        $this->position = $position;
    }


    //    VALIDATION    //

    // fonction de validation en cas d'ajout de nouvelle carte
    public function validate(): array {
        $errors = [];
        if (!Validation::str_longer_than($this->get_title(), 2)) {
            $errors[] = "Title must be at least 3 characters long";
        }
        if(!$this->title_is_unique()){
            $errors[] = "Title already exists in this board";
        }
        if(is_null($this->get_column()) ){
            $errors[] = "This column does not exist";
        }
        return $errors;
    }
    // fonction de validation en cas d'update d'une carte deja existante
    public function validate_update(): array{
        $errors = [];
        if (!Validation::str_longer_than($this->get_title(), 2)) {
            $errors[] = "Title must be at least 3 characters long";
        }
        if(!$this->title_is_unique_update()){
            $errors[] = "Title already exists in this board";
        }
        return $errors;
    }


    //    TOOLS    //

    public function has_comments(): bool {
        return count($this->get_comments()) > 0;
    }

    public function get_comments_count(): int {
        return count($this->get_comments());
    }
 
    public function is_first(): bool {
        return $this->get_position() == 0;
    }

    public function is_last(): bool {
        return $this->get_position() == count($this->get_column_cards()) - 1;
    }

    public function has_participations(): bool {
        return !empty($this->get_participations());
    }


    //    QUERIES    //

    protected static function get_instance($data) :Card {
        list($createdAt, $modifiedAt) = self::get_dates_from_sql($data["CreatedAt"], $data["ModifiedAt"]);
        return new Card(
            $data["Title"],
            $data["Body"],
            $data["Position"],
            User::get_by_id($data["Author"]),
            Column::get_by_id($data["Column"]),
            is_null($data["DueDate"]) ? null : new DateTime($data["DueDate"]),
            $data["ID"],
            $createdAt,
            $modifiedAt
        );
    }
    
    public static function get_by_id(string $id): ?Card {
        $sql = "SELECT * 
                FROM `card` 
                WHERE ID=:id";
        $params = array("id" => $id);
        $query = self::execute($sql, $params);
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return null;
        } else {
            $card = self::get_instance($data);
            return $card;
        }
    }

    public static function get_by_title(string $title, Column $column): ?Card {
        $sql = "SELECT * 
                FROM `card` ca
                JOIN `column` co ON ca.Column = co.ID
                WHERE ca.Title = :title
                AND co.Board = :board";
        $params = array(
            "title" => $title,
            "board" => $column->get_board_id()
        );
        $query = self::execute($sql, $params);
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return null;
        } else {
            $card = self::get_instance($data);
            return $card;
        }
    }

    // renvoie true si le titre de la carte est unique pour le tableau contenant la carte
    public function title_is_unique() {
        $sql = "SELECT * 
                FROM `card` ca, `column` co
                WHERE ca.Title=:title AND ca.Column=co.ID AND co.Board=:board_id";
        $params = array(
            "title"=>$this->get_title(), 
            "board_id"=>$this->get_board_id(),
            );
        $query = self::execute($sql, $params);
        $data=$query->fetch();
        return $query->rowCount()==0 ;
    }

    //renvoie true si le titre de la carte est unique pour le tableau contenant la carte
    // version a utiliser en cas d'update
    public function title_is_unique_update() {
        $sql = "SELECT * 
                FROM `card` ca, `column` co
                WHERE ca.Title=:title AND ca.Column=co.ID AND co.Board=:board_id AND ca.ID<>:card_id";
         $params = array(
             "title"=>$this->get_title(), 
             "board_id"=>$this->get_board_id(),
             "card_id"=>$this->get_id()
            );
         $query = self::execute($sql, $params);
         $data=$query->fetch();
         return $query->rowCount()==0 ;
    }

    //renvoie un tableau de cartes triées par leur position dans la colonne dont la colonne est $column;
    public static function get_cards_for_column(Column $column): array {
        $sql = "SELECT * 
                FROM `card` 
                WHERE `Column`=:column 
                ORDER BY Position";
        $params = array("column"=>$column->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $cards = [];
        foreach ($data as $rec) {
            $card = self::get_instance($rec);
            array_push($cards, $card);
        }
        return $cards;
    }

    public static function get_users_cards(User $user): array {
        $sql = "SELECT * 
                FROM `card` 
                WHERE Author = :id";
        $params = array("id"=>$user->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $cards = array();
        foreach ($data as $rec) {
            array_push($cards, self::get_instance($rec));
        }

        return $cards;
    }

    //nombre de cartes dans la colonne
    public static function get_cards_count(Column $column) {
        $sql = "SELECT COUNT(Position) as nbr
                FROM `card` 
                WHERE `Column`=:id";
        $params = array("id"=>$column->get_id());
        $query = self::execute($sql, $params);
        $data = $query->fetch();
        return $data["nbr"];
    }

    public static function get_cards_with_duedate(Board $board, $start, $end): array {
        $sql = "SELECT * 
                FROM `card` 
                WHERE duedate IS NOT NULL
                AND duedate >= :start
                AND duedate <= :end
                AND `Column` IN (
                    SELECT ID
                    FROM `column`
                    WHERE board IN (
                        SELECT ID
                        FROM board
                        WHERE ID=:id)
                    )";
        $params = array(
            "id"=>$board->get_id(),
            "start"=>$start,
            "end"=>$end
        );
        $query = self::execute($sql, $params);
        $data = $query->fetchAll();

        $cards = [];
        foreach ($data as $rec) {
            $card = self::get_instance($rec);
            array_push($cards, $card);
        }
        return $cards;
    }

    //insère la carte dans la db, la carte reçoit un nouvel id.
    public function insert() {
        $sql = "INSERT INTO `card`(Title, Body, Position, Author, `Column`, DueDate) 
                VALUES(:title, :body, :position, :author, :column, :dueDate)";
        $params = array(
            "title" => $this->get_title(),
            "body" => $this->get_body(),
            "position" => $this->get_position(),
            "author" => $this->get_author_id(),
            "column" => $this->get_column_id(),
            "dueDate" => $this->get_dueDate()
        );

        $this->execute($sql, $params);
        $id = $this->lastInsertId();
        $this->set_id($id);
        $this->set_dates_from_db();
    }

    //met à jour la db avec les valeurs des attributs actuels de l'objet Card
    public function update() {
        $sql = "UPDATE `card` 
                SET Title=:title, Body=:body, Position=:position, ModifiedAt=NOW(), Author=:author, `Column`=:column, DueDate=:dueDate 
                WHERE ID=:id";
        $params = array(
            "id" => $this->get_id(), 
            "title" => $this->get_title(),
            "body" => $this->get_body(), 
            "position" => $this->get_position(),
            "author" => $this->get_author_id(),
            "column" => $this->get_column_id(),
            "dueDate" => $this->get_dueDate_sql_format()
        );

        $this->execute($sql, $params);
        $this->set_dates_from_db();
    }

    //supprime la carte de la db, ainsi que tous les commentaires liés a cette carte
    public function delete() {
        $this->delete_all_participations();
        $this->delete_all_comments();

        $sql = "DELETE FROM card 
                WHERE ID = :id";
        $param = array('id' => $this->get_id());
        self::execute($sql, $param);
    }

    public function delete_all_comments() {
        $sql = "DELETE FROM comment 
                WHERE Card = :card";
        $param = array('card' => $this->get_id());
        self::execute($sql, $param);
    }

    public function delete_all_participations() {
        $sql = "DELETE FROM participate 
                WHERE Card = :card";
        $param = array('card' => $this->get_id());
        self::execute($sql, $param);
    }


    //    MOVE CARD    //

    public function move_up(): void {
        $pos = $this->get_position();

        if ($pos > 0) {
            $target = $this->get_column_cards()[$pos-1];
            $this->set_position($target->get_position());
            $target->set_position($pos);

            $this->update();
            $target->update();
        }
    }

    public function move_down(): void {
        $pos = $this->get_position();
        $cards = $this->get_column_cards();

        if ($pos < sizeof($cards)-1) {
            $target = $cards[(int)$pos + 1];
            $this->set_position($target->get_position());
            $target->set_position($pos);

            $this->update();
            $target->update();;
        }
    }

    public function move_left(): void {
        $pos = $this->get_column_position();

        if ($pos > 0) {
            $target = $this->get_all_columns()[$pos-1];

            /*Faut décrémenter les suivantes avant de changer de colonne*/
            Card::decrement_following_cards_position($this);

            $this->set_column($target);
            $this->set_position(Card::get_cards_count($target));
            $this->update();
        }
    }

    public function move_right(): void {
        $pos = $this->get_column_position();
        $colList = $this->get_all_columns();

        if ($pos < sizeof($colList)-1) {
            $target = $colList[$pos+1];

            /*Faut décrémenter les suivantes avant de changer de colonne*/
            Card::decrement_following_cards_position($this);

            $this->set_column($target);
            $this->set_position(Card::get_cards_count($target));
            $this->update();

        }
    }

    /*
        fonction utilisée lors de la suppression d'une carte. mets a jour la position des autres cartes de la colonne.
        on n'utilise pas update pour ne pas mettre a jour 'modified at', vu qu'il ne s'agit pas d'une modif de la carte voulue par 
        l'utilisateur, mais juste une conséquence d'une autre action
    */
    public static function decrement_following_cards_position($card): void {
        $sql = "UPDATE `card` 
                SET Position = Position - 1
                WHERE `Column`=:column 
                AND Position>:pos";
        $params = array(
            "column" => $card->get_column_id(),
            "pos" => $card->get_position()
        );
        self::execute($sql,$params);
    }

    public static function increment_following_cards_position($card): void {
        $sql = "UPDATE `card` 
                SET Position = Position + 1
                WHERE `Column`=:column 
                AND Position>=:pos";
        $params = array(
            "column" => $card->get_column_id(),
            "pos" => $card->get_position()
        );
        self::execute($sql,$params);
    }

    public function card_to_calendar_event(): array {
        $now = new DateTime();
        $card = array();
        $card['id'] = $this->get_id();
        $card['title'] = $this->get_title();
        $card['start'] = $this->get_dueDate()->format('Y-m-d');
        $card['allDay'] = true;
        $card['board_id'] = $this->get_board_id();
        $card['due'] = true;
        if($this->get_dueDate() > $now) {
            $card['due'] = false;   
        }
        $card['description'] = $this->get_card_description();

        return $card;
    }

    private function get_participants(): array {
        $participations = $this->get_participations();
        $participants = array();
        foreach($participations as $participation) {
            array_push($participants, $participation->get_participant_first_letters());
        }
        return $participants;
    }

    private function get_card_description(): array {
        $description = array();
        $description['title'] = $this->get_title();
        $description['body'] = $this->get_body();
        $description['author'] = $this->get_author_name();
        $description['created'] = $this->get_createdAt()->format('d/m/Y');
        $description['modified'] = $this->get_modifiedAt()->format('d/m/Y');
        $description['participants'] = $this->get_participants();
        return $description;
    }
}