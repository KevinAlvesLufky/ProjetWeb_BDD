<?php
/**
 * This file permit to display user's leasing
 * Created by PhpStorm.
 * User: Damien Chervet
 * Date: 20.05.2017
 * Time: 09:10
 */

$title = 'Vos locations';

ob_start();
?>
    <h2>Vos locations</h2>
    <?php
    //if the user doesn't have leasings
    if( $snowsLeasingResults == false)
    {
        echo "<p>"."Vous n'avez aucune location pour le moment"."</p>";
    }
    else //if the user have leasings
    {
    ?>
        <article>
            <table class="table">
                <tr>
                    <th>N° Location</th><th>Code</th><th>Marque</th><th>Modèle</th><th>Prix</th><th>Quantité</th><th>Date début de Location</th>
                </tr>
                <?php
                    // Displays user's leasing content
                    for ($i = 0; $i < count($snowsLeasingResults) ; $i++)
                    {
                        echo "<tr>";
                        echo "<td>" . $snowsLeasingResults[$i]['idLeasings'] . "</td>";
                        echo "<td>" . $snowsLeasingResults[$i]['code'] . "</td>";
                        echo "<td>" . $snowsLeasingResults[$i]['brand'] . "</td>";
                        echo "<td>" . $snowsLeasingResults[$i]['model'] . "</td>";
                        echo "<td>" . $snowsLeasingResults[$i]['dailyPrice'] . "</td>";
                        echo "<td>" . $snowsLeasingResults[$i]['qtySelected'] . "</td>";
                        echo "<td>" . $snowsLeasingResults[$i]['startDate'] . "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
        </article>
        <?php
    }

$content = ob_get_clean();
require 'gabarit.php';
?>
