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
    // If user's leasing is valid, show a text Message

    ?>
    <article>
            <table class="table">
                <tr>
                    <th>N° Location</th><th>Code</th><th>Marque</th><th>Modèle</th><th>Prix</th><th>Quantité</th><th>Date début de Location</th>
                </tr>
                <?php
                // Displays user's leasing content
                for ($i=0; $i < count($_SESSION['leasing']); $i++)
                {
                        echo "<tr>";
                            echo "<td>".$id."</td>";
                            echo "<td>".$snowsLeasingResults[$i]['code']."</td>";
                            echo "<td>".$snowsLeasingResults[$i]['brand']."</td>";
                            echo "<td>".$snowsLeasingResults[$i]['model']."</td>";
                            echo "<td>".$snowsLeasingResults[$i]['dailyPrice']."</td>";
                            echo "<td>".$snowsLeasingResults[$i]['qtyAvailable']."</td>";
                            echo "<td>".$date."</td>";
                         echo "</tr>";
                }
                ?>

            </table>
    </article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>
