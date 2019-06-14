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

   // $_SESSION["haveLeasing"] = getSnowLeasingsUser(); //take data leasings
    $leasingsResults = getAllSnowLeasings();

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
 * This function is designed to display the manage leasing page
 */
function displayManageLeasing($idLeasing)
{
    $_GET['action'] = "displayManageLeasing";
    require_once"model/rentManager.php";
    $leasingResults = getASnowLeasing($idLeasing);

    if($leasingResults[0]['statut'] == "En cours")
    {
        $option2 = "Rendu";
    }
    else
    {
        $option2 = "En cours";
    }

    require_once"view/sellerManageLeasing.php";

}

/**
 * This function is designed to manage the confirmation of the leasing
 */
function confirmLeasing()
{
    require_once"model/rentManager.php";
    if(dataInsert())
    {
        $_SESSION["haveLeasing"] = getSnowLeasingsUser();
        unset ($_SESSION['cart']); //delete cart
    }
    require_once "view/UserLeasing.php";
}

function updateStatut()
{

}

//endregion
