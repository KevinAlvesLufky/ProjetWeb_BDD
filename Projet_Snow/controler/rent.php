<?php
/**
 * This file contain all functions about the rent
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

//region Rent Management
/**
 * This function is designed to manage the confirmation of the leasing
 */
function confirmLeasing()
{
    require_once"model/cartManager.php";
    if(dataInsert() && getSnowsLeasing())
    {
        $Leasing = 1;
        unset ($_SESSION['cart']);
    }
    $date = date("d-m-Y");

    $_GET['action'] = "displayLeasing";
    require "view/UserLeasing.php";
}

//endregion
