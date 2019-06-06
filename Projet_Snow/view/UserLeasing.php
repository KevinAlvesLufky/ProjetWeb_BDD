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
    if( $GLOBALS['haveLeasing'] == false)
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
                    for ($i = 0; $i < count($GLOBALS['haveLeasing']) ; $i++)
                    {
                        echo "<tr>";
                        echo "<td>" . $GLOBALS['haveLeasing'][$i]['idLeasings'] . "</td>";
                        echo "<td>" . $GLOBALS['haveLeasing'][$i]['code'] . "</td>";
                        echo "<td>" . $GLOBALS['haveLeasing'][$i]['brand'] . "</td>";
                        echo "<td>" . $GLOBALS['haveLeasing'][$i]['model'] . "</td>";
                        echo "<td>" . $GLOBALS['haveLeasing'][$i]['dailyPrice'] . "</td>";
                        echo "<td>" . $GLOBALS['haveLeasing'][$i]['qtySelected'] . "</td>";
                        echo "<td>" . $GLOBALS['haveLeasing'][$i]['startDate'] . "</td>";
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
