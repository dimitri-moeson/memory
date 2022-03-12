

<h2>Classement</h2>
<table>

    <tbody>
    <?php

    /** @var app\model\Ladder $ladder */

    foreach ($ladders as $i => $ladder){ ?>

        <tr>
            <th><?php echo intval($i+1); ?></th>
            <td><?php echo $ladder->getNom(); ?></td>
            <td><?php echo $ladder->getTimer()->format("i:s");?></td>
            <td><?php echo $ladder->getTry();?></td>
            <td><?php echo $ladder->getDateCreate()->format("d/m/Y - H:i"); ?></td>
        </tr>
    <?php } ?>
    </tbody>

    <thead>
    <tr>
        <th>Rang</th>
        <th>Nom</th>
        <th>Timer</th>
        <th>Essais</th>
        <th>Date</th>
    </tr>
    </thead>
    <tfoot>
        <tr>
            <td></td>
            <th><h3><?php echo $count." resultats" ?></h3></th>
        </tr>
    </tfoot>
</table>
