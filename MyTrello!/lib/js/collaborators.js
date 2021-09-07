$(function() {

    const boardID = $("input[name='board_id']").val();
    const boardTitle = $("title").text().charAt(0).toLowerCase() + $("title").text().slice(1);;
    let collaborators;
    let others;
    let shown = false;
    let userID;
    let otherID;

    $(".collab-php").remove();

    buildHtml();

    $(".title_list_container").click(() => showList());
    $(".collab_list").click((e) => showDialog(e));
    $("#others").click(() => selectCollab());
    $("button").click(() => addCollaborator());

    function buildHtml() {
        buildList();
        buildCombo();
    }

    function buildList() {
        $(".main-article").append("<section class='collabListJS'>");
        $(".collabListJS").append("<div class='flex_row title_list_container'>");
        $(".title_list_container").append("<i class='fas fa-caret-down title-list-down'></i>");
        $(".title_list_container").append("<i class='fas fa-caret-up title-list-up display_none'></i>");
        $(".title_list_container").append("<h3 id='title_list'>Current collaborator(s):");
        $(".collabListJS").append("<ul class='collab_list display_none'>");
        getCollaborators();
    }

    function buildCombo() {
        $(".main-article").append("<section class='collabComboJS'>");
        $(".collabComboJS").append("<h3>Add a new collaborator");
        $(".collabComboJS").append("<div class='add flex_row center' id='addCollaboratorForm'>");
        $("#addCollaboratorForm").append("<select name='user_id' id='others'>");
        getOthers();
        $("#addCollaboratorForm").append("<button value='&#xf067' class='fas fa-plus'>");
    }

    function getCollaborators() {
        $.post("collaboration/get_collaborators_service", { board_id: boardID }, function(data) {
            collaborators = JSON.parse(data);
            if (collaborators.length == 0) {
                $(".collab_list").append("<p>No collaboration yet");
            } else {
                for (let i = 0; i < collaborators.length; ++i) {
                    $(".collab_list").append(`<li id='${collaborators[i].id}'>${collaborators[i].fullName}`);
                }
            }
        });
    }

    function getOthers() {
        $.post("collaboration/get_others_service", { board_id: boardID }, function(data) {
            others = JSON.parse(data);
            if (others.length == 0) {
                $("#addCollaboratorForm button").attr("disabled", true);
            } else {
                $("#addCollaboratorForm button").attr("disabled", false);
                for (let i = 0; i < others.length; ++i) {
                    $("#others").append('<option value=' + others[i].id + '>' + others[i].fullName + ' (' + others[i].email + ')');
                }
            }
        });
    }

    function showList() {
        if (!shown) {
            shown = true;
            $(".collab_list").removeClass('display_none');
            $(".title-list-down").addClass('display_none');
            $(".title-list-up").removeClass('display_none');
        } else {
            shown = false;
            $(".collab_list").addClass('display_none');
            $(".title-list-down").removeClass('display_none');
            $(".title-list-up").addClass('display_none');
        }
    }

    function initUserID(e) {
        return e.target.id;
    }

    function showDialog(e) {
        userID = initUserID(e);
        $("#confirmDialogCollab").empty();
        $("#confirmDialogCollab").append('<p> Delete ' + e.target.outerText + ' from ' + boardTitle + ' ?');
        $("#confirmDialogCollab").dialog('open');
    }

    $("#confirmDialogCollab").dialog({
        resizable: false,
        height: 200,
        width: 500,
        modal: true,
        autoOpen: false,
        buttons: {
            Delete: function() {
                $.post("collaboration/delete_collaboration_service", { board_id: boardID, collaborator_id: userID },
                    function(data) {
                        refresh();
                    }
                )
                $(this).dialog("close");
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }
    });

    function addCollaborator() {
        $.post("collaboration/add_collaboration_service", { board_id: boardID, collaborator_id: otherID },
            function(data) {
                refresh();
            }
        );
    }

    function selectCollab() {
        otherID = $("#others option:selected").val();
    }

    function refresh() {
        $(".collab_list").empty();
        getCollaborators();
        $("#others").empty();
        getOthers();
    }

});