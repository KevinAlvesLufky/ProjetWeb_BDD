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

    $leasingsResults = getAllLeasings();

    $_GET['action'] = "displayLeasing";

    //display view depending of the tyoe of user
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
 * @param $idLeasing : contains the id of the leasing
 */
function displayManageLeasing($idLeasing)
{
    require_once"model/rentManager.php";

    //set variables
    $leasingsResults = getAllLeasings();
    $snowsLeasings = getAllSnowLeasings();

    //check status of the leasings for modify the status of the (general) leasing
    for($i=0;$i<count($leasingsResults); $i++)
    {
        for($y=0;$y<count($snowsLeasings); $y++)
        {
            if($leasingsResults[$i]['id'] == $snowsLeasings[$y]['idLeasings'])
            {
                $leasingId = $leasingsResults[$i]['id'];
                $statut = getASnowLeasing($leasingId);

                if($statut[0]['statut'] == $snowsLeasings[$y]['statut'])
                {

                    if($statut[0]['statut'] == "En cours")
                    {
                        $statut = "En cours";
                        insertNewStatutLeasing($leasingId, $statut);
                    }
                    else
                    {
                        $statut = "Rendu";
                        insertNewStatutLeasing($leasingId, $statut);
                    }
                }
                else
                {
                    $statut = "Rendu partiel";
                    insertNewStatutLeasing($leasingId, $statut);
                    break;
                }
            }
        }
    }

    //set variables
    $leasingResults = getASnowLeasing($idLeasing);
    lineInLeasingInsert($leasingResults, $idLeasing);
    $endDateLeasingResults =  getEndDateLeasing($idLeasing);
    $userEmailAddressLeasing = getLeasingUserEmailAddress($idLeasing);
    $idLeasingInUrl = $leasingResults[0]['idLeasings'];
    $statutLeasing = getStatutLeasing($idLeasing);

    //get the option 2 depending of the option 1
    for ($i = 0; $i < count($leasingResults); $i++)
    {
        if ($leasingResults[$i]['statut'] == "En cours")
        {
            $option2[$i] = "Rendu";
        }
        else
        {
            $option2[$i] = "En cours";
        }
    }

    $_GET['action'] = "displayManageLeasing";
    require_once"view/sellerManageLeasing.php";
}

/**
 * This function is designed to manage the confirmation of the leasing and insert leasing's informations in the BD
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

/**
 * This function is designed to update the status of the articles's leasing
 * @param $idLeasing : contains the id of the leasing
 * @param $statutUpdateRequest : contains the fields of the form to update the status of an article
 */
function updateStatut($idLeasing, $statutUpdateRequest)
{
    require_once "model/rentManager.php";
    $leasingResults = getASnowLeasing($idLeasing);

    if(isset($idLeasing) && isset($statutUpdateRequest))
    {
        //change the status of the article
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
