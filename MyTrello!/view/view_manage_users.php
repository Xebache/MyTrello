<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="lib/assets/images/logo.png" />
        <title>Manage users</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles2.css" rel="stylesheet" type="text/css"/>
        <link href="lib/assets/fontawesome-free-5.15.3-web/css/all.min.css" rel="stylesheet"/>
        <script src="lib/js/jquery-3.6.0/jquery-3.6.0.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery-validation-1.19.3/jquery.validate.min.js" type="text/javascript"></script>
        <script src="lib/js/jquery-ui-1.12.1.ui-lightness/jquery-ui.js" type="text/javascript"></script>     
        <script src="lib/js/manage-user-edit-validation.js" type="text/javascript"></script>
    </head>
    <body>
        <header id="main_header">
            <?php include('menu.php') ?>
        </header>
        <main>
            <article class="flex_row wrap manage_users">
                <section class="background1">
                    <h2 class="window_title">Manage Users</h2>
                    <table class="table_header">
                        <thead>
                            <tr>
                                <th class="name"><i class="fas fa-user"></td>
                                <th class="email" ><i class="fas fa-at"></i></td>
                                <th class="role"><i class="fas fa-user-shield"></td>
                            </tr>           
                        </thead>
                    </table>
                    <?php foreach($users as $member): ?>
                    <div class="tableJS" hidden>
                        <form action="user/edit" class="user-edit-form" method="post">
                            <table>
                                <tr>
                                    <td class="name">
                                        <p><?= $member->get_fullName() ?></p>
                                        <input type="text" name="name" value="<?= $member->get_fullName() ?>" class="display_none">
                                    </td>
                                    <td class="email">
                                        <p><?= $member->get_email() ?></p>
                                        <input type="email" name="email" value="<?= $member->get_email() ?>" class="display_none">
                                    </td>
                                    <td class="role">
                                        <p><?= ucfirst($member->get_role()) ?></p>
                                        <select name="role" class="display_none">
                                            <option value="admin" <?= $member->admin_selected() ?>>Admin</option>
                                            <option value="user" <?= $member->user_selected() ?>>User</option>
                                        </select>
                                    </td>
                                        <td class="delete">
                                            <input type="text" class='u' name="id" value="<?= $member->get_id() ?>" form="delete_user_<?=$member->get_id()?> " hidden >
                                            <input type='text' class="n" name='title' value='<?= $member->get_fullName() ?>' form="delete_user_<?=$member->get_id()?> " hidden>
                                            <input type="submit" value="&#xf2ed" class="trash far fa-trash-alt dlt_button_user" form="delete_user_<?=$member->get_id()?> " style="background:none">
                                        </td>
                                    <td class="submit display_none">
                                        <input type="text" name="user" value="<?= $user->get_id() ?>" hidden>
                                        <input type="text" name="id" value="<?= $member->get_id() ?>" user_id_edit="user_id" hidden>
                                        <input type="submit" class="fas fa-check" value="&#xf00c">
                                    </td>
                                </tr>
                            </table> 
                        </form>
                        <form class="delete_user delete_user_manage" id="delete_user_<?=$member->get_id()?> " action="user/delete" method="post"> </form>
                        <div id='userEditError<?=$member->get_id()?>'></div>
                    </div>
                    <div class="table">
                        <input class="checkbox" type="checkbox" name="edit_user_<?= $member->get_id() ?>" id="edit_user_<?= $member->get_id() ?>" hidden>
                        <div class="user_info flex_row">
                            <table>
                                <tr>
                                    <td class="name"><?= $member->get_fullName() ?></td>
                                    <td class="email"><?= $member->get_email() ?></td>
                                    <td class="role"><?= ucfirst($member->get_role()) ?></td>
                                    <td><label for="edit_user_<?= $member->get_id() ?>"><i class="fas fa-edit"></i></label></td>
                                    <?php if($user != $member): ?>
                                        <td>
                                            <form class="delete_user delete_user_manage" action="user/delete" method="post">
                                                <input type="text" class='u' name="id" value="<?= $member->get_id() ?>" hidden>
                                                <input type='text' class="n" name='title' value='<?= $member->get_fullName() ?>' hidden>
                                                <input type="submit" value="&#xf2ed" class="trash far fa-trash-alt dlt_button_user" style="background:none">
                                            </form>
                                        </td>
                                    <?php endif ?>
                                </tr>
                            </table> 
                        </div>
                        <div class="user_edit">
                            <form action="user/edit" class="editUserForm" method="post">
                                <table>
                                    <tr>
                                        <td class="name">
                                            <input type="text" name="name" value="<?= $member->get_fullName() ?>" required>
                                        </td>
                                        <td  class="email">
                                            <input type="email" name="email" value="<?= $member->get_email() ?>"required>
                                        </td>
                                        <?php if($user != $member): ?>
                                            <td class="role">
                                                <select name="role">
                                                    <option value="admin" <?= $member->admin_selected() ?>>Admin</option>
                                                    <option value="user" <?= $member->user_selected() ?>>User</option>
                                                </select>
                                            </td>
                                        <?php else: ?>
                                            <td class="role">
                                                <input type="text" name="role" value="<?= ucfirst($member->get_role()) ?>" disabled>
                                            </td>
                                        <?php endif ?>
                                        <td>
                                            <label for="edit_user_<?= $member->get_id() ?>"><i class="fas fa-arrow-left"></i></label>
                                        </td>
                                        <td>
                                            <input type="text" name="id" value="<?= $member->get_id() ?>" user_id_edit="user_id" hidden>
                                            <input type="submit" class="fas fa-check" value="&#xf00c">
                                        </td>
                                    </tr> 
                                </table>
                            </form>
                        </div>
                    </div>
                    
                    <?php if ($errors->has_errors("user", "edit", $member->get_id())): ?>
                        <?php include('errors.php'); ?>
                    <?php endif; ?>
                <?php endforeach ?>                
                <div class="popup_window " id="confirmDialogUser" hidden>
                    <h2 class="window_title"><i class="far fa-trash-alt"></i></h2>
                    <p id="popup_window_user_content"> ISSOU </p>
                </div>   
                </section>
                <section>
                    <div id="errors"></div>
                    <form class="main_form background1" id="addUserForm" action="user/add"  method="post">
                        <h2 class="window_title">Add a user</h2>
                        <ul>
                            <li class="flex_row center form_row">
                                <label for="email"><i class="fas fa-at"></i></label>
                                <input type="email" id="email" name="email" placeholder="Email" required>
                            </li>
                            <li class="flex_row center form_row">
                                <label for="fullname"><i class="fas fa-user"></i></label>
                                <input type="text" name="fullName" placeholder="Full Name" required>
                            </li>
                            <li class="flex_row center form_row">
                                <label for="role"><i class="fas fa-user-shield"></i></label>
                                <select name="role" required>
                                    <option value="" disabled selected>Select your option</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </li>
                            <li class="flex_row center form_row submit">
                                <input type="submit" value="Add">
                            </li>
                        </ul>
                    </form>
                </section>
            </article>
        </main>
    </body>
</html>
