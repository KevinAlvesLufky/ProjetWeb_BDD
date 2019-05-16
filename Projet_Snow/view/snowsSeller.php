<?php
/**
 * This file permit to display admin snows page
 * Created by PhpStorm.
 * User: Damien Chervet
 * Date: 06.05.2017
 * Time: 09:10
 */

$title="Nos snowboards";
// Tampon de flux stocké en mémoire
ob_start();
?>

<article>
    <header>
        <h2> Nos snows</h2>
        <div class="table-responsive">
            <table class="table textcolor">
                <tr>
                    <th>Code</th><th>Marque</th><th>Modèle</th><th>Longueur</th><th>Prix</th><th>Disponibilité</th><th>Photo</th>
                </tr>

                <?php
                foreach ($snowsResults as $result) : ?>
                    <tr>
                        <td><a href="index.php?action=displayASnow&code=<?= $result['code']; ?>"><?= $result['code']; ?></a></td>
                        <td><?= $result['brand']; ?></td>
                        <td><?= $result['model']; ?></td>
                        <td><?= $result['snowLength']; ?> cm</td>
                        <td>CHF <?= $result['dailyPrice']; ?>.- par jour</td> <!-- Prices are not float -->
                        <td><?= $result['qtyAvailable']; ?></td>
                        <td><a href="<?= $result['photo']; ?>" target="blank"><img src="<?= $result['photo']; ?>" style="height: 20px"></a></td>
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



    <div  class="table-responsive">
        <table class="table table-dark">
            <thead class="thead-dark">
            <tr>
                <th>Code</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Longueur</th>
                <th>Prix</th>
                <th>Disponibilité</th>
                <th>Photo</th>
            </tr>
            </thead>
            <?php foreach ($snowsResults as $snow):?>
                <tr>
                    <td><a href="index.php?action=displayASnow&code=<?= $snow['code']; ?>"><?php echo $snow['code'] ?></a></td>
                    <td><?php echo $snow['brand'] ?></td>
                    <td><?php echo $snow['model'] ?></td>
                    <td><?php echo $snow['snowLength'] ?> cm</td>
                    <td>CHF <?php echo $snow['dailyPrice'] ?>.- par jour</td>
                    <td><?php echo $snow['qtyAvailable'] ?></td>
                    <td><img src="<?php echo $snow['photo']; ?>" alt="<?= $snow['code']; ?>" style="width: 10%;"></td>
                </tr>
            <?php endforeach ?>
        </table>
    </div>
