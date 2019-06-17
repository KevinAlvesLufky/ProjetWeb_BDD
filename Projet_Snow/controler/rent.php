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
    require_once"model/rentManager.php";

    $_SESSION["haveLeasing"] = getSnowLeasingsUser();
    $leasingsResults = getAllLeasings();
    $snowsLeasings = getAllSnowLeasings();

    for($i=0;$i<count($leasingsResults); $i++)
    {
        for($y=0;$y<count($snowsLeasings); $y++)
        {
            if($leasingsResults[$i]['id'] == $snowsLeasings[$y]['idLeasings'])
            {
                $idLeasing = $leasingsResults[$i]['id'];

                if($snowsLeasings[0]['statut'] == $snowsLeasings[$y]['statut'])
                {
                    if($snowsLeasings[0]['statut'] == "En cours")
                    {
                        $statut = "En cours";
                        insertNewStatutLeasing($idLeasing, $statut);
                    }
                    else
                    {
                        $statut = "Rendu";
                        insertNewStatutLeasing($idLeasing, $statut);
                    }
                }
                else
                {
                    $statut = "Rendu partiel";
                    insertNewStatutLeasing($idLeasing, $statut);
                    break;
                }
            }
        }
    }

    $_GET['action'] = "displayLeasing";

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
    require_once"model/rentManager.php";
    $leasingResults = getASnowLeasing($idLeasing);
    lineInLeasingInsert($leasingResults, $idLeasing);
    $endDateLeasingResults =  getEndDateLeasing($idLeasing);
    $userEmailAddressLeasing = getLeasingUserEmailAddress($idLeasing);
    $idLeasingInUrl = $leasingResults[0]['idLeasings'];
    $statutLeasing = getStatutLeasing($idLeasing);

    for ($i = 0; $i < count($leasingResults); $i++)
    {
        if ($leasingResults[$i]['statut'] == "En cours")
        {
            $option2 = "Rendu";
        }
        else
        {
            $option2 = "En cours";
        }
    }

    $_GET['action'] = "displayManageLeasing";
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

function updateStatut($idLeasing, $statutUpdateRequest)
{
    require_once "model/rentManager.php";
    $leasingResults = getASnowLeasing($idLeasing);

    if(isset($idLeasing) && isset($statutUpdateRequest))
    {
        for ($i = 0; $i < count($leasingResults); $i++)
        {
            $statutToInsert = $statutUpdateRequest["statut$i"];
            insertNewStatutLeasings($i, $idLeasing, $statutToInsert);
        }
    }
    $_GET['action'] = "displayManageLeasing";
    displayManageLeasing($idLeasing);
}

//endregion
