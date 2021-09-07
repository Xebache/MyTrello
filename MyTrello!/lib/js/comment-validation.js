$(function() {


    $("#addCommentForm").validate({
        errorLabelContainer: "#commentErrorContainer",
        rules: {
            body: {
                required: true
            }
        },

        messages: {
            body: {
                required: "Comment field cannot be empty"
            }
        },
    });

    $(".commentAllForm").each(function() {
        const $form = $(this).validate({
            errorLabelContainer: "#errorCommentEdit",
            rules: {
                body: {
                    required: true
                }
            },

            messages: {
                body: {
                    required: "Comment field cannot be empty"
                }
            },
        })
    });

})