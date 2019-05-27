<?php
/**
 * This file contain all functions about the cart
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

//region Cart Management
/**
 * This function is designed to get only one snow results (for aSnow view)
 * @param $snowCode : contains the code of the snow
 * @param $error : contains the error of the command (error quantity)
 */
function snowLeasingRequest($snowCode,$error)
{
    //if the user is connected, display the rent page
    if(isset($_SESSION['userEmailAddress']))
    {
         require_once "model/snowsManager.php";
         $snowsResults = getASnow($snowCode);
         if ($error !="") //check if there is an error
         {
             $warning = $error;
         }
         $_GET['action'] = "snowLeasingRequest";
         require_once "view/snowLeasingRequest.php";
    }
    else
    {
        $_GET['action'] = "login";
        require "view/login.php";
    }
}

/**
 * This function designed to put the leasing request in the cart
 * @param $snowCode : contains the code of the snow
 * @param $snowLocationRequest : contains the updates request fields
 */
function updateCartRequest($snowCode, $snowLocationRequest)
{
    //set variables
    $qty =$snowLocationRequest['inputQuantity'];
    $days = $snowLocationRequest['inputDays'];
    $cartArrayTemp = array();

        //check the fields of the form
        if(isset($snowLocationRequest) && isset($snowCode))
        {
            if($qty > 0 && $days > 0) //check if there are negative numbers
            {
                    //check if the user have something in the cart
                    if (isset($_SESSION['cart']))
                    {
                        $cartArrayTemp = $_SESSION['cart'];
                    }
                    require_once "model/cartManager.php";

                    //insert data snow in cart
                    $cartArrayTemp = updateCart($cartArrayTemp, $snowCode, $qty, $days);

                    //if the quantity isn't correct display an error
                    if ($cartArrayTemp == false)
                    {
                        $warning ="Quantité trop élevée ou inférieure à 1, Vérifiez la disponibilité du stock";
                        $_GET["action"]="snowLeasingRequest";
                        snowLeasingRequest($snowCode,$warning);
                    }else
                    {
                        $_SESSION['cart'] = $cartArrayTemp;
                    }
            }
            else
            {
                $warning ="Quantité trop élevée ou inférieure à 1, Vérifiez la disponibilité du stock";
                $_GET["action"]="snowLeasingRequest";
                snowLeasingRequest($snowCode,$warning);
            }
        }
        $_GET['action'] = "displayCart";
        require "view/cart.php";
}

/**
 * This function designed to manage to delete an article from cart
 * @param $snowLine : contains the line of the snow
 */
function deleteCartRequest($snowLine)
{
        if (isset($snowLine))
        {
          /**
          *TODO debug du deletcart, commande de base :
          * array_splice($_SESSION['cart'],$snowLine,1);
          */
            //Reorders the data of selected snow
            array_splice($_SESSION['cart'],$snowLine,0);

            // Test if the cart is empty
            if (count($_SESSION['cart'])<1)
            {
                unset ($_SESSION['cart']); //Delete cart
                require_once "model/snowsManager.php";
                $snowsResults=getSnows();
                $_GET['action'] = "displaySnows";
                require "view/snows.php";
            }
            else
            {
                $_GET['action'] = "cartManage";
                require "view/cart.php";
            }
        }
}

/**
 * This function is designed to handle the update of a cart item.
 * @param $snowLine : contains the line of the snow
 * @param $snowUpdateRequest : contains the fields of the update form
 */
function updateCartItem($snowLine, $snowUpdateRequest)
{
    //set variables
    $qty =$snowUpdateRequest['uQty'];
    $days = $snowUpdateRequest['uNbD'];
    $cartArrayTemp = $_SESSION['cart'];

    if (isset($snowLine))
    {
        if ($qty > 0 && $days > 0)
        {
            require_once "model/cartManager.php";
            $currentCart = updateInCart($snowLine, $qty, $days, $cartArrayTemp);

            //if the quantity isn't correct display an error
            if ($currentCart == false)
            {
                $warning = "Quantité trop élevée ou inférieure à 1, Vérifiez la disponibilité du stock";
            }
            $_SESSION['cart'] = $currentCart;
        }
        else
        {
            $warning = "Quantité trop élevée ou inférieure à 1, Vérifiez la disponibilité du stock";
        }
        $_GET['action'] = "displayCart";
        require"view/cart.php";
    }
}
//endregion
