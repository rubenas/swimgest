<?php 
$event = $data['content']['object']
?>

<section class="tab" id="events">
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
    <header>
        <h1>Inscrit@s</h1>
    </header>
    <?php
        include 'subEvent.php';
    ?>
     <div class="my-1 mr-1 row jc-end w-100">
        <a href="adminEvent/list" class="btn" tab-target="events" ajax-request='{"url": "adminEvent/list/v" }'>Volver</a>
    </div>
</section>