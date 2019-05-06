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
    foreach ($cartUpdated as $cart){

        if ($cart["code"]=$snowCodeToAdd && $cart["nbD"]=$howManyLeasingDays){
            $cart["qty"] = $cart["qty"]+$qtyOfSnowsToAdd;
            $alreadyExist = true;
        }

    }
    if (!$alreadyExist) {
        $newSnowLeasing = array('code' => $snowCodeToAdd, 'dateD' => Date("d-m-y"), 'nbD' => $howManyLeasingDays, 'qty' => $qtyOfSnowsToAdd);
        array_push($cartUpdated, $newSnowLeasing);
    }



    return $cartUpdated;
}

//in_array https://www.php.net/manual/en/function.in-array.php
//array_push() https://www.php.net/manual/en/function.array-push.php
//array_search
//unset

/**
 *
 *
 */

function isDispo($snowCode,$qtyOfSnow){
    require_once "model/snowsManager.php";
    $theSnow = getASnow($snowCode);

    if ($theSnow["qtyAvailable"]>=$qtyOfSnow){
        return true;

    }

    return false;


}