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

    $leasingsIdQuery = 'SELECT idLeasings FROM leasings';

    require_once 'model/dbConnector.php';
    $arrayId = executeQuerySelect($leasingsIdQuery);

    foreach($arrayId as $array)
    {
        $lastId = $array['idLeasings'];
    }

    $lastId +=1;

    return $lastId;
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
        $status = "En cours";

        $snowsInsertQuery = 'INSERT INTO leasings (idLeasings, idUsers, idSnows, qtySelected, startDate, endDate, status) VALUES ('.$idLeasing.','.$idUser.','.$idSnow.','.$qtySelected.','.$startDate.','.$endDate.','.$status.')';

        require_once 'model/dbConnector.php';
        executeQueryInsert($snowsInsertQuery);
    }
    return true;
}

/**
 * This function is designed to get leasings's informations
 * @return $snowsLeasingResults : array of informations leasing or false
 */
function getSnowsLeasing()
{
    $snowsLeasingResults = false;

    $strSeparator = '\'';
    $idUser = $_SESSION["userId"];

    //take informations leasings
    $snowsLeasingQuery = 'SELECT idLeasings, snows.code, snows.brand, snows.model, snows.dailyPrice, qtySelected, startDate FROM leasings INNER JOIN snows ON leasings.idSnows = snows.id WHERE leasings.idUsers ='. $strSeparator . $idUser . $strSeparator;

    require_once 'model/dbConnector.php';
    $snowsLeasingResults = executeQuerySelect($snowsLeasingQuery);

    return $snowsLeasingResults;
}

/**
 * This function is designed to check if the user have leasings
 * @return $snowsLeasingResults : array of informations leasing or false
 */
function isLeasingOk()
{
    $strSeparator = '\'';
    $idUser = $_SESSION["userId"][0]["id"];

    //take informations leasings
    $snowsLeasingQuery = 'SELECT idLeasings, snows.code, snows.brand, snows.model, snows.dailyPrice, qtySelected, startDate FROM leasings INNER JOIN snows ON leasings.idSnows = snows.id WHERE leasings.idUsers ='. $strSeparator . $idUser . $strSeparator;

    require_once 'model/dbConnector.php';
    $snowsLeasingResults = executeQuerySelect($snowsLeasingQuery);

    return $snowsLeasingResults;
}