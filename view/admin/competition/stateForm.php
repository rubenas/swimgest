<?php if(!isset($competition)) $competition = $data['content']['object'] ?>
<form id="competition-state-<?php echo $competition->getId() ?>" method="post" action="adminCompetition/updateState/<?php echo $competition->getId() ?>">
    <select name="state" class="w-100">
        <option value="closed" <?php
                                if ($competition->getState() == 'closed') echo 'selected'
                                ?>>
            Cerradas
        </option>
        <option value="open" <?php
                                if ($competition->getState() == 'open') echo 'selected'
                                ?>>
            Abiertas
        </option>
    </select>
    <button type="submit" style="display:none" ajax-request='{"url":"adminCompetition/ajaxUpdateState/<?php echo $competition->getId() ?>/v"}'></button>
    <button id="send-email-confirm-<?php echo $competition->getId();?>" modal-target="modal-send-competition-email-confirm-<?php echo $competition->getId();?>" style="display:none" ajax-request='{"url":"adminCompetition/showSendEmailConfirm/<?php echo $competition->getId() ?>/v"}'></button>
</form>