<?php
/**
 * This file contains all functions to manage the cart
 * Created by PhpStorm.
 * User: Kevin Alves
 * Date: 06.05.2017
 * Time: 09:10
 */

/**
 * This function is designed to insert data in cart
 * @param $currentCartArray : contains array of the cart
 * @param $snowCodeToAdd : contains the code of the snow
 * @param $qtyOfSnowsToAdd : contains the quantity of the snows
 * @param $howManyLeasingDays : contains the days
 * @return $cartUpdated : contains array of the cart update
 */
function updateCart($currentCartArray, $snowCodeToAdd, $qtyOfSnowsToAdd, $howManyLeasingDays){

    //set variables
    $cartUpdated = array();
    $alreadyExist = false;

    //take data cart
    if($currentCartArray != null){
        $cartUpdated = $currentCartArray;
    }
    //foreach the cart to see if the cart to update exist already, and need just to add the quantity to the good cart
    foreach ($cartUpdated as $key => &$cart){

        if ($snowCodeToAdd==$cart["code"]){
            if ($howManyLeasingDays==$cart["nbD"]) {
                $tempQty = $cart["qty"];
                $cart["qty"] = $tempQty + $qtyOfSnowsToAdd;
                $alreadyExist = true;
            }
        }
    }
    if (!$alreadyExist) {

        //put data in the array
        $newSnowLeasing = array('code' => $snowCodeToAdd, 'dateD' => Date("Y-m-d"), 'nbD' => $howManyLeasingDays, 'qty' => $qtyOfSnowsToAdd);
        array_push($cartUpdated, $newSnowLeasing);
    }
    if (!isDispo($snowCodeToAdd,$qtyOfSnowsToAdd,$cartUpdated)){
        return false;
    }
    return $cartUpdated;
}

/**
 * This function is designed to update data in cart
 * @param $lineToChange : contains the line of the snow
 * @param $qtyToChange : contains the quantity of snows
 * @param $nbDaysToChange : contains the days
 * @param $currentCart : contains the array of the cart
 * @return $currentCart : contains array of the cart update
 */
function updateInCart($lineToChange,$qtyToChange,$nbDaysToChange,$currentCart){

    $lineCode = $currentCart[$lineToChange]["code"];

    //change data in cart
    for ($i =0;$i<count($currentCart);$i++){
        if ($i == $lineCode){

            if (isDispo($currentCart[$i]["code"],$qtyToChange,$currentCart)) {
                $currentCart[$i]["qty"] = $qtyToChange;
                $currentCart[$i]["nbD"] = $nbDaysToChange;
            }else
            {
                return false;
            }
        }
    }
    return $currentCart;
}

/**
 * This function is designed to manage the disposable of the quantity
 * @param $snowCode : contains the code of the snow
 * @param $qtyOfSnowRequested: contains the quantity of snows
 * @param $cart : contains the array of the cart
 * @return true or false
 */
function isDispo($snowCode,$qtyOfSnowRequested,$cart){

    require_once "model/snowsManager.php";
    $theSnow = getASnow($snowCode);
    if (isset($cart))
    {
        //check the quantity
        foreach ($cart as $item)
        {
            if ($snowCode == $item["code"])
            {
                if (isset($tempQty))
                {
                    $tempQty = $tempQty + $item["qty"];
                }
                else
                {
                    $tempQty = $item["qty"];
                }
            }
        }
    }

    if (isset($tempQty)){
        $qtyOfSnowRequested = $tempQty;
    }

    if ($theSnow[0]["qtyAvailable"]>=$qtyOfSnowRequested)
    {
        return true;
    }
    return false;
}

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

        $snowsInsertQuery = 'INSERT INTO leasings (idLeasings, idUsers, idSnows, qtySelected, startDate, endDate) VALUES ('.$idLeasing.','.$idUser.','.$idSnow.','.$qtySelected.','.$startDate.','.$endDate.' )';

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