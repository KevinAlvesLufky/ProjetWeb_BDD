<?php
/**
 * This file contain all functions about the rent
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

/**
 * This function is designed to get the last leasing's id
 * @return $lastId : the last leasing's id
 */
function getLastIdLeasing()
{
    $lastId = 0;

    $leasingsIdQuery = 'SELECT id FROM leasings';

    require_once 'model/dbConnector.php';
    $arrayId = executeQuerySelect($leasingsIdQuery);

    foreach($arrayId as $array)
    {
        $lastId = $array['id'];
    }

    $lastId +=1;

    return $lastId;
}

/**
 * This function is designed to get the leasings of the user
 * @return
 */
function getLeasingsUser()
{
    $strSeparator = '\'';
    $idUser = $_SESSION["userId"];

    $leasingsUserQuery = 'SELECT id FROM leasings WHERE leasings.idUsers ='. $strSeparator . $idUser . $strSeparator;

    require_once 'model/dbConnector.php';
    $leasingsUser = executeQuerySelect($leasingsUserQuery);

    return $leasingsUser;
}

/**
 * This function is designed to get the leasings of the user
 * @return
 */
function getLeasingUserEmailAddress($idLeasing)
{
    $strSeparator = '\'';

    $userEmailAddressQuery = 'SELECT users.userEmailAddress FROM leasings INNER JOIN users ON leasings.idUsers = users.id WHERE leasings.id = '. $strSeparator . $idLeasing . $strSeparator;

    require_once 'model/dbConnector.php';
    $userEmailAddressLeasing = executeQuerySelect($userEmailAddressQuery);

    return $userEmailAddressLeasing;
}

/**
 * This function is designed to manage the insert data cart in the DB
 * @return true
 */
function dataInsert()
{
    $strSeparator = '\'';
    $idLeasing = getLastIdLeasing();
    $snowsData[] = "";

    for($i=0; $i < count($_SESSION['cart']); $i++)
    {
        //set variables
        $startDate = $_SESSION['cart'][$i]['dateD'];
        $addDate = $_SESSION['cart'][$i]['nbD'];
        $timeStamp = strtotime($startDate);
        $endDate = date("Y-m-d", strtotime('+'.$addDate.'days', $timeStamp ));
        $startDate = $strSeparator . $startDate . $strSeparator;
        $endDate = $strSeparator . $endDate . $strSeparator;
        $snowCode = $_SESSION['cart'][$i]['code'];

        //take informations snows
        $snowsDataQuery = 'SELECT id, qtyAvailable FROM snows WHERE snows.code =' . $strSeparator . $snowCode . $strSeparator;

        require_once 'model/dbConnector.php';
        $snowsData[$i] = executeQuerySelect($snowsDataQuery);

        //change the qantity snows
        $snowsData[$i][0]["qtyAvailable"] -= $_SESSION['cart'][$i]['qty'];
        $snowsQtyQuery = 'UPDATE snows SET qtyAvailable = '.$snowsData[$i][0]["qtyAvailable"].' WHERE snows.code =' . $strSeparator . $snowCode . $strSeparator;

        require_once 'model/dbConnector.php';
        executeQueryInsert($snowsQtyQuery);

        //insert leasing informations
        $idUser = $_SESSION["userId"];
        $idSnow = $snowsData[$i][0]["id"];
        $idSnow = (int)$idSnow;
        $qtySelected = $_SESSION['cart'][$i]['qty'];
        $statut = '"En cours"';

        $snowsLeasingsInsertQuery = 'INSERT INTO snows_leasings (idLeasings, idSnows, qtySelected, startDate, endDate, statut) VALUES ('.$idLeasing.','.$idSnow.','.$qtySelected.','.$startDate.','.$endDate.','.$statut.')';

        require_once 'model/dbConnector.php';
        executeQueryInsert($snowsLeasingsInsertQuery);
    }

    $leasingsInsertQuery = 'INSERT INTO leasings (idUsers, startDate, endDate, statut) VALUES ('.$idUser.','.$startDate.','.$endDate.','.$statut.')';

    require_once 'model/dbConnector.php';
    executeQueryInsert($leasingsInsertQuery);

    return true;
}

/**
 * This function is designed to get leasings's informations
 * @return $_SESSION["haveLeasing"] : array of informations leasing or false
 */
function getSnowLeasingsUser()
{
    $_SESSION["haveLeasing"] = false;

    $strSeparator = '\'';
    $idLeasingsUser = getLeasingsUser();
    $_SESSION["haveLeasing"] = array();

    //take informations leasings
    for($i=0; $i < count($idLeasingsUser); $i++)
    {
        $idLeasingUser = $idLeasingsUser[$i]['id'];
        $snowsLeasingQuery = 'SELECT snows_leasings.idLeasings, snows.code, snows.brand, snows.model, snows.dailyPrice, snows_leasings.qtySelected, snows_leasings.startDate FROM snows_leasings INNER JOIN snows ON snows_leasings.idSnows = snows.id WHERE snows_leasings.idLeasings =' . $strSeparator . $idLeasingUser . $strSeparator;

        require_once 'model/dbConnector.php';
        $snowLeasingsUser = executeQuerySelect($snowsLeasingQuery);

        array_push($_SESSION["haveLeasing"], $snowLeasingsUser);
    }
    return $_SESSION["haveLeasing"];
}

/**
 * This function is designed to get leasing's informations
 * @return $_SESSION["haveLeasing"] : array of informations leasing or false
 */
function getASnowLeasing($idLeasing)
{
    $leasingResults = false;

    $strSeparator = '\'';

    //take informations leasings
    $snowLeasingQuery = 'SELECT snows_leasings.idLeasings, snows.code, snows_leasings.qtySelected, snows_leasings.startDate, snows_leasings.endDate, snows_leasings.statut FROM snows_leasings INNER JOIN snows ON snows_leasings.idSnows = snows.id INNER JOIN leasings ON snows_leasings.idLeasings = leasings.id WHERE snows_leasings.idLeasings ='. $strSeparator . $idLeasing . $strSeparator;

    require_once 'model/dbConnector.php';
    $leasingResults = executeQuerySelect($snowLeasingQuery);

    return $leasingResults;
}

/**
 * This function is designed to get leasings's informations
 * @return $_SESSION["haveLeasing"] : array of informations leasing or false
 */
function getAllSnowLeasings()
{
    $leasingsResults = false;

    //take informations leasings
    $snowLeasingsQuery = 'SELECT leasings.id, users.userEmailAddress, leasings.startDate, leasings.endDate, leasings.statut FROM leasings INNER JOIN users ON leasings.idUsers = users.id';

    require_once 'model/dbConnector.php';
    $leasingsResults = executeQuerySelect($snowLeasingsQuery);

    return $leasingsResults;
}