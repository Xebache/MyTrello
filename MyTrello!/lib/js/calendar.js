$(function() {

    const colors = ["#fdb93a", "#5b9c6d", "#6ea0bd", "#2e5065", "#9A2E1F",
        "#696952", "#a65222", "#f2bd2c", "#7dae7ab", "#e5511f",
        "#f686ae", "#8b47b1", "#b2435f", "#c7bbed", "#5647b1"
    ];

    let checked;

    var calendar;
    var calendarEl = document.getElementById('calendar');

    $.post('calendar/get_boards', function(data) {
        checked = JSON.parse(data);
        initCalendar();
    });

    function initCalendar() {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listYear'
            },

            // display des cards
            events: {
                url: 'calendar/boards_filter_service/',
                method: "post",
                extraParams: function() {
                    return { checked: checked }
                }
            },

            eventDidMount: function(info) {
                // couleur des events
                if (info.event.extendedProps.board_id) {
                    const idx = info.event.extendedProps.board_id;
                    info.el.style.background = colors[idx % colors.length];
                    info.el.style.padding = "5px";
                    info.el.style.border = "1px solid white";
                }
                // couleur du dot ds list
                const dot = $(".fc-list-event-dot", info.el)[0];
                if (dot) {
                    if (info.event.extendedProps.due) {
                        dot.style.borderColor = "red";
                    } else {
                        dot.style.borderColor = "green";
                    }
                }
            },

            // description des cards qd click
            eventClick: function(info) {
                $("#showCard").dialog({
                    resizable: false,
                    height: 300,
                    width: 400,
                    modal: true,
                    closeOnEscape: true,
                    buttons: {
                        Ok: function() {
                            $(this).dialog("close");
                        }
                    }
                });
                buildCardModal(info.event.extendedProps.description);
            }

        });
        calendar.render();
    }

    // couleurs ds boards list
    let i;
    for (i = 0; i < $("li").length; i++) {
        $("li")[i].style.background = colors[$("input")[i].value % 15];
    }

    // listener sur checkbox
    $("ul.calendarCheckboxes").change(function() {
        checked = $(":checked").map(function() {
            return this.value;
        }).toArray();
        if (checked.length != 0)
            calendar.refetchEvents();
        else
            calendar.removeAllEvents();
    });

    // html popup card
    function buildCardModal(card) {
        $(".popup_card_info").empty();
        $(".popup_card_info").append("<div class='flex_row popup_card_header'>");
        $(".popup_card_header").append("<h3>" + card['title']);
        $(".popup_card_header").append("<ul class='flex_row collab_list_icon popup_card_ul'>");
        for (let i = 0; i < card['participants'].length; ++i) {
            $(".popup_card_ul").append("<li><button>" + card['participants'][i]);
        }
        $(".popup_card_info").append("<p class='credit'>Created on the " + card['created'] + " by <strong>" + card['author']);
        $(".popup_card_info").append("<p class='credit'>Last modified on the " + card['modified']);
        $str = card['body'];
        $str = $str.substr(0, 300) + "...";
        $(".popup_card_info").append("<p>" + $str);
    }

});