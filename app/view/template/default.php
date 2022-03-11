<!DOCTYPE html>
<html lang="en" >

<head>

    <meta charset="UTF-8">

    <title>Memory</title>


    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.0.13/css/all.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css'>
    <link rel='stylesheet' href='styles/styles.css'>

    <script>
        window.console = window.console || function(t) {};

        if (document.location.search.match(/type=embed/gi)) {
            window.parent.postMessage("resize", "*");
        }

    </script>

</head>

<body translate="no" >

    <header>
        <h1>Memoriel</h1>
    </header>
    <ul>
        <li <?php echo $view ==="game" ? "class='active'" : "" ?> ><a href="?p=default.game">Jeu</a></li>
        <li <?php echo $view ==="ladder" ? "class='active'" : "" ?> ><a href="?p=default.ladder">Classement</a></li>

    </ul>

    <?php echo $content ?>

</body>

</html>