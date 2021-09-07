<?php

/**/
require_once "framework/Controller.php";
require_once "model/User.php";
require_once "CtrlTools.php";
require_once "ValidationError.php";

class ControllerUser extends Controller {

    public function email_exists_service() {
        $res = "true";
        if(isset($_POST["email"]) && $_POST["email"] !== "") {
            $email = $_POST["email"];
            $user = User::get_by_email($email);
            if($user)
                $res = "false";
            echo $res;
        }else{
            Tools::abort("Error : UserController can not generate web page with that function");
        }
    }

    public function email_does_not_exists_service() {
        $res = "false";
        if(isset($_POST["email"]) && $_POST["email"] !== "") {
            $email = $_POST["email"];
            $user = User::get_by_email($email);            
            if($user)
                $res = "true";
            echo $res;
        }else{
            Tools::abort("Error : UserController can not generate web page with that function");
        }
    }

    public function email_edit_validate(){
        $this->get_user_or_redirect();
        $res = "true";
        if(isset($_POST["email"]) && $_POST["email"] !== "" && isset($_POST["user_id"])) {
            $email = $_POST["email"];
            $userGetByEmail = User::get_by_email($email);
            $userCurrent = User::get_by_id($_POST["user_id"]);
            if(!is_null($userGetByEmail) && $userGetByEmail->get_id() != $userCurrent->get_id())
                $res = "false";
            echo $res;
        }else{
            Tools::abort("Error : UserController can not generate web page with that function");
        }
    }

    public function user_delete_service(){
        $user=$this->get_user_or_redirect();

        if(!empty($_POST['id_user'])){
            $member_id = $_POST["id_user"];
            $member = User::get_by_id($member_id);
            
            if($user->is_admin() && !is_null($member)) {
                $member->delete();
            }
        }else{
            Tools::abort("Error : UserController can not generate web page with that function");
        }
    }

    public function check_unicity_service() {
        $res = "false";
        if(isset($_POST["email"]) && $_POST["email"] !== "" && $_POST["password"] && $_POST["password"]) {
            $password = $_POST["password"];
            $email = $_POST["email"];
            $user = User::get_by_email($email);
            $res = $user->check_password($password) ? "true" : "false";
            echo $res;
        }else{
            Tools::abort("Error : UserController can not generate web page with that function");
        }
    }

    public function index() {
        if ($this->user_logged()) {
            $this->redirect();
        } else {
            $this->login();
        }
    }


    public function login() {
        if ($this->user_logged()) {
            $this->redirect();
        }

        $email = '';
        $password = '';
        $error = new ValidationError();

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $error->set_messages_and_add_to_session(User::validate_login($email, $password));
        
            if ($error->is_empty()) {
                $this->log_user(User::get_by_email($email));
            }
        }
        (new View("login"))->show(array(
            "email" => $email, 
            "password" => $password, 
            "errors" => $error)
        );
    }

    public function logout() {
        session_destroy();
        $this->redirect();
    }


    public function signup() {
        if ($this->user_logged()) {
            $this->redirect();
        }
        $email = '';
        $password = '';
        $fullName = '';
        $confirm = '';
        $user = null;
        $error = new ValidationError();

        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['fullName']) && isset($_POST['confirm'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $fullName = $_POST['fullName'];
            $confirm = $_POST['confirm'];
            
            $user = new User($email, $fullName, null, $password);
            $error->set_messages_and_add_to_session($user->validate($confirm));

            if($error->is_empty()) {
                $user->insert();
                $this->log_user($user);
            }
        }
        (new View("signup"))->show(array(
            "email" => $email, 
            "password" => $password,
            "fullName" => $fullName,
            "confirm" => $confirm, 
            "errors" => $error)
        );
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function manage() {
        $user = $this->get_user_or_redirect();
        $users = User::get_users();
        if($user->is_admin()){
            (new View("manage_users"))->show(array(
                "user" => $user, 
                "users" => $users,
                "errors" => ValidationError::get_error_and_reset()
                )
            );
            die;
        }
        $this->redirect();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function edit() {
        $user = $this->get_user_or_redirect();
        $error = new ValidationError();

        if(isset($_POST["id"])) {
            $user_edit_id = $_POST["id"];
            $user_edit = User::get_by_id($user_edit_id);
            $new_email = $user_edit->get_email();

            if($user == $user_edit) {
                $_SESSION["user"] = $user_edit;
            }

            if($_POST['name'] != $user_edit->get_fullName()) {
                $new_name = $_POST['name'];
                $user_edit->set_fullName($new_name);
            }

            if($_POST['email'] != $user_edit->get_email()) {
                $new_email = Validation::sanitize_string($_POST['email']);
            }

            if(isset($_POST['role'])) {
                $new_role = $_POST['role'];
                $user_edit->set_role($new_role);
            }
            
            $error = new ValidationError($user_edit, "edit");
            $error->set_messages_and_add_to_session($user_edit->validate_edit($new_email));

            if($error->is_empty() && $user->is_admin()) {    
                $user_edit->update();
            } 
            $this->redirect("user", "manage");
        }
        $this->redirect();
    }

    public function add() {
        $user = $this->get_user_or_redirect();
        $email = '';
        $fullName = '';
        $role = '';
        $password = '';
        $new_user = null;
        $error = new ValidationError();

        if (isset($_POST['email']) && isset($_POST['fullName']) && isset($_POST['role'])) {
            $email = $_POST['email'];
            $fullName = $_POST['fullName'];
            $role = $_POST['role'];
            $password = "Password1,";
            $password_confirm = $password;
            
            $new_user = new User($email, $fullName, $role, $password);
            $error->set_messages_and_add_to_session($new_user->validate($password_confirm));

            if($error->is_empty() && $user->is_admin()) {
                $new_user->insert();
            }
        }
        $this->redirect("user", "manage");
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function delete() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST['id']) && $user->is_admin() ) {
            $user_id = $_POST['id'];
            if($user->get_id()!=$user_id){
                $this->redirect("user", "delete_confirm", $user_id);
            }
        }
        $this->redirect("user", "manage");
    }

    public function delete_confirm() {
        $user = $this->get_user_or_redirect();

        if(isset($_GET['param1'])) {
            $user_id = $_GET['param1'];
            $member = User::get_by_id($user_id);

            if(!is_null($member) && $user->is_admin()) {
                (new View("delete_confirm"))->show(array(
                    "user" => $user, 
                    "instance" => $member)
                );
                die;
            }
        }
        $this->redirect("user", "manage");
    }


    public function remove() {
        $user = $this->get_user_or_redirect();

        if(isset($_POST["id"])) {
            $member_id = $_POST["id"];
            $member = User::get_by_id($member_id);
            
            if(isset($_POST["delete"]) && $user->is_admin() && !is_null($member) && $member->get_id() != $user->get_id()) {
                $member->delete();
            }
        }
        $this->redirect("user", "manage");
    }


}