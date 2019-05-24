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
                    <th>N° Location</th><th>Code</th><th>Marque</th><th>Prix</th><th>Quantité</th><th>Date début de Location</th>
                </tr>
                <?php
                // Displays user's leasing content
                for ($i=0; $i < count($_SESSION['leasing']); $i++)
                {
                        echo "<tr>";
                        echo "<td>".$_SESSION['leasing'][$i]['NLeasing']."</td>";
                }
                ?>

            </table>
    </article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>
