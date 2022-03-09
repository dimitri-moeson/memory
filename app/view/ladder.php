
<h2>Ladder</h2>

<ol>
    <?php /** @var app\model\Ladder $ladder */
    foreach ($ladders as $ladder){ ?>

        <li>
            Nom : <?php echo $ladder->getNom(); ?><br/>
            Timer : <?php echo $ladder->getTimer()->format("i:s");?><br/>
            Essais : <?php echo $ladder->getTry();?><br/>
            Date : <?php echo $ladder->getDateCreate()->format("d/m/Y - H:i:s"); ?><br/>
        </li>
        <br/>
    <?php } ?>
</ol>