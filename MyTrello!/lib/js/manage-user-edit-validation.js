$.fn.editUser = function() {
    $(this).each(function() {

        // Fields
        const $this = $(this);

        const userID = $("input[name='id']", $this).val();

        const $current_user = $("input[name='user']", $this).val();

        const $form = $('.user-edit-form', $this);

        const $name = $(".name p", $this);
        const $email = $(".email p", $this);
        const $role = $(".role p", $this);

        const $nameInput = $('input[name="name"]', $this);
        const $emailInput = $('input[name="email"]', $this);
        const $roleInput = $('select[name="role"]', $this);

        let isEditMode = false;

        var validatorUser = validateUserEdit();


        // actions  

        hideDelete();

        $this.click(() => {
            enableEditMode()
        });

        $nameInput.keyup((e) => {
            //escape
            if (e.keyCode === 27) {
                validatorUser.resetForm();
                stopEditMode();
            }
        })

        $emailInput.keyup((e) => {
            //escape
            if (e.keyCode === 27) {
                validatorUser.resetForm();
                stopEditMode();
            }
        })

        $roleInput.keyup((e) => {
            //escape
            if (e.keyCode === 27) {
                validatorUser.resetForm();
                stopEditMode();
            }
        })


        // methods

        function hideDelete() {
            if ($current_user == userID) {
                $(".delete", $this).addClass("display_none");
            }
        }

        function validateUserEdit() {
            return $form.validate({
                errorLabelContainer: $("div[id^='userEditError']", $this),
                rules: {
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: "user/email_edit_validate/",
                            type: "post",
                            data: {
                                user_id: userID
                            }
                        }
                    },
                    name: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    email: {
                        remote: "Invalid email",
                        required: "Email field cannot be empty",
                        email: "Invalid email format"
                    },
                    name: {
                        required: "Name field cannot be empty",
                        minlength: "Name must be at least 3 characters long"
                    }
                }
            });
        }


        function enableEditMode() {
            isEditMode = true;
            $name.addClass('display_none');
            $email.addClass('display_none');
            $(".delete", $this).addClass('display_none');
            $nameInput.removeClass('display_none');
            $emailInput.removeClass('display_none');
            $(".submit", $this).removeClass('display_none');
            $role.addClass('display_none');
            $roleInput.removeClass('display_none');
            if (userID == $current_user) {
                $roleInput.prop('disabled', 'disabled');
            }

        }

        function stopEditMode() {
            isEditMode = false;
            $name.removeClass('display_none');
            $email.removeClass('display_none');
            $(".delete", $this).removeClass('display_none');
            $nameInput.addClass('display_none');
            $emailInput.addClass('display_none');
            $(".submit", $this).addClass('display_none');
            $role.removeClass('display_none');
            $roleInput.addClass('display_none');
            if (userID == $current_user) {
                $roleInput.prop('disabled', false);
                hideDelete();
            }
        }
    });
}


$(function() {

    $(".table").remove();
    $(".tableJS").attr("hidden", false);
    $(".tableJS").editUser();

    // validation add new user
    $("#addUserForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                    url: "user/email_exists_service/",
                    type: "post"
                }
            },
            fullName: {
                required: true,
                minlength: 3
            }
        },
        messages: {
            email: {
                required: "Email field cannot be empty",
                email: "Invalid email format",
                remote: "Invalid email",
            },
            fullName: {
                required: "Name field cannot be empty",
                minlength: "Name must be at least 3 characters long"
            }
        }

    });

    let $userID;
    $('.dlt_button_user').each(function() {

        $(this).click((e) => {
            $name = $(this).siblings('.n').val();
            $userID = $(this).siblings('.u').val();
            e.preventDefault();
            $('#confirmDialogUser').dialog('open');
            $('#popup_window_user_content').text('Do you really want to delete this user ( ' + $name + ' ) ?');
        });
    })

    $('#confirmDialogUser').dialog({
        resizable: false,
        height: 500,
        width: 500,
        modal: true,
        autoOpen: false,
        buttons: {
            Yes: function() {
                $.post("user/user_delete_service", { id_user: $userID }, function() { location.href = 'user/manage' });
                $(this).dialog("close");
            },
            No: function() {
                $(this).dialog("close");
            }
        },
        close: function() {}
    });
})