<?php
/**
 * This file contains all functions to manage the cart
 * Created by PhpStorm.
 * User: Kevin Alves
 * Date: 06.05.2017
 * Time: 09:10
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
        //set variables
        $snowsInsertResults = false;
        $id = $i+1;
        $date = $_SESSION['cart'][$i]['dateD'];
        $startDate = date('d-m-y', strtotime($date));
        $addDate = $_SESSION['cart'][$i]['nbD'];
        $timeStamp = strtotime($startDate);
        $endDate = date('d-m-y', strtotime('+'.$addDate.'days', $timeStamp ));
        $snowCode = $_SESSION['cart'][$i]['code'];

        //take informations snows
        $snowsDataQuery = 'SELECT id AND qtyAvailable FROM snows WHERE snows.code =' . $strSeparator . $snowCode . $strSeparator;

        require_once 'model/dbConnector.php';
        $snowsData = executeQuerySelect($snowsDataQuery);

        //change the qantity snows
        $snowsQtyQuery = 'INSERT INTO snows (qtyAvailable ) VALUES ('.$snowsData[$i]["qtyAvailable"].') snows.code =' . $strSeparator . $snowCode . $strSeparator;

        require_once 'model/dbConnector.php';
        $queryQty = executeQueryInsert($snowsQtyQuery);

        //insert leasing informations
        $snowsInsertQuery = 'INSERT INTO leasings (id, idUsers, idSnows, startDate, endDate) VALUES ('.$id.','.$_SESSION["userId"][$i]["id"].','.$snowsData[$i]["id"].','.$date.','.$endDate.' )';

        require_once 'model/dbConnector.php';
        $queryResult = executeQueryInsert($snowsInsertQuery);

        if($queryResult)
        {
            $snowsInsertResults = $queryResult;
        }
        return $snowsInsertResults;
    }
}

function getSnowsCart()
{
    //TODO faire la requête sql pour aller chercher les informations dans la table leasing (attention multitable)
    $snowsCartQuery =

    require_once 'model/dbConnector.php';
    $snowsCartResults = executeQuerySelect($snowsCartQuery);

    return $snowsCartResults;
}