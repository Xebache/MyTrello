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

    $("#signinForm").validate({
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
                minlength: 3,
                regex: /^[a-zA-Z][a-zA-Z0-9]*$/
            },
            password: {
                required: true,
                minlength: 8,
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/]
            },
            confirm: {
                required: true,
                equalTo: "#pwd"
            },
        },
        messages: {
            email: {
                required: "Field cannot be empty",
                email: "Invalid email format",
                remote: "Invalid email"
            },
            fullName: {
                required: "Field cannot be empty",
                minlength: "Name must be at least 3 characters long",
                regex: "Invalid name format"
            },
            password: {
                required: "Field cannot be empty",
                minlength: "Password must be at least 8 characters long",
                regex: "Invalid password format"
            },
            confirm: {
                required: "Field cannot be empty",
                equalTo: "Passwords don't match"
            },

        },
        //errorLabelContainer: '#errors'
    });
    $("form input:first").focus();
});