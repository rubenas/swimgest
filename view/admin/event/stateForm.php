<?php if (!isset($event)) $event = $data['content']['object'] ?>
<form id="event-state-<?php echo $event->getId() ?>" method="post" action="adminEvent/updateState/<?php echo $event->getId() ?>">
    <select name="state" class="w-100">
        <option value="closed" <?php
                                if ($event->getState() == 'closed') echo 'selected'
                                ?>>
            Cerradas
        </option>
        <option value="open" <?php
                                if ($event->getState() == 'open') echo 'selected'
                                ?>>
            Abiertas
        </option>
    </select>
    <button type="submit" style="display:none" ajax-request='{"url":"adminEvent/ajaxUpdateState/<?php echo $event->getId() ?>/v"}'></button>
</form>