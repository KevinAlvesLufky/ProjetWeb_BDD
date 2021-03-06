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
        <p>ID Location : <?= $leasingResults[0]['idLeasings']?>&emsp;&emsp;&emsp;&nbsp;&nbsp;&nbsp;Email : <?= $userEmailAddressLeasing[0]['userEmailAddress'] ?> </p>
        <p>Prise : <?= $leasingResults[0]['startDate']?>&emsp;&emsp;Retour : <?= $endDateLeasingResults[0]['endDate']?></p>
        <p>Statut : <?= $statutLeasing[0]['statut']?></p>

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
                    echo "<form class='form' method='POST' action='index.php?action=updateStatut&idLeasing=$idLeasingInUrl'>";
                    echo "<td><select name='statut$i' id='statut$i'>";
                    echo "<option value='" . $leasingResults[$i]['statut'] . "'>" . $leasingResults[$i]['statut'] . "</option>";
                    echo "<option value='$option2[$i]'>" . $option2[$i] . "</option>";
                    echo "</select></td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <a href='index.php?action=displayLeasing' class='btn btn-info'>Retour à la vue d'ensemble</a>
            <button type='submit' class='btn btn-success'>Enregistrer les modifications</button>
            </form>
        </div>
    </header>
</article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>
