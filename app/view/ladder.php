
<h2>Ladder <?php echo $count->counter." resultats" ?></h2>

<table>

    <thead>
        <tr>
            <th>Rang</th>
            <th>Nom</th>
            <th>Timer</th>
            <th>Essais</th>
            <th>Statut</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
    <?php /** @var app\model\Ladder $ladder */
    foreach ($ladders as $i => $ladder){ ?>

        <tr>
            <th><?php echo($i+1); ?></th>
            <td><?php echo $ladder->getNom(); ?></td>
            <td><?php echo $ladder->getTimer()->format("i:s");?></td>
            <td><?php echo $ladder->getTry();?></td>
            <td><?php echo $ladder->getStatus();?></td>
            <td><?php echo $ladder->getDateCreate()->format("d/m/Y - H:i:s"); ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
