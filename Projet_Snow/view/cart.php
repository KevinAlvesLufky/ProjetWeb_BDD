<?php
/**
 * This file permit to display the cart
 * Created by PhpStorm.
 * User: Damien Chervet
 * Date: 06.05.2017
 * Time: 09:10
 */

$title = 'Rent A Snow - Demande de location';

ob_start();
?>
    <h2>Votre panier</h2>
    <?php
    //if the user doesn't have leasings
    if(!isset($_SESSION['cart']))
    {
    ?>
         <article>
            <table class="table">
                <tr>
                    <th>Code</th>
                    <th>Date</th>
                    <th>Quantité</th>
                    <th>Nombre de jours</th>
                    <th>Retirer</th>
                </tr>
            </table>
            <a href="index.php?action=displaySnows" class="btn btn-info">Louer encore</a>
            <a href="index.php?action=emptyCart" class="btn btn-cancel">Vider le panier</a>
        </article>
    <?php
    }
    else //if the user have leasings
    {
    ?>
        <article>
            <!-- If warning is active,  show a redbox with an error message.  class in custom.css-->
            <?php if (isset($warning)): ?>
                <div class="isa_error">
                    <i class="fa fa-times-circle"></i>
                    <p> <?= $warning; ?> </p>
                </div>
            <?php endif; ?>
            <table class="table">
                <tr>
                    <th>Code</th>
                    <th>Date</th>
                    <th>Quantité</th>
                    <th>Nombre de jours</th>
                    <th>Retirer</th>
                </tr>
                
                <?php

                // Displays cart session's content
                for ($i = 0; $i < count($_SESSION['cart']); $i++) {
                    echo "<tr>";
                    echo "<td>" . $_SESSION['cart'][$i]['code'] . "</td>";
                    echo "<td>" . $_SESSION['cart'][$i]['dateD'] . "</td>";

                    echo "<form method='POST' action='index.php?action=updateCartItem&line=" . $i . "'>";
                    echo "<td><input type='number' name='uQty' value='" . $_SESSION['cart'][$i]['qty'] . "'></td>";
                    echo "<td><input type='number' name='uNbD' value='" . $_SESSION['cart'][$i]['nbD'] . "'></td>";

                    echo "<td><a href='index.php?action=deleteCartRequest&line=" . $i . "'><img src='view/content/images/delete2.png'></a>";
                    echo "<input type='submit' src='view/content/images/edit2.png'></td>";
                    echo "</form></tr>";
                }
                ?>

            </table>
            <a href="index.php?action=displaySnows" class="btn btn-info">Louer encore</a>
            <a href="index.php?action=emptyCart" class="btn btn-cancel">Vider le panier</a>
            <a href="index.php?action=confirmLeasing" class="btn btn-success">Finaliser ma location</a>
        </article>
        <?php
    }
$content = ob_get_clean();
require 'gabarit.php';
?>
