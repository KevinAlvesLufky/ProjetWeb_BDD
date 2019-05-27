<?php
/**
 * This file contain all functions about the snows
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

//region snows management
/**
 * This function is designed to display Snows
 * There are two different view available.
 * One for the seller, an other one for the customer.
 */
function displaySnows()
{
    if (isset($_POST['resetCart']))
    {
        unset($_SESSION['cart']);
    }

    require_once "model/snowsManager.php";
    $snowsResults = getSnows();

    $_GET['action'] = "displaySnows";
    if (isset($_SESSION['userType']))
    {
        switch ($_SESSION['userType'])
        {
            case 1://this is a customer
                require "view/snows.php";
                break;
            case 2://this a seller
                require "view/snowsSeller.php";
                break;
            default:
                require "view/snows.php";
                break;
        }
    }
    else
    {
        require "view/snows.php";
    }
}

/**
 * This function is designed to get only one snow results (for aSnow view)
 * @param $snowCode : contains the code of the snow
 */
function displayASnow($snowCode)
{
    if (isset($registerRequest['inputUserEmailAddress']))
    {
        //TODO
    }
    require_once "model/snowsManager.php";
    $snowsResults= getASnow($snowCode);
    require "view/aSnow.php";
}
//endregion