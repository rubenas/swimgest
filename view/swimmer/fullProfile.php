<section class="tab" id="my-profile">
    <header>
        <h1>Mi perfil</h1>
    </header>
    <main id="profile-container">
        <?php 
            require_once 'profile.php';
            require_once 'marks.php';
         ?>
    </main>
</section>
<script type="module">import {activateTabLink} from './public/js/modules/tab.js';activateTabLink('my-profile');</script>