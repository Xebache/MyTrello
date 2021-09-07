$(function() {

    const boardID = $("#board_id").val();

    $("#addColumnForm").validate({
        errorLabelContainer: '#addColumnErrors',
        rules: {
            title: {
                required: true,
                minlength: 3,
                remote: {
                    url: "column/column_title_exists_service/",
                    type: "post",
                    data: {
                        id: function() {
                            return boardID;
                        },
                    }
                },
            }
        },

        messages: {
            title: {
                remote: "A column with the same title already exists",
                required: "Field cannot be empty",
                minlength: "Title must be at least 3 characters long"
            }
        },
    });

    $(".addCardForm").each(function() {
        const columnID = $(".column_id", $(this)).val();
        const $form = $(this).validate({
            errorLabelContainer: $("#cardError" + columnID),
            rules: {
                title: {
                    remote: {
                        url: "card/card_title_exists_service/",
                        type: "post",
                        data: {
                            column_id: function() {
                                return $(".column_id").val()
                            }
                        }
                    },
                    minlength: 3,
                    required: true
                }
            },

            messages: {
                title: {
                    required: "Field cannot be empty",
                    remote: "A card with the same title already exists",
                    minlength: "Title must be at least 3 characters long"
                }
            },
        })
    })

})