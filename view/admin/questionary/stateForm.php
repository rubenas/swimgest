<?php if (!isset($questionary)) $questionary = $data['content']['object'] ?>
<form id="questionary-state-<?php echo $questionary->getId() ?>" method="post" action="adminQuestionary/updateState/<?php echo $questionary->getId() ?>">
    <select name="state" class="w-100">
        <option value="closed" <?php
                                if ($questionary->getState() == 'closed') echo 'selected'
                                ?>>
            Cerradas
        </option>
        <option value="open" <?php
                                if ($questionary->getState() == 'open') echo 'selected'
                                ?>>
            Abiertas
        </option>
    </select>
    <button type="submit" style="display:none" ajax-request='{"url":"adminQuestionary/ajaxUpdateState/<?php echo $questionary->getId() ?>/v"}'></button>
</form>