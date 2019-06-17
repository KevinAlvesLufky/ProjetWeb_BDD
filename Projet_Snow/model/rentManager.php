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

function getStatutLeasing($idLeasing)
{
    $strSeparator = '\'';

    $statutLeasingQuery = 'SELECT leasings.statut FROM leasings WHERE leasings.id = '. $strSeparator . $idLeasing . $strSeparator;

    require_once 'model/dbConnector.php';
    $statutLeasing = executeQuerySelect($statutLeasingQuery);

    return $statutLeasing;
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
    $timeStamp2 = 0;

    for($i=0; $i < count($_SESSION['cart']); $i++)
    {
        //set variables
        $startDate = $_SESSION['cart'][$i]['dateD'];
        $addDate = $_SESSION['cart'][$i]['nbD'];
        $timeStamp = strtotime($startDate);
        $endDate = date("Y-m-d", strtotime('+'.$addDate.'days', $timeStamp ));
        $startDateToInsert = $strSeparator . $startDate . $strSeparator;
        $endDateToInsert = $strSeparator . $endDate . $strSeparator;
        $timeStamp1 = strtotime($endDate);
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

        $snowsLeasingsInsertQuery = 'INSERT INTO snows_leasings (idLeasings, idSnows, qtySelected, startDate, endDate, statut) VALUES ('.$idLeasing.','.$idSnow.','.$qtySelected.','.$startDateToInsert.','.$endDateToInsert.','.$statut.')';

        require_once 'model/dbConnector.php';
        executeQueryInsert($snowsLeasingsInsertQuery);

        if($timeStamp1 > $timeStamp2)
        {
            $laterTimeStamp = $timeStamp1;
        }
        else
        {
            $laterTimeStamp = $timeStamp2;
        }

        $timeStamp2 = strtotime($endDate);
    }
    $laterEndDate = date("Y-m-d", $laterTimeStamp);
    $laterEndDate = $strSeparator . $laterEndDate . $strSeparator;

    $leasingsInsertQuery = 'INSERT INTO leasings (idUsers, startDate, endDate, statut) VALUES ('.$idUser.','.$startDateToInsert.','.$laterEndDate.','.$statut.')';

    require_once 'model/dbConnector.php';
    executeQueryInsert($leasingsInsertQuery);

    return true;
}

function lineInLeasingInsert($leasingResults, $idLeasing)
{
    $idArticlesQuery = 'SELECT snows_leasings.id FROM snows_leasings ORDER BY snows_leasings.id';

    require_once 'model/dbConnector.php';
    $idArticlesResults = executeQuerySelect($idArticlesQuery);

    $idFirstArticleQuery = 'SELECT snows_leasings.id FROM snows_leasings WHERE snows_leasings.idLeasings =' . $idLeasing;

    require_once 'model/dbConnector.php';
    $idFirstArticlesResults = executeQuerySelect($idFirstArticleQuery);

    $y = $idFirstArticlesResults[0]['id'];
    $y -=1;

    for ($i = 0; $i < count($leasingResults); $i++)
    {
        $lineInLeasingInsertQuery = 'UPDATE snows_leasings SET snows_leasings.lineInLeasing = ' . $i . ' WHERE snows_leasings.id =' . $idArticlesResults[$y]['id'] . ' AND snows_leasings.idLeasings =' . $idLeasing;

        require_once 'model/dbConnector.php';
        executeQueryInsert($lineInLeasingInsertQuery);

        $y++;
    }

}

function getAllSnowLeasings()
{
    //take informations leasings
    $snowsLeasingsQuery = 'SELECT snows_leasings.idLeasings,  snows_leasings.statut FROM snows_leasings INNER JOIN leasings ON snows_leasings.idLeasings = leasings.id';

    require_once 'model/dbConnector.php';
    $snowsLeasings = executeQuerySelect($snowsLeasingsQuery);

    return $snowsLeasings;
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
 * This function is designed to get leasing's informations
 * @return $_SESSION["haveLeasing"] : array of informations leasing or false
 */
function getEndDateLeasing($idLeasing)
{
    $endDateLeasingResults = false;

    $strSeparator = '\'';

    //take informations leasings
    $endDateLeasingQuery = 'SELECT leasings.endDate FROM leasings WHERE leasings.id ='. $strSeparator . $idLeasing . $strSeparator;

    require_once 'model/dbConnector.php';
    $endDateLeasingResults = executeQuerySelect($endDateLeasingQuery);

    return $endDateLeasingResults;
}

/**
 * This function is designed to get leasings's informations
 * @return $_SESSION["haveLeasing"] : array of informations leasing or false
 */
function getAllLeasings()
{
    $leasingsResults = false;

    //take informations leasings
    $snowLeasingsQuery = 'SELECT leasings.id, users.userEmailAddress, leasings.startDate, leasings.endDate, leasings.statut FROM leasings INNER JOIN users ON leasings.idUsers = users.id';

    require_once 'model/dbConnector.php';
    $leasingsResults = executeQuerySelect($snowLeasingsQuery);

    return $leasingsResults;
}

function insertNewStatutLeasing($idLeasing, $statut)
{
    $strSeparator = '\'';

    $statutInsertQuery = 'UPDATE leasings SET leasings.statut = ' . $strSeparator . $statut . $strSeparator . '  WHERE leasings.id =' . $idLeasing;

    require_once 'model/dbConnector.php';
    executeQueryInsert($statutInsertQuery);
}

function insertNewStatutLeasings($i, $idLeasing, $statutToInsert)
{
    $strSeparator = '\'';

    $statutInsertQuery = 'UPDATE snows_leasings SET snows_leasings.statut = ' . $strSeparator . $statutToInsert . $strSeparator . ' WHERE snows_leasings.lineInLeasing =' . $i . ' AND snows_leasings.idLeasings =' . $idLeasing;

    require_once 'model/dbConnector.php';
    executeQueryInsert($statutInsertQuery);

}