$(function() {

    $.validator.addMethod("regex", function(value, element, pattern) {
        if (pattern instanceof Array) {
            for (p of pattern) {
                if (!p.test(value))
                    return false;
            }
            return true;
        } else {
            return pattern.test(value);
        }
    });


    $("#loginForm").validate({
        rules: {
            email: {
                required: true,
                email: true,
                remote: {
                    url: "user/email_does_not_exists_service/",
                    type: "post"
                }
            },
            password: {
                required: true,
                minlength: 8,
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/],
                remote: {
                    url: "user/check_unicity_service/",
                    type: "post",
                    data: {
                        email: function() {
                            return $("#email").val()
                        }

                    }
                }

            },
        },
        messages: {
            email: {
                required: "Field cannot be empty",
                email: "Invalid email format",
                remote: "Invalid email"
            },
            password: {
                required: "Field cannot be empty",
                minlength: "Password must be at least 8 characters long",
                regex: "Invalid password format",
                remote: "Invalid email or password"
            },

        },
    });
    $("form input:first").focus();
});