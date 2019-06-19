<?php
/**
 * This file contain all functions about the users
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

/**
 * This function is designed to redirect the user to the home page
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
    //test the database connexion
    require_once "model/dbConnector.php";
    if (testDBConnexion())
    {
        //if a login request was submitted
        if (isset($loginRequest['inputUserEmailAddress']) && isset($loginRequest['inputUserPsw']))
        {
            //extract login parameters
            $userEmailAddress = $loginRequest['inputUserEmailAddress'];
            $userPsw = $loginRequest['inputUserPsw'];

            //try to check if user/psw are matching with the database
            require_once "model/usersManager.php";

            try
            {
                isLoginCorrect($userEmailAddress, $userPsw); //check if the login fields are correct
                createSession($userEmailAddress);
                $_GET['action'] = "home";
                require "view/home.php";
            }
            catch (Exception $e)
            {
                //if the user/psw does not match, login form appears again
                $msgError = $e->getMessage();
                $_GET['action'] = "login";
                require "view/login.php";
            }
        }
        else
        {
            //the user does not yet fills the form
            $_GET['action'] = "login";
            require "view/login.php";
        }
    }
}

/**
 * This fonction is designed to manage register request
 * @param $registerRequest : contains register fields required to register the user
 */
function register($registerRequest)
{
    //test the database connexion
    require_once "model/dbConnector.php";
    if(testDBConnexion())
    {
        //if a register request was submitted
        if (isset($registerRequest['inputUserEmailAddress']) && isset($registerRequest['inputUserPsw']) && isset($registerRequest['inputUserPswRepeat']))
        {
            //extract register parameters
            $userEmailAddress = $registerRequest['inputUserEmailAddress'];
            $userPsw = $registerRequest['inputUserPsw'];
            $userPswRepeat = $registerRequest['inputUserPswRepeat'];

            require_once "model/usersManager.php";

            try
            {
                registerNewAccount($userEmailAddress, $userPsw, $userPswRepeat); // Insert register fields in the BD
                createSession($userEmailAddress);
                $_GET['action'] = "home";
                require "view/home.php";
            }
            catch (Exception $e)
            {
                //if the user/psw does not match, register form appears again
                $msgError = $e->getMessage();
                $_GET['action'] = "register";
                require "view/register.php";
            }
        }
        else
        {
            //the user does not yet fills the form
            $_GET['action'] = "register";
            require "view/register.php";
        }
    }
}

/**
 * This function is designed to create a new user session
 * @param $userEmailAddress : contains user's email address
 */
function createSession($userEmailAddress)
{
    $_SESSION['userEmailAddress'] = $userEmailAddress;

    //set user type and id in Session
    require_once "model/usersManager.php";
    $userId = getUserId($userEmailAddress); //take user id
    $userId = (int)$userId; //convert var in int

    $userType = getUserType($userEmailAddress); //take user type

    $_SESSION['userType'] = $userType;
    $_SESSION['userId'] = $userId;

    require_once 'model/rentManager.php';
    if(!getSnowLeasingsUser())
    {
        unset($_SESSION["haveLeasing"]); //if the user doesn't have snows, delete haveLeasing
    }
    else
    {
        $_SESSION["haveLeasing"] = getSnowLeasingsUser(); //take all snows of the user
    }
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
