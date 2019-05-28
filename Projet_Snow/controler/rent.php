<?php
/**
 * This file contain all functions about the rent
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

//region Rent Management
function displayLeasing()
{
    $_GET['action'] = "displayLeasing";
    require_once"model/cartManager.php";
    $snowsLeasingResults = getSnowsLeasing();
    require "view/UserLeasing.php";
}
/**
 * This function is designed to manage the confirmation of the leasing
 */
function confirmLeasing()
{
    require_once"model/cartManager.php";
    if(dataInsert())
    {
        $snowsLeasingResults = getSnowsLeasing();
        unset ($_SESSION['cart']); //delete cart
        $hasLeasing = true;
    }
    require_once "view/UserLeasing.php";
}

//endregion
