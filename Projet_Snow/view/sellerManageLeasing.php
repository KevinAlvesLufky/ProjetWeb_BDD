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
        <p>ID Location : <?= $leasingResults[0]['idLeasings']?>       Email : <?= $leasingResults[0]['userEmailAddress'] ?> </p>
        <p>Prise : <?= $leasingResults[0]['startDate']?>              Retour : <?= $leasingResults[0]['endDate']?></p>
        <p>Statut : <?= $leasingResults[0]['statut']?></p>

        <div class="table-responsive">
            <table class="table textcolor">
              <form>
                <tr>
                    <th>Code</th><th>Quantité</th><th>Prise</th><th>Retour</th><th>Statut</th>
                </tr>
                <?php
                foreach ($leasingResults as $result) : ?>
                    <tr>
                        <td><?= $result['code']; ?></td>
                        <td><?= $result['qtySelected']; ?></td>
                        <td><?= $result['startDate']; ?></td>
                        <td><?= $result['endDate']; ?></td>
                        <!-- Mettre checkbox pour choisir, tester ensuite l'état des statut -->
                        <td><select name="StatutLocation" size="l"
                            <option> Rendu </option>
                            <option> En cours </option>
                        </select></td>
                    </tr>
                <?php endforeach ?>
              </form>
            </table>
            <a href="index.php?action=displayLeasing" class="btn btn-info">Retour à la vue d'ensemble</a>
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
            <?php foreach ($leasingResults as $snow):?>
              <tr>
                  <td><?= $result['code']; ?></td>
                  <td><?= $result['qtySelected']; ?></td>
                  <td><?= $result['startDate']; ?></td>
                  <td><?= $result['endDate']; ?></td>
                  <td><?= $result['statut']; ?></td>
              </tr>
            <?php endforeach ?>
        </table>
    </div>
