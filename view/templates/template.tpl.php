<!DOCTYPE html>
<html lang="es">

<head>
    <!--CAMBIAR AL MOVER A SERVIDOR REAL-->
    <base href="http://localhost/swimgest/">
    <title>SwimGest</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="public/styles/css/styles.css">
    <link rel="stylesheet" type="text/css" href="public/styles/css/profile_styles.css">
    <style>
        .ck-content {
            min-height:10rem;
        }
    </style>
</head>

<body>
    <header class="container-fluid">
        <?php include 'navbar.tpl.php'; ?>
    </header>
    <!--CONTENT-->
    <main class="container">
        <div class="row jc-between">
            <!--MENU-->
            <section class="col-12 col-md-3 p-1 mt-md-2">
                <nav class="nav-tabs">
                    <?php
                    if ($data['isAdmin'] && $data['isAdminArea']) {

                        include 'adminMenu.tpl.php';
                    } else {

                        include 'swimmerMenu.tpl.php';
                    }
                    ?>
                </nav>
            </section>

            <!--TABS-->
            <section id="tab-container" class="col-12 col-md-9 p-1 mt-md-2">
                    <?php require_once './view/'.$controller->getView().".php" ?>
            </section>
        </div>
    </main>
    <!--MODAL WINDOWS-->
    <section id="modalWindows">
        <?php
        if ($data['isAdminArea']) { 
            
            include 'adminModals.tpl.php';
            
        } else {

            include 'swimmerModals.tpl.php';
        }
        ?>
    </section>
    <!--ERROR AND SUCCESS MESSAGES-->
    <div class="error"></div>
    <div class="success"></div>

    <!--SPINNER FOR AJAX REQUESTS-->
    <section class="loading"></section>
    <script src="public/js/ckeditor/build/ckeditor.js"></script>
    <script type="module" src="public/js/main.js"></script>
</body>
</html>