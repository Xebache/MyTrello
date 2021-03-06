<?php

require_once "framework/Model.php";
require_once "model/Card.php";
require_once "model/User.php";


class Comment extends Model {
    use DateTrait;

    private ?String $id;
    private String $body;
    private User $author;
    private Card $card;

    public function __construct(string $body, User $author, Card $card, ?string $id=null, ?DateTime $createdAt=null,
                                ?DateTime $modifiedAt=null){
        $this->id = $id;
        $this->body = Validation::sanitize_string($body);
        $this->author = $author;
        $this->card = $card;
        $this->createdAt = $createdAt;
        $this->modifiedAt = $modifiedAt;
    }
    public static function create_new(String $body, User $author, Card $card): Comment{
        return new Comment($body, $author, $card, null);
    }

    // GETTERS

    public function get_id(): ?string {
        return $this->id;
    }

    public function get_body(): string {
        return $this->body;
    }

    public function get_author(): User {
        return $this->author;
    }

    public function get_author_fullName(): string {
        return $this->author->get_fullName();
    }

    public function get_author_id(): string {
        return $this->get_author()->get_id();
    }
 
    public function get_card(): Card {
        return $this->card;
    }

    public function get_card_id(): String {
        return $this->card->get_id();
    }

    public function get_board_owner_id(): int{
        return $this->card->get_board_owner()->get_id();
    }
    //   SETTERS

    public function set_id($id){
        $this->id=$id;
    }

    public function set_body($body){
        $this->body=Validation::sanitize_string($body);
    }

    public function set_author($author){
        $this->author=$author;
    }

    public function set_card($card){
        $this->card=$card;
    }



    //   QUERIES

    // renvoie un comment avec comme attributs les donnee de $data
    protected static function get_instance($data): Comment {
        list($createdAt, $modifiedAt) = self::get_dates_from_sql($data["CreatedAt"], $data["ModifiedAt"]);
        return new Comment(
            $data["Body"],
            User::get_by_id($data["Author"]),
            Card::get_by_id($data["Card"]),
            $data["ID"],
            $createdAt,
            $modifiedAt
        );
    }

    public static function get_by_id(string $id): Comment {
        $sql = "SELECT * 
                FROM `comment` 
                WHERE ID=:id";
        $params = array("id" => $id);
        $query = self::execute($sql, $params);
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return null;
        } else {
            $comment = self::get_instance($data);
            return $comment;
        }
    }

    public static function get_users_comments(User $user) {
        $sql = 
            "SELECT * 
             FROM comment 
             WHERE Author=:id";
        $param = array("id" => $user->get_id());
        $query = self::execute($sql, $param);
        $data = $query->fetchAll();

        $comments = array();
        foreach ($data as $rec) {
            array_push($comments, self::get_instance($rec));
        }
        return $comments;
    }

    // insertion en db avec les valeurs d'instances.
    public function insert() { 
        $sql =
            "INSERT INTO comment (Body, Author, Card) 
             VALUES (:body, :author, :card)";
        $params=array(
            "body"=>$this->get_body(),
            "author"=>$this->get_author()->get_id(),
            "card"=>$this->get_card()->get_id()
        );
        $this->execute($sql, $params);
        $id = $this->lastInsertId();
        $this->set_id($id);
        $this->set_dates_from_db();
    }
    
    // rencoie true si l'utilisateur $user a le droit d'??diter le commentaire $id 
    public static function can_edit(int $id, User $user): bool{
        $comment = Comment::get_by_id($id);
        return is_null($comment) && ($comment->get_author_id()==$user->get_id() || $user->is_admin());
    }
    
    //mets a jour la db avec les valeurs de l'instance
    public function update() {
        $sql = 
            "UPDATE comment 
             SET Body=:body, Author=:author, Card=:card , ModifiedAt=NOW()
             WHERE ID=:id";
        $params = array(
            "id"=>$this->get_id(),
            "body"=>$this->get_body(), 
            "author"=>$this->get_author()->get_id(),
            "card"=>$this->get_card()->get_id()
        );
        $this->execute($sql, $params);
        $this->set_dates_from_db();
    }

    public function delete() {
        $sql = "DELETE FROM comment 
                WHERE ID = :id";
        $param = array('id' => $this->id);
        self::execute($sql, $param);
    }

    

    // renvoie un tableau de comment associ?? ?? la carte $card
    public static function get_comments_for_card(Card $card): array {
        $sql = 
            "SELECT * 
            FROM comment 
            WHERE card=:id 
            ORDER BY ifnull(modifiedat, createdat) desc";
        $param = array("id" => $card->get_id());
        $query = self::execute($sql, $param);
        $data = $query->fetchAll();

        $comments = array();
        foreach ($data as $rec) {
            array_push($comments, self::get_instance($rec));
        }
        return $comments;
    }
    
    // fonction utilitaires
    public function get_author_name(): String{
        return $this->get_author()->get_fullName();
    }

    public function get_time_string(): String{
        $created=$this->get_created_intvl();
        $ma=$this->get_modified_intvl();
        return "Created ".$created.". ".$ma.".";
    }

    //renvoie true si le commentaire peut ??tre montr?? sur la page, false sinon
    public function can_be_show($show_comment): bool{
        return $show_comment == $this->get_id();
    }

    public function can_be_deleted(User $user): bool{
        return $this->get_author_id() == $user->get_id() 
            || $this->get_board_owner_id() == $user->get_id()
            || $user->is_admin();
    }

    public function can_be_modified(User $user): bool{
        return $this->get_author_id() == $user->get_id() || $user->is_admin();
    }

    public function get_date_string(): String{
        $date = "Created ".$this->get_created_intvl();
        if($this->get_createdAt()!=$this->get_modifiedAt()){
            $date = $this->get_modified_intvl();
        }
        return $date;
    }

    public function validate(): bool{
        return $this->body != " " && $this->body != "";
    }
}
