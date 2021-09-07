$(function() {

    const boardID = $("#board_id").val();

    $("#delete_btn").click(function(e) {
        e.preventDefault();
        $('#confirmDialog').dialog('open');
    });

    $('#confirmDialog').dialog({
        resizable: false,
        height: 500,
        width: 500,
        modal: true,
        autoOpen: false,
        buttons: {
            Delete: function() {
                $.post("board/delete_board_service", { "id_board": boardID }, function() { location.href = 'board/index' });
                $(this).dialog("close");
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {}
    });

    let idColumn;
    let titleColumn;

    $(".dlt_button_column").each(function() {
        $(this).click(function(e) {
            e.preventDefault();
            idColumn = $(e.target).attr('delete_column_id');
            $.post("column/is_column_empty_service", { id_column: idColumn }, function(data) {
                if (data == "true") {
                    $.post("column/column_delete_service", { "id_column": idColumn }, function() { location.href = 'board/board/' + boardID });
                } else {
                    $('#confirmDialogColumn').dialog('open');
                    titleColumn = $(e.target).attr('delete_column_title');
                    $('#popup_window_column_content').text('Do you really want to delete this column ( ' + titleColumn + ' ) ?');
                }
            });
        });
    });

    $('#confirmDialogColumn').dialog({
        resizable: false,
        height: 350,
        width: 500,
        modal: true,
        autoOpen: false,
        buttons: {
            Delete: function() {
                $.post("column/column_delete_service", { "id_column": idColumn }, function() { location.href = 'board/board/' + boardID });
                $(this).dialog("close");
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {}
    });


    let idCard;
    let titleCard;
    let card_idBoard;

    $(".dlt_button_card").each(function() {
        $(this).click(function(e) {
            e.preventDefault();
            $('#confirmDialogCard').dialog('open');
            idCard = $(e.target).attr('delete_card_id');
            titleCard = $(e.target).attr('delete_card_title');
            card_idBoard = $(e.target).attr('delete_card_idBoard');
            $('#popup_window_card_content').text('Do you really want to delete this card ( ' + titleCard + ' ) ?');

        })
    });

    $('#confirmDialogCard').dialog({
        resizable: false,
        height: 350,
        width: 500,
        modal: true,
        autoOpen: false,
        buttons: {
            Delete: function() {
                $.post("card/card_delete_service", { "id_card": idCard }, function() { location.href = 'board/board/' + card_idBoard });
                $(this).dialog("close");
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {}
    });


});