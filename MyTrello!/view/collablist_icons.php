<ul class="flex_row collab_list_icon">
    <li><button class="owner" title="<?= $board->get_owner_fullName() ?>"><?= $board->get_owner_first_letters() ?></button></li>
    <?php foreach (array_slice($board->get_collaborations(), 0, 4) as $collaboration) :?>
        <li><button title="<?= $collaboration->get_collaborator_fullName() ?>"><?= $collaboration->get_collaborator_first_letters() ?></button></li>
    <?php endforeach ?>
    <?php if(count($board->get_collaborations()) > 4):?>
        <li class="plus"><button>+</button></li>
    <?php endif ?>
</ul>