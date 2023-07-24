<?php if (!isset($event)) $event = $data['content']['object'];?>
<section class="tab" id="events">
    <header>
        <h1>Detalles del evento</h1>
    </header>
    <main>
        <?php include 'subEventDetails.php'; ?>
    </main>
    <footer class="mb-1 mr-1 row jc-end w-100">
        <a href="adminEvent/list" class="btn">Finalizar</a>
    </footer>
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
</section>