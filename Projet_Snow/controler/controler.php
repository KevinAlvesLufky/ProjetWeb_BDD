<?php
/**
 * This file contain all functions for each functionality
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

/**
 * This function is designed to redirect the user to the home page (depending on the action received by the index)
 */
function home()
{
    $_GET['action'] = "home";
    require "view/home.php";
}

//region users management
/**
 * This function is designed to manage login request
 * @param $loginRequest : contains login fields required to authenticate the user
 */
function login($loginRequest)
{
    //if a login request was submitted
    if (isset($loginRequest['inputUserEmailAddress']) && isset($loginRequest['inputUserPsw']))
    {
        //extract login parameters
        $userEmailAddress = $loginRequest['inputUserEmailAddress'];
        $userPsw = $loginRequest['inputUserPsw'];

        //try to check if user/psw are matching with the database
        require_once "model/usersManager.php";
        if (isLoginCorrect($userEmailAddress, $userPsw))
        {
            createSession($userEmailAddress);
            $_GET['loginError'] = false;
            $_GET['action'] = "home";
            require "view/home.php";
        }
        else
        { //if the user/psw does not match, login form appears again
            $_GET['loginError'] = true;
            $_GET['action'] = "login";
            require "view/login.php";
        }
    }
    else
    {   //the user does not yet fills the form
        $_GET['action'] = "login";
        require "view/login.php";
    }
}

/**
 * This fonction is designed to mange register request
 * @param $registerRequest : contains register fields required to register the user
 */
function register($registerRequest)
{
    //if a register request was submitted
    if (isset($registerRequest['inputUserEmailAddress']) && isset($registerRequest['inputUserPsw']) && isset($registerRequest['inputUserPswRepeat']))
    {
        //extract register parameters
        $userEmailAddress = $registerRequest['inputUserEmailAddress'];
        $userPsw = $registerRequest['inputUserPsw'];
        $userPswRepeat = $registerRequest['inputUserPswRepeat'];

        if ($userPsw == $userPswRepeat)
        {
            require_once "model/usersManager.php";
            if (registerNewAccount($userEmailAddress, $userPsw))
            {
                createSession($userEmailAddress);
                $_GET['registerError'] = false;
                $_GET['action'] = "home";
                require "view/home.php";
            }
        }
        else
        {
            $_GET['registerError'] = true;
            $_GET['action'] = "register";
            require "view/register.php";
        }
    }
    else
    {
        $_GET['action'] = "register";
        require "view/register.php";
    }
}

/**
 * This function is designed to create a new user session
 * @param $userEmailAddress : contains user's email address
 */
function createSession($userEmailAddress)
{
    $_SESSION['userEmailAddress'] = $userEmailAddress;
    //set user type in Session
    $userType = getUserType($userEmailAddress);
    $_SESSION['userType'] = $userType;
}

/**
 * This function is designed to manage logout request
 */
function logout()
{
    $_SESSION = array();
    session_destroy();
    $_GET['action'] = "home";
    require "view/home.php";
}
//endregion


//region snows management
/**
 * This function is designed to display Snows
 * There are two different view available.
 * One for the seller, an other one for the customer.
 */
function displaySnows()
{
    if (isset($_POST['resetCart']))
    {
        unset($_SESSION['cart']);
    }

    require_once "model/snowsManager.php";
    $snowsResults = getSnows();

    $_GET['action'] = "displaySnows";
    if (isset($_SESSION['userType']))
    {
        switch ($_SESSION['userType'])
        {
            case 1://this is a customer
                require "view/snows.php";
                break;
            case 2://this a seller
                require "view/snowsSeller.php";
                break;
            default:
                require "view/snows.php";
                break;
        }
    }
    else
    {
        require "view/snows.php";
    }
}

/**
 * This function is designed to get only one snow results (for aSnow view)
 * @param $snowCode : contains the code of the snow
 */
function displayASnow($snowCode)
{
    if (isset($registerRequest['inputUserEmailAddress']))
    {
        //TODO
    }
    require_once "model/snowsManager.php";
    $snowsResults= getASnow($snowCode);
    require "view/aSnow.php";
}
//endregion

//region Cart Management
/**
 * This function is designed to display the cart
 */
function displayCart()
{
    $_GET['action'] = "cart";
    require "view/cart.php";
}

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
        displayCart();
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
        displayCart();
    }
}


/**
* Function to show UserLeasing
* Add comment
* TODO
*
*/
function displayLeasing()
{

  /**
  * TODO
  * Prepare SQL Request if new data come by cart
  * Insert Data if need to prepare the SQL insert Request
  * Prepare SQL Resquest to catch Data to show
  * Delete Cart
  * 
  */
  require "view/UserLeasing.php";
}

//endregion
