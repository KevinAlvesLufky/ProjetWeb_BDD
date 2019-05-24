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

/** TODO
 * Fonction pour enlever les snows lorsque loués
 * Récupère la QTY dans le cart pour chaque snows
 * Récupère les data de disponibilité des snows en fonction du code
 * Enlève la QTY du snow (DB) avec la qty du cart pour chaque snows
 * Prépare la requête SQL pour insertion de donnée dans la DB
 * Envoie la requête vers la DB
 */

/*TODO

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
 * @return false
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

function dataInsert()
{
    $strSeparator = '\'';

    for($i=0; $i < count($_SESSION['cart']); $i++)
    {
        $snowCode = $_SESSION['cart'][$i]['code'];
        $startDate = $_SESSION['cart'][$i]['dateD'];
        $addDate = $_SESSION['cart'][$i]['nbD'];
       // $endDate = date(‘d-m-Y’, strtotime(‘+15 days’));

        $snowIdQuerry = 'SELECT id FROM snows WHERE snows.code = ' . $strSeparator . $snowCode . $strSeparator;

        require_once 'model/dbConnector.php';
        $snowId = executeQuerySelect($snowIdQuerry);

        $snowsInsertQuery = 'INSERT INTO leasings (id, idUsers, idSnows, startDate, endDate) VALUES ('.$i.'.,.'.$_SESSION["userId"].'.,.'.$snowId.'.,.'.$startDate.'.,.'.$endDate.'.)';

        require_once 'model/dbConnector.php';
        $snowsInsertResults = executeQuerySelect($snowsInsertQuery);

        return $snowsInsertResults;
    }
}

function getSnowsCart()
{
    $snowsCartQuery =

    require_once 'model/dbConnector.php';
    $snowsCartResults = executeQuerySelect($snowsCartQuery);

    return $snowsCartResults;
}


