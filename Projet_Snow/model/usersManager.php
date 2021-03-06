<?php
/**
 * This file contain all functions to manage the users
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

//region users management
/**
 * This function is designed to verify user's login
 * @param $userEmailAddress : contains the email
 * @param $userPsw : contains the password
 * @throws : error if the login or password aren't correct
 * @return bool : "true" only if the user and psw match the database. In all other cases will be "false".
 */
function isLoginCorrect($userEmailAddress, $userPsw)
{
    $result = false;

    $strSeparator = '\'';
    $loginQuery = 'SELECT userHashPsw FROM users WHERE userEmailAddress = '. $strSeparator . $userEmailAddress . $strSeparator;

    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($loginQuery);

    // if the select is successful
    if (count($queryResult) == 1)
    {
        //check password
        $userHashPsw = $queryResult[0]['userHashPsw'];
        $result = password_verify($userPsw, $userHashPsw);
    }

    //throw error if the password isn't correct
    if($result == false)
    {
        throw new Exception('Login refusé');
    }

    return $result;
}

/**
 * This function is designed to register a new account
 * @param $userEmailAddress : contains the email
 * @param $userPsw : contains the password
 * @param $userPswRepeat : contains the second password
 * @throws : error if the insert doesn't work or the password isn't correct
 * @return bool|null
 */
function registerNewAccount($userEmailAddress, $userPsw, $userPswRepeat)
{
    //set variables
    $result = false;
    $strSeparator = '\'';
    $userType = 1;

    //check the password
    if ($userPsw == $userPswRepeat)
    {
        $userHashPsw = password_hash($userPsw, PASSWORD_DEFAULT);
    }
    else
    {
        throw new Exception('Inscription refusée');
    }

    $registerQuery = 'INSERT INTO users (`userEmailAddress`, `userHashPsw`, `userType`) VALUES ('.$strSeparator . $userEmailAddress .$strSeparator . ','.$strSeparator . $userHashPsw .$strSeparator.','.$strSeparator . $userType .$strSeparator.')';

    require_once 'model/dbConnector.php';
    $queryResult = executeQueryInsert($registerQuery);

    //check if the insert has worked
    if($queryResult)
    {
        $result = $queryResult;
    }
    else
    {
        throw new Exception('Inscription refusée');
    }

    return $result;
}

/**
 * This function is designed to get the type of user
 * For the webapp, it will adapt the behavior of the GUI
 * @param $userEmailAddress : contains the email
 * @return int (1 = customer ; 2 = seller)
 */
function getUserType($userEmailAddress)
{
    $result = 1;//we fix the result to 1 -> customer
    $strSeparator = '\'';

    //take the id of the user
    $getUserTypeQuery = 'SELECT userType FROM users WHERE users.userEmailAddress =' . $strSeparator . $userEmailAddress . $strSeparator;

    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);

    //check if the select has worked
    if (count($queryResult) == 1)
    {
        //take the userType
        $result = $queryResult[0]['userType'];
    }
    return $result;
}

/**
 * This function is designed to get the id of user
 * @param $userEmailAddress : contain the user email address
 * @return $result : the id of the user connected or false
 */
function getUserId($userEmailAddress)
{
    $result = false;

    $strSeparator = '\'';

    //take the id of the user
    $getUserIdQuery = 'SELECT id FROM users WHERE users.userEmailAddress =' . $strSeparator . $userEmailAddress . $strSeparator;

    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserIdQuery);

    //if the select has worked
    if (count($queryResult) == 1)
    {
        //take the id
        $result = $queryResult[0]['id'];
    }
    return $result;
}
//endregion