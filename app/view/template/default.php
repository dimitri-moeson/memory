<?php

use kernel\Request;

?><!DOCTYPE html>
<html lang="en" >

<head>

    <meta charset="UTF-8">

    <title>O'clock - Jeu de mémoire</title>

    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.0.13/css/all.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css'>
    <link rel='stylesheet' href='<?php echo Request::url(["styles","styles.css"], true ) ?>'>

</head>

    <body>

        <header>
            <h1><a href="<?php echo Request::url() ?>">Jeu de mémoire</a></h1>
        </header>

        <nav>
            <ul>
                <li <?php echo $view ==="game" ? "class='active'" : "" ?> ><a href="<?php echo Request::url(["jeu"]) ?>" onclick="return confirm('nouvelle partie ?')" >Nouveau Jeu</a></li>
                <li <?php echo $view ==="ladder" ? "class='active'" : "" ?> ><a href="<?php echo Request::url(["classement"]) ?>">Classement</a></li>
            </ul>
        </nav>

        <?php echo $content ?>

        <footer>

        </footer>

    </body>

</html>