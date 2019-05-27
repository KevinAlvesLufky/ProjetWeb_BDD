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
        $newSnowLeasing = array('code' => $snowCodeToAdd, 'dateD' => Date("d-m-y"), 'nbD' => $howManyLeasingDays, 'qty' => $qtyOfSnowsToAdd);
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

    foreach ($currentCart as $key => &$item){
        if ($lineCode == $item["code"] && $nbDaysToChange == $item["nbD"]){


        }
    }

    //change data in cart
    for ($i =0;$i<count($currentCart);$i++){
        if ($i == $lineToChange){

            if (isDispo($currentCart[$i]["code"],$qtyToChange,$currentCart)) {
                $currentCart[$i]["qty"] = $qtyToChange;
                $currentCart[$i]["nbD"] = $nbDaysToChange;
            }else{
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

    if ($theSnow[0]["qtyAvailable"]>=$qtyOfSnowRequested){
        return true;
    }
    return false;
}

/**
 * This function is designed to manage the insert data cart in the DB
 * @return true or  false
 */
function dataInsert()
{
    $strSeparator = '\'';

    for($i=0; $i < count($_SESSION['cart']); $i++)
    {
        //set variables
        $snowsInsertResults = false;
        $id = $i+1; //Revoir pour les numéros des locations
        $date = $_SESSION['cart'][$i]['dateD'];
        $startDate = date('d-m-y', strtotime($date));
        $addDate = $_SESSION['cart'][$i]['nbD'];
        $timeStamp = strtotime($startDate);
        $endDate = date('d-m-y', strtotime('+'.$addDate.'days', $timeStamp ));
        $snowCode = $_SESSION['cart'][$i]['code'];

        //take informations snows
        $snowsDataQuery = 'SELECT id, qtyAvailable FROM snows WHERE snows.code =' . $strSeparator . $snowCode . $strSeparator;

        require_once 'model/dbConnector.php';
        $snowsData = executeQuerySelect($snowsDataQuery);

        //change the qantity snows
        $snowsData[$i]["qtyAvailable"] -= $_SESSION['cart'][$i]['qty'];
        $snowsQtyQuery = 'INSERT INTO snows (qtyAvailable ) VALUES ('.$snowsData[$i]["qtyAvailable"].') WHERE snows.code =' . $strSeparator . $snowCode . $strSeparator;

        require_once 'model/dbConnector.php';
        executeQueryInsert($snowsQtyQuery);

        //insert leasing informations
        $snowsInsertQuery = 'INSERT INTO leasings (id, idUsers, idSnows, startDate, endDate) VALUES ('.$id.','.$_SESSION["userId"][0]["id"].','.$snowsData[$i]["id"].','.$date.','.$endDate.' )';


        require_once 'model/dbConnector.php';
        //todo corriger l'erreur (return false)
        $queryResult = executeQuerySelect($snowsInsertQuery);

        if($queryResult)
        {
            $snowsInsertResults = $queryResult;
        }
        return $snowsInsertResults;
    }
}

/**
 * This function is designed to manage the
 * @return true or  false
 */
function getSnowsLeasing()
{
    $strSeparator = '\'';
    $idUser = $_SESSION["userId"][0]["id"];
    //TODO faire la requête sql pour aller chercher les informations dans la table leasing (attention multitable)
    $snowsLeasingQuery = 'SELECT snows.code, snows.brand, snows.model, snows.dailyPrice, snows.qtyAvailable FROM snows INNER JOIN leasings ON snows.id = leasings.idSnows INNER JOIN leasings ON users.id = leasings.idUsers WHERE leasing.idUsers ='. $strSeparator . $idUser . $strSeparator;

    require_once 'model/dbConnector.php';
    $queryResultLeasing = executeQuerySelect($snowsLeasingQuery);

    if($queryResultLeasing)
    {
        $snowsLeasingResults = $queryResultLeasing;
    }
    return $snowsLeasingResults;
}