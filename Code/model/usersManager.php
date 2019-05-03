<?php
/**
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : Epreuve_151
 * Created  : 09.04.2019 - 13:48
 *
 * Last update :    [01.12.2018 author]
 *                  [add $logName in function setFullPath]
 * Git source  :    [link]
 */

/**
 * this function is designed for checking the logging
 * @param $mailForm
 * @param $passwordForm
 * @return bool
 */
function loginCheck($mailForm,$passwordForm){
    $return = false;
    $strSeparator = '\'';
    $query = "Select userEmailAddress, userPsw, pseudo from users where userEmailAddress =".$strSeparator.$mailForm.$strSeparator;
    require_once "model/dbConnector.php";
    $result = executeQuerySelect($query);

    if (isset($result[0])){
        if ($result[0]["userPsw"]==$passwordForm){
            $return = true;
        }
    }


    return $return;
}


function addUser ($userEmail,$userPseudo,$userPsw){
    $strSeparator = '\'';
    //$userPswHashed = password_hash($userPsw,PASSWORD_DEFAULT);
    $query = "insert into users (userEmailAddress,UserPsw,pseudo) values (".$strSeparator.$userEmail.$strSeparator.",".$strSeparator.$userPsw.$strSeparator.",".$strSeparator.$userPseudo.$strSeparator.")";

    require "model/dbConnector.php";
    executeQuery($query);

}
?>