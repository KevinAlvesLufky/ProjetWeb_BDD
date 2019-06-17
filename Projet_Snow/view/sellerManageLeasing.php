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
        <pre>
        <p>ID Location : <?= $leasingResults[0]['idLeasings']?>       Email : <?= $userEmailAddressLeasing[0]['userEmailAddress'] ?> </p>
        <p>Prise : <?= $leasingResults[0]['startDate']?>              Retour : <?= $endDateLeasingResults[0]['endDate']?></p>
        <p>Statut : <?= $statutLeasing[0]['statut']?></p>
      </pre>
        <div class="table-responsive">
            <table class="table textcolor">
                <tr>
                    <th>Code</th><th>Quantité</th><th>Prise</th><th>Retour</th><th>Statut</th>
                </tr>
                <?php
                for ($i = 0; $i < count($leasingResults); $i++)
                {
                    echo "<tr>";
                    echo "<td>" . $leasingResults[$i]['code'] . "</td>";
                    echo "<td>" . $leasingResults[$i]['qtySelected'] . "</td>";
                    echo "<td>" . $leasingResults[$i]['startDate'] . "</td>";
                    echo "<td>" . $leasingResults[$i]['endDate'] . "</td>";
                    echo "<form class='form' method='POST' action='index.php?action=updateStatut&line=$i&idLeasing=$idLeasingInUrl'>";
                    echo "<td><select name='statut' id='statut'>";
                    echo "<option value='" . $leasingResults[$i]['statut'] . "'>" . $leasingResults[$i]['statut'] . "</option>";
                    echo "<option value='$option2'>" . $option2 . "</option>";
                    echo "</select></td>";
                    echo "</tr>";

                    if ($i == count($leasingResults))
                    {
                        echo "<a href='index.php?action=displayLeasing' class='btn btn-info'>Retour à la vue d'ensemble</a>";
                        echo "<button type='submit' class='btn btn-success'>Enregistrer les modifications</button>";

                    }
                }
                 ?>
            </table>
            </form>
        </div>
    </header>
</article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>
