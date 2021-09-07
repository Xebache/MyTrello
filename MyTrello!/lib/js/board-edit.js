//plugin edit column
$.fn.editColumn = function() {
    $(this).each(function() {
        const $this = $(this);

        const boardID = $("#board_id").val();
        const columnID = $('input[name="id"]', $this).val();

        const $columnTitle = $('h3', $this);
        const $columnTitleForm = $('form', $this);
        const $columnTitleInput = $('input[name="title"]', $this);

        let isEditMode = false;

        var validatorColumn = validateColumnTitle();

        function validateColumnTitle() {
            return $columnTitleForm.validate({
                errorLabelContainer: $("[id='columnTitleError" + columnID + "']"),
                rules: {
                    title: {
                        required: true,
                        remote: {
                            url: "column/column_edit_service/",
                            type: "post",
                            data: { column_id: columnID, board_id: boardID }
                        },
                        minlength: 3
                    }
                },
                messages: {
                    title: {
                        required: "Field cannot be empty",
                        remote: "A column with the same title already exists",
                        minlength: "Title must be at least 3 characters long"
                    }
                }
            });
        }

        $columnTitle.click(() => enableEditMode());
        $columnTitleInput.blur(() => {
            if (isEditMode)
                $columnTitleForm.submit();
        })

        $columnTitleInput.keyup((e) => {
            //enter
            if (e.keyCode === 13) {
                $columnTitleForm.submit();
            }
            //escape
            if (e.keyCode === 27) {
                $columnTitle.text($columnTitle.text());
                validatorColumn.resetForm();
                stopEditMode();
            }
        })

        function enableEditMode() {
            isEditMode = true;
            $columnTitle.addClass('display_none');
            $columnTitleForm.removeClass('display_none');
            $columnTitleInput.val($columnTitle.text()).focus();
        }

        function stopEditMode() {
            isEditMode = false;
            $columnTitle.removeClass('display_none');
            $columnTitleForm.addClass('display_none');
        }

    })

}

//plugin edit board
$.fn.editBoard = function() {
    const boardID = $("#board_id").val();

    const $this = $(this);
    const $boardTitle = $('h2', $this);
    const $boardTitleForm = $('form', $this);
    const $boardTitleInput = $('input[name="title"]', $this);
    let $titleContent;

    let isEditMode = false;

    const validator = validateBoardTitle();

    $boardTitle.click(() => enableEditMode());

    $boardTitleInput.blur(() => {
        if (isEditMode)
            $boardTitleForm.submit();
    });

    $boardTitleInput.keyup((e) => {
        //enter
        if (e.keyCode === 13) {
            $boardTitleForm.submit();
        }
        //escape
        if (e.keyCode === 27) {
            $boardTitle.text($boardTitle.text());
            validator.resetForm();
            stopEditMode();
        }
    })

    function enableEditMode() {
        isEditMode = true;
        $boardTitle.addClass('display_none');
        $boardTitleForm.removeClass('display_none');
        $titleContent = $boardTitle.text().substring(7, $boardTitle.text().length - 1)
        $boardTitleInput.val($titleContent).focus();
    }

    function stopEditMode() {
        isEditMode = false;
        $boardTitle.removeClass('display_none');
        $boardTitleForm.addClass('display_none');
    }

    function validateBoardTitle() {
        return $boardTitleForm.validate({
            errorLabelContainer: "#boardTitleError",
            rules: {
                title: {
                    required: true,
                    minlength: 3,
                    remote: {
                        url: "board/board_edit_service/",
                        type: "post",
                        data: { board_id: boardID },
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
    }
}

// scripts
$(function() {

    $(".editBoard").remove();
    $(".editBoardJS").show();
    $(".editBoardJS").editBoard();


    $(".editColumn").remove();
    $(".editColumnJS").show();
    $(".editColumnJS").editColumn();

});