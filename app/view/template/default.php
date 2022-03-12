<!DOCTYPE html>
<html lang="en" >

<head>

    <meta charset="UTF-8">

    <title>O'clock - Jeu de mémoire</title>

    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.0.13/css/all.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css'>
    <link rel='stylesheet' href='styles/styles.css'>

</head>

    <body>

        <header>
            <h1>Jeu de mémoire</h1>
        </header>

        <nav>
            <ul>
                <li <?php echo $view ==="game" ? "class='active'" : "" ?> ><a href="?p=default.game" onclick="return confirm('nouvelle partie ?')" >Jeu</a></li>
                <li <?php echo $view ==="ladder" ? "class='active'" : "" ?> ><a href="?p=default.ladder">Classement</a></li>
            </ul>
        </nav>

        <?php echo $content ?>

        <footer>

        </footer>

    </body>

</html>