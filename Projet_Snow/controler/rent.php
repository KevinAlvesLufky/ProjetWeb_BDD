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
 * This function is designed to display the leasing page
 */
function displayLeasing()
{
    $_GET['action'] = "displayLeasing";
    require_once"model/rentManager.php";

    $GLOBALS['haveLeasing'] = getSnowsLeasing(); //take data leasings

    if (isset($_SESSION['userType']))
    {
        switch ($_SESSION['userType'])
        {
            case 1://this is a customer
                require "view/UserLeasing.php";
                break;
            case 2://this a seller
                require "view/sellerOverview.php";
                break;
            default:
                require "view/UserLeasing.php";
                break;
        }
    }
    else
    {
        require "view/UserLeasing.php";
    }
}
/**
 * This function is designed to manage the confirmation of the leasing
 */
function confirmLeasing()
{
    require_once"model/rentManager.php";
    if(dataInsert())
    {
        $GLOBALS['haveLeasing'] = getSnowsLeasing();
        unset ($_SESSION['cart']); //delete cart
    }
    require_once "view/UserLeasing.php";
}

//endregion
