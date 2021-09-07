$(function() {

    $("#edit_card").validate({
        errorLabelContainer: "#edit_card_error",
        rules: {
            title: {
                remote: {
                    url: "card/card_edit_service/",
                    type: "post",
                    data: {
                        card_id: function() {
                            return $("#cardId").val()
                        }
                    }
                },
                minlength: 3,
                required: true
            }
        },

        messages: {
            title: {
                remote: "A card with the same title already exists",
                minlength: "Title must be at least 3 characters long",
                required: "Field cannot be empty"
            }

        },
    });

})