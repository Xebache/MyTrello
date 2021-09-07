<?php

require_once "framework/Model.php";
require_once "Board.php";
require_once "Collaboration.php";
require_once "Validation.php";


class User extends Model {
    private ?string $id;
    private string $email;
    private string $fullName;
    private ?string $role;
    private ?string $passwdHash;
    private ?DateTime $registeredAt;
    private ?string $clearPasswd; //Utilisé uniquement au moment du signup pour faciliter validate

    
    public function __construct(string $email, 
                                string $fullName, 
                                ?string $role=null, 
                                ?string $clearPasswd=null,
                                ?string $id=null, 
                                ?string $passwdHash=null, 
                                ?DateTime $registeredAt=null) {
        if (is_null($id)) {
            $passwdHash = Tools::my_hash($clearPasswd);
        }

        if (is_null($role)) {
            $role = "user";
        }

        $this->id = $id;
        $this->email = $email;
        $this->fullName = Validation::sanitize_string($fullName);
        $this->role = $role;
        $this->passwdHash = $passwdHash;
        $this->clearPasswd = $clearPasswd;
        $this->registeredAt = $registeredAt;
    }


    //    GETTERS    //

    public function get_id(): ?string {
        return $this->id;
    }

    public function get_email(): string {
        return $this->email;
    }

    public function get_fullName(): string {
        return $this->fullName;
    }

    public function get_passwdHash(): string {
        return $this->passwdHash;
    }

    public function get_registeredAt(): DateTime {
        return $this->registeredAt;
    }

    public function get_role(): string {
        $role = $this->role;
        if(is_null($role)) {
            $role = "user";
        }
        return $role;
    }

    public function get_clearPasswd() {
        return $this->clearPasswd;
    }


    //    SETTERS    //

    public function set_id($id) {
        $this->id = $id;
    }

    public function set_registeredAt(DateTime $registeredAt) {
        $this->registeredAt = $registeredAt;
    }

    public function set_fullName(string $fullName) {
        $this->fullName = Validation::sanitize_string($fullName);
    }

    public function set_email(string $email) {
        $this->email = $email;
    }

    public function set_role(string $role) {
        $this->role = $role;
    }

    //    VALIDATION    //

    public static function validate_login($email, $password): array {
        $errors = [];
        $user = User::get_by_email($email);
        if ($user) {
            if (!$user->check_password($password)) {
                $errors[] = "Invalid username or password";
            }
        } else {
            $errors[] = "Invalid username or password";
        }
        return $errors;
    }

    public function check_password($clearPasswd): bool {
        return $this->passwdHash === Tools::my_hash($clearPasswd);
    }

    public function validate_edit(string $email): array {
        $errors = array();
        //email
        if (!Validation::valid_email($email)) {
            $errors[] = "Invalid email";
        }

        if($email != $this->get_email()){
            if(!Validation::is_unique_email($email)){
                $errors[] = "Email already exists";
            }else{
                $this->set_email($email);
            }
        }

        //fullName
        if (!Validation::str_longer_than($this->fullName, 2)) {
            $errors[] = "Name must be at least 3 characters long";
        }
        return $errors;
    }

    public function validate(string $confirm): array {
        $errors = array();
        //email
        if (!Validation::valid_email($this->email)) {
            $errors[] = "Invalid email";
        }
        if(!Validation::is_unique_email($this->email)){
            $errors[] = "Invalid email";
        }
        //fullName
        if (!Validation::str_longer_than($this->fullName, 2)) {
            $errors[] = "Name must be at least 3 characters long";
        }

        //password
        if (!Validation::str_longer_than($this->clearPasswd, 7)) {
            $errors[] = "Password must be at least 8 characters long";
        }

        if (!Validation::is_same_password($this->clearPasswd, $confirm)) {
            $errors[] = "Passwords don't match";
        }

        if (!Validation::contains_capitals($this->clearPasswd)) {
            $errors[] = "Password must contain at least 1 uppercase letter";
        }

        if (!Validation::contains_digits($this->clearPasswd)) {
            $errors[] = "Password must contain at least 1 number";
        }

        if (!Validation::contains_non_alpha($this->clearPasswd)) {
            $errors[] = "Password must contain at least one special character";
        }

        return $errors;
    }

    public function is_owner(Board $board): bool {
        return $this->get_id() == $board->get_owner_id();
    } 

    public function is_author(Comment $comment): bool {
        return $this->get_id() == $comment->get_author_id() && !isset($show_comment);
    }

    public function is_admin(): bool {
        return $this->get_role() == "admin";
    }

    public function is_admin_or_owner(Board $board): bool {
        return $this->is_owner($board) || $this->is_admin();
    }

    public function is_admin_or_owner_or_collaborator(Board $board): bool {
        return $this->is_admin_or_owner($board) || $this->is_collaborator($board);
    }


    //    QUERIES    //

    /* Retourne une instance de User à partir d'une colonne de la DB */
    protected static function get_instance($data): User {
        return new User(
            $data["Mail"],
            $data["FullName"],
            $data["Role"],
            null,
            $data["ID"],
            $data["Password"],
            new DateTime($data["RegisteredAt"])
        );
    }

    public static function get_by_id(string $id): ?User {
        $sql = "SELECT * 
                FROM `user` 
                WHERE ID=:id";
        $params = array("id" => $id);
        $query = self::execute($sql, $params);
        $data = $query->fetch();

        if ($query->rowCount() == 0) {
            return null;
        } else {
            $user = self::get_instance($data);
            return $user;
        }
    }

    public static function get_by_email(string $email): ?User {
        $sql = 
            "SELECT * 
             FROM user 
             WHERE Mail=:email";
        $query = self::execute($sql, array("email"=>$email));

        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return null;
        }
        return self::get_instance($data);
    }

    public static function get_users(): array {
        $sql = "SELECT * 
                FROM `user`
                ORDER BY FullName";
        $params = [];
        $query = self::execute($sql, $params);
        $data = $query->fetchAll(); 

        $users = [];
        foreach ($data as $rec) {
            $user = self::get_instance($rec);
            array_push($users, $user);
        }
        return $users;
    }
    
    public function is_collaborator(Board $board): bool {
        if($this->is_owner($board)) {
            return true;
        }
        
        $sql = "SELECT Collaborator
                FROM collaborate
                WHERE Board = :boardId AND Collaborator = :userId";
        $params = array(
            "boardId" => $board->get_id(), 
            "userId" => $this->get_id()
        );
        $query = self::execute($sql, $params);
        $data = $query->fetchall();

        return count($data) == 1;
    }

    

    public function insert() {
        $sql = 
            "INSERT INTO user(Mail, FullName, Password, Role) 
             VALUES(:email, :fullName, :passwdHash, :role)";
        $params = array(
            "email" => $this->get_email(), 
            "fullName" => $this->get_fullName(),
            "passwdHash" => $this->get_passwdHash(),
            "role" => $this->get_role()
        );
        $this->execute($sql, $params);
        $user = self::get_by_id($this->lastInsertId());
        $this->set_id($user->get_id());
        $this->set_registeredAt($user->get_registeredAt());
    }

    public function update() {
        $sql = 
            "UPDATE user 
             SET Mail=:email, FullName=:fullName, Password=:passwdHash, Role=:role 
             WHERE ID=:id";
        $params = array(
            "id" => $this->get_id(), 
            "email" => $this->get_email(), 
            "fullName" => $this->get_fullName(),
            "passwdHash" => $this->get_passwdHash(),
            "role" => $this->get_role());
        $this->execute($sql, $params);
    }

    public function delete() {
        $this->delete_own_collaborations();
        $this->delete_own_boards();
        $this->delete_own_cards();
        $this->delete_own_comments();
        $sql = 
            "DELETE FROM user 
             WHERE ID = :id";
        $params = array("id"=>$this->get_id());
        $this->execute($sql, $params);
    }

    private function delete_own_collaborations() {
        foreach (Collaboration::get_users_collaborations($this) as $collaboration) {
            $collaboration->delete();
        }
    }

    private function delete_own_boards() {
        foreach (Board::get_users_boards($this) as $board) {
            $board->delete();
        }
    }

    private function delete_own_cards() {
        foreach (Card::get_users_cards($this) as $card) {
            $card->delete();
        }
    }

    private function delete_own_comments() {
        foreach (Comment::get_users_comments($this) as $comment) {
            $comment->delete();
        }
    }


    //    TOOLBOX    //

    // Prépare la liste des boards pour l'affichage
    private function get_boards_for_view($board_array): array {
        $boards = [];
        foreach ($board_array as $board) {
            $user = $board->get_owner();

            if(count($board->get_columns()) > 1) {
                $columns = "(" . count($board->get_columns()) . " columns)";
            } else {
                $columns = "(" . count($board->get_columns()) . " column)";
            }

            $boards[] = array(
                "id" => $board->get_id(), 
                "title" => $board->get_title(), 
                "fullName" => $user->get_fullName(), 
                "columns" => $columns
            );
        }
        return $boards;
    }

    public function get_own_boards(): array {
        return $this->get_boards_for_view(Board::get_users_boards($this));
    }

    public function get_shared_boards(): array {
        return $this->get_boards_for_view(Board::get_shared_boards($this));
    }
 
    public function get_others_boards(): array {
        return $this->get_boards_for_view(Board::get_others_boards($this));
    }

    // vérifie si l'utilisateur peut delete le comment $comment
    public function can_delete_comment(Card $card, Comment $comment): bool{
        return $this->is_owner($card->get_board()) || $this->is_author($comment) || $this->is_admin();
    }

    public function admin_selected() {
        if($this->is_admin()) {
            return "selected";
        }
    }

    public function user_selected() {
        if(!$this->is_admin()) {
            return "selected";
        }
    }

    public function get_first_letters() {
        $name = $this->get_fullName();
        $first = substr($name, 0, 1);
        $second = "";
        if (!is_bool(strpos($name, ' ')))
            $second = substr($name, strpos($name, ' ') + strlen(' '), 1);
        return $first . " " . $second;
    }

    public function prepare_JSON() {
        $user = array();
        $user['id'] = $this->get_id();
        $user['fullName'] = $this->get_fullName();
        $user['email'] = $this->get_email();

        return $user;
    }


}
