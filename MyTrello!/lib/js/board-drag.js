$(function() {

    let timer = null;

    $(".move").hide()

    $("#sortableColumnsList").sortable({
        cursor: "move",
        opacity: .7,
        tolerance: "pointer",
        item: "> li",
        revert: true,
        stop: function(event, ui) {
            if (timer) {
                clearTimeout(timer)
            }
            timer = setTimeout(() => {
                var data = $(this).sortable("toArray")
                $.post("column/column_move_service/", { data })
            }, 300)
        }
    })
    $(".sortableCardsList").sortable({
        connectWith: ".sortableCardsList",
        cursor: "move",
        opacity: .7,
        tolerance: "pointer",
        item: "> li",
        revert: true,
        stop: function(event, ui) {
            if (timer) {
                clearTimeout(timer)
            }
            timer = setTimeout(() => {
                // !!! id column et id card dans vue -> pourrait être identique -> string devant
                var draggedCard = ui.item.attr('custom_id');
                var endColumn = ui.item.closest('ul').attr('custom_id');
                var pos_stop = ui.item.index();
                $.ajax({
                    url: "card/card_move_service/",
                    type: "post",
                    data: {
                        card_id: draggedCard,
                        column_id: endColumn,
                        pos: pos_stop
                    }
                })
            }, 300)
        }
    })
})