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
 * This function is designed to display the user's leasing page
 */
function displayLeasing()
{
    $_GET['action'] = "displayLeasing";
    require_once"model/rentManager.php";
    $snowsLeasingResults = getSnowsLeasing(); //take data leasings
    require "view/UserLeasing.php";
}
/**
 * This function is designed to manage the confirmation of the leasing
 */
function confirmLeasing()
{
    require_once"model/rentManager.php";
    if(dataInsert())
    {
        $snowsLeasingResults = getSnowsLeasing(); //take data leasings
        unset ($_SESSION['cart']); //delete cart
    }
    require_once "view/UserLeasing.php";
}

//endregion
