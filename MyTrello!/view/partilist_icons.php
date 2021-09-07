<ul class="flex_row collab_list_icon">
    <?php foreach (array_slice($card->get_participations(), 0, 3) as $participation) :?>
        <li><button class="<?= $participation->board_owner($board) ?>" title="<?= $participation->get_participant_fullName() ?>"><?= $participation->get_participant_first_letters() ?></button></li>  
    <?php endforeach ?>
    <?php if(count($card->get_participations()) > 3):?>
        <li class="plus"><button>+</button></li>
    <?php endif ?>
</ul>