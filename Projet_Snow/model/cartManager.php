<?php
/**
 * This file contains all functions to manage the cart
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

//region cart management
/**
 * This function is designed to insert data in cart
 * @param $currentCartArray : contains array of the cart
 * @param $snowCodeToAdd : contains the code of the snow
 * @param $qtyOfSnowsToAdd : contains the quantity of the snows
 * @param $howManyLeasingDays : contains the days
 * @throws : return error of quantity or date
 * @return $cartUpdated : contains array of the cart update
 */
function updateCart($currentCartArray, $snowCodeToAdd, $qtyOfSnowsToAdd, $howManyLeasingDays)
{

    //set variables
    $cartUpdated = array();
    $alreadyExist = false;
    if($qtyOfSnowsToAdd > 0 && $howManyLeasingDays > 0) //check if there are negative numbers
    {
        //take data cart
        if ($currentCartArray != null)
        {
            $cartUpdated = $currentCartArray;
        }
        //foreach the cart to see if the cart to update exist already, and need just to add the quantity to the good cart
        foreach ($cartUpdated as $key => &$cart)
        {
            if ($snowCodeToAdd == $cart["code"])
            {
                //if the date is the same, assembles the quantity
                if ($howManyLeasingDays == $cart["nbD"])
                {
                    $tempQty = $cart["qty"];
                    $cart["qty"] = $tempQty + $qtyOfSnowsToAdd;
                    $qtyOfSnowsToAdd = $cart["qty"];
                    $alreadyExist = true;
                }
            }
        }

        //if the informations isn't correct, throw an error
        if (!isDispo($snowCodeToAdd, $qtyOfSnowsToAdd, $cartUpdated))
        {
            throw new Exception('Valeurs trop élevées ou inférieures à 1, vérifiez la disponibilité du stock');
            return false;
        }

        if (!$alreadyExist)
        {
            //put data in the array
            $newSnowLeasing = array('code' => $snowCodeToAdd, 'dateD' => Date("Y-m-d"), 'nbD' => $howManyLeasingDays, 'qty' => $qtyOfSnowsToAdd);
            array_push($cartUpdated, $newSnowLeasing);
        }
    }
    else
    {
        throw new Exception('Valeurs trop élevées ou inférieures à 1, vérifiez la disponibilité du stock');
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
 * @throws : return error of quantity or date
 * @return $currentCart : contains array of the cart update
 */
function updateInCart($lineToChange,$qtyToChange,$nbDaysToChange,$currentCart)
{
    //if the informations isn't equal to 0
    if ($qtyToChange > 0 && $nbDaysToChange > 0)
    {
        //change data in cart
        for ($i = 0; $i < count($currentCart); $i++)
        {
            if ($i == $lineToChange)
            {
                if (isDispo($currentCart[$i]["code"], $qtyToChange, $currentCart))
                {
                    $currentCart[$i]["qty"] = $qtyToChange;
                    $currentCart[$i]["nbD"] = $nbDaysToChange;
                }
            }
        }
    }
    else
    {
        throw new Exception('Valeurs trop élevées ou inférieures à 1, vérifiez la disponibilité du stock');
        return false;
    }
    return $currentCart;
}

/**
 * This function is designed to manage the disposable of the quantity
 * @param $snowCode : contains the code of the snow
 * @param $qtyOfSnowRequested: contains the quantity of snows
 * @param $cart : contains the array of the cart
 * @throws : return error of quantity or date
 * @return true or false
 */
function isDispo($snowCode,$qtyOfSnowRequested,$cart)
{
    require_once "model/snowsManager.php";
    $theSnow = getASnow($snowCode);
    if (isset($cart))
    {
        //take the quantity
        foreach ($cart as $item)
        {
            if ($snowCode == $item["code"])
            {
                $tempQty = $qtyOfSnowRequested;
            }
        }
    }

    if (isset($tempQty))
    {
        $qtyOfSnowRequested = $tempQty;
    }

    //check if the quantity is correct
    if ($theSnow[0]["qtyAvailable"]>=$qtyOfSnowRequested)
    {
        return true;
    }
    else
    {
        throw new Exception('Quantité trop élevée ou inférieure à 1, Vérifiez la disponibilité du stock');
        return false;
    }
}
//endregion