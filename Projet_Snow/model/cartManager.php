<?php
/**
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : 151_2019_code
 * Created  : 04.04.2019 - 18:48
 *
 * Last update :    [01.12.2018 author]
 *                  [add $logName in function setFullPath]
 * Git source  :    [link]
 */

/**
 * @param $currentCartArray
 * @param $snowCodeToAdd
 * @param $qtyOfSnowsToAdd
 * @param $howManyLeasingDays
 * @return array
 */

function updateCart($currentCartArray, $snowCodeToAdd, $qtyOfSnowsToAdd, $howManyLeasingDays){
    $cartUpdated = array();
    $alreadyExist = false;
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
        $newSnowLeasing = array('code' => $snowCodeToAdd, 'dateD' => Date("d-m-y"), 'nbD' => $howManyLeasingDays, 'qty' => $qtyOfSnowsToAdd);
        array_push($cartUpdated, $newSnowLeasing);
    }

    if (isDispo($snowCodeToAdd,$)){

    }

    return $cartUpdated;
}

//in_array https://www.php.net/manual/en/function.in-array.php
//array_push() https://www.php.net/manual/en/function.array-push.php
//array_search
//unset


function deleteCart(){
    $_SESSION["cart"] = array();

}

/**
 *
 *
 */

function isDispo($snowCode,$qtyOfSnowRequested,$cart){
    require_once "model/snowsManager.php";
    $theSnow = getASnow($snowCode);
    if (isset($_SESSION["cart"])) {
        foreach ($_SESSION["cart"] as $cart) {
            if ($snowCode == $cart["code"]) {
                if (isset($tempQty)){
                 $tempQty = $tempQty + $cart["qty"];
                }else {
                    $tempQty = $cart["qty"];
                }
            }
        }
    }

    if (isset($tempQty)){
        $qtyOfSnowRequested = $qtyOfSnowRequested+$tempQty;
    }

    if ($theSnow[0]["qtyAvailable"]>=$qtyOfSnowRequested){
        return true;

    }

    return false;


}