<?php
/**
 * This file permit to display admin snows page
 * Created by PhpStorm.
 * User: Damien Chervet
 * Date: 03.06.2019
 * Time: 09:10
 */

$title="Les Locations";
// Tampon de flux stocké en mémoire
ob_start();
?>

<article>
    <header>
        <h2> Location en cours </h2>
        <div class="table-responsive">
            <table class="table textcolor">
                <tr>
                    <th>Location</th><th>Client</th><th>Prise</th><th>Retour</th><th>Statut</th>
                </tr>
                <?php
                foreach ($leasingsResults as $result) : ?>
                    <tr>
                        <td><a href="index.php?action=displayManageLeasing&idLeasing=<?= $result['id']; ?>"><?= $result['id']; ?></a></td>
                        <td><?= $result['userEmailAddress']; ?></td>
                        <td><?= $result['startDate']; ?></td>
                        <td><?= $result['endDate']; ?></td>
                        <td><?= $result['statut']; ?></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    </header>
</article>
<hr/>

<?php
$content = ob_get_clean();
require 'gabarit.php';
?>