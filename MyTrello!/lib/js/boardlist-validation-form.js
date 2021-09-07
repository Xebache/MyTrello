$(function() {

    $("#addBoardForm").validate({
        errorLabelContainer: "#addBoardErrors",
        rules: {
            title: {
                required: true,
                minlength: 3,
                remote: {
                    url: "board/board_title_exists_service/",
                    type: "post"
                }
            }
        },
        messages: {
            title: {
                required: "Field cannot be empty",
                remote: "A board with the same title already exists",
                minlength: "Title must be at least 3 characters long"
            }
        }
    });

});