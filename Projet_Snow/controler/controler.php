<?php
/**
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
 * @param $loginRequest containing login fields required to authenticate the user
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
    {   //the user does not yet fill the form
        $_GET['action'] = "login";
        require "view/login.php";
    }
}

/**
 * This fonction is designed
 * @param $registerRequest containing register fields required to register the user
 */
function register($registerRequest)
{
    //variable set
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
 * @param $userEmailAddress : user unique id
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
 * @param $snowCode containing the code of the snow
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
 * @param $snowCode containing the code of the snow
 */
function snowLeasingRequest($snowCode)
{
    if(isset($_SESSION['userEmailAddress']))
    {
         require "model/snowsManager.php";
         $snowsResults = getASnow($snowCode);
         $_GET['action'] = "snowLeasingRequest";
         require "view/snowLeasingRequest.php";
    }
    else
    {
        $_GET['action'] = "login";
        require "view/login.php";
    }
}

/**
 * This function designed to put the leasing request in the cart
 * @param $snowCode containing the code of the snow
 * @param $snowLocationRequest containing the updates request fields
 */
function updateCartRequest($snowCode, $snowLocationRequest)
{
    $qty =$snowLocationRequest['inputQuantity'];
    $days = $snowLocationRequest['inputDays'];
    $cartArrayTemp = array();
    if(isset($_SESSION['userType']))
    {
        if(isset($snowLocationRequest) && isset($snowCode))
        {
            
            if($qty > 0 || $days >0)
            {
                if (isDispo($qty, $days))
                {
                    if (isset($_SESSION['cart']))
                    {
                        $cartArrayTemp = $_SESSION['cart'];
                    }
                    require "model/cartManager.php";
                    $cartArrayTemp = updateCart($cartArrayTemp, $snowCode, $qty, $days);
                    $_SESSION['cart'] = $cartArrayTemp;
                }
                else
                {
                    $warning ="Ce snow n'est pas disponible !";
                }
            }
            else
            {
                $warning ="Veuillez entrer un nombre positif !";
            }
        }
        $_GET['action'] = "displayCart";
        displayCart();
    }
    else
    {
        $_GET['action'] = "login";
        require "view/login.php";
    }
}

/**
 * This function designed to manage to delete an article from cart
 * @param $snowCode containing the code of the snow
 */
function deleteCartRequest($snowCode)
{
        if (isset($snowCode))
        {
            array_splice($_SESSION['cart'],$_GET['line'],1);
            // Test if the cart is empty
            if (count($_SESSION['cart'])<1)
            {
                unset ($_SESSION['cart']); //Cancel cart
                require_once "model/snowsManager.php";
                $snowsResults=getSnows();
                $_GET['action'] = "displaySnows";
                require "view/snowsSeller.php";
            }
            else
            {
                $_GET['action'] = "cartManage";
                require "view/cart.php";
            }
        }
}
//endregion