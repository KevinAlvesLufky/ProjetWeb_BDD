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
 * This function is designed to display the cart
 */
function displayCart()
{
    $_GET['action'] = "displayCart";
    require_once "view/cart.php";
}
/**
 * This function is designed to get only one snow results (for aSnow view)
 * @param $snowCode : contains the code of the snow
 * @param $error : contains the error of the command (error quantity)
 */
function snowLeasingRequest($snowCode,$msgError)
{
    //if the user is connected, display the rent page
    if(isset($_SESSION['userEmailAddress']))
    {
         require_once "model/snowsManager.php";
         $snowsResults = getASnow($snowCode);
         if ($msgError !="") //check if there is an error
         {
             $warning = $msgError;
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

        //check if the user have something in the cart
        if (isset($_SESSION['cart']))
        {
            $cartArrayTemp = $_SESSION['cart'];
        }


        //insert data snow in cart
        try
        {
            require_once "model/cartManager.php";
            $cartArrayTemp = updateCart($cartArrayTemp, $snowCode, $qty, $days);

            $_SESSION['cart'] = $cartArrayTemp;
        }
        catch (Exception $e)
        {
            $msgError = $e->getMessage();
            $_GET["action"]="snowLeasingRequest";
            snowLeasingRequest($snowCode,$msgError);
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
        //Reorders the data of selected snow
        array_splice($_SESSION['cart'],$snowLine,1);

        // Test if the cart is empty
        if (count($_SESSION['cart'])<1)
        {
            unset ($_SESSION['cart']); //Delete cart
            $_GET['action'] = "displayCart";
            require "view/cart.php";
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
        try
        {
            require_once "model/cartManager.php";
            $currentCart = updateInCart($snowLine, $qty, $days, $cartArrayTemp);

            $_SESSION['cart'] = $currentCart;
        }
        catch (Exception $e)
        {
            $msgError = $e->getMessage();
        }

    }
    require"view/cart.php";
}

/**
 * This function is designed to delete the cart
 */
function emptyCart()
{
    unset ($_SESSION['cart']); //delete cart

    require_once "model/snowsManager.php";
    $snowsResults = getSnows();

    $_GET['action'] = "displaySnows";
    require "view/snows.php";
}
//endregion
