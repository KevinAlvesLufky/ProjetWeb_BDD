<?php
/**
 * This file permit to display admin snows page
 * Created by PhpStorm.
 * User: Damien Chervet
 * Date: 03.06.2019
 * Time: 09:10
 */

$title="Gestion des Retours";
// Tampon de flux stocké en mémoire
ob_start();
?>

<article>
    <header>
        <h2> Gestion des Retours </h2>
        <p>ID Location : <?= $result['LeasingID']?>   EMail : <?= $_SESSION['userEmailAddress'] ?> </p>
        <p>Prise : <?= $result['DateTake']?>   Retour : <?= $result['DateRetrun']?> ?> </p>
        <p>Statut : <?= $result['Statut'] ?></p>

        <div class="table-responsive">
            <table class="table textcolor">
              <form>
                <tr>
                    <th>Code</th><th>Quantité</th><th>Prise</th><th>Retour</th><th>Statut</th>
                </tr>
                <?php
                foreach ($LeasingResults as $result) : ?>
                    <tr>
                        <td><?= $result['code']; ?></td>
                        <td><?= $result['qty']; ?></td>
                        <td><?= $result['DateTake']; ?></td>
                        <td><?= $result['DateRetrun']; ?></td>
                        <!-- Mettre checkbox pour choisir, tester ensuite l'état des statut -->
                        <td><select name="StatutLocation" size="l"
                        <option> Rendu
                        <option> En cours
                        </select></td>
                    </tr>
                <?php endforeach ?>
              </form>
            </table>
            <a href="index.php?action=displaySellerOverview" class="btn btn-info">Retour à la vue d'ensemble</a>
            <a href="index.php?action=" class="btn btn-success">Enregistrer les modifications</a>
        </div>
    </header>
</article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>

    <div  class="table-responsive">
        <table class="table table-dark">
            <thead class="thead-dark">
              <tr>
                  <th>Code</th><th>Quantité</th><th>Prise</th><th>Retour</th><th>Statut</th>
              </tr>
            </thead>
            <?php foreach ($snowsResults as $snow):?>
              <tr>
                  <td><?= $result['code']; ?></td>
                  <td><?= $result['qty']; ?></td>
                  <td><?= $result['DateTake']; ?></td>
                  <td><?= $result['DateRetrun']; ?></td>
                  <td><?= $result['Statut']; ?></td>
              </tr>
            <?php endforeach ?>
        </table>
    </div>
