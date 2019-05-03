<?php
/**
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : Epreuve_151
 * Created  : 09.04.2019 - 13:45
 *
 * Last update :    [01.12.2018 author]
 *                  [add $logName in function setFullPath]
 * Git source  :    [link]
 */

/**
 * This function is designed to redirect the user to the home page (depending on the action received by the index)
 */

function home(){
    $_GET['action'] = "home";
    require "view/home.php";
}

//region users management

/**
 * This function id designed to show the login and connect someone to the site
 * @param $form -> formulaire reçu en post de la page login
 */
function login($form){

    if (isset($form["user_mail"])){
        require_once "model/usersManager.php";
        if (loginCheck($form["user_mail"],$form["user_password"])){
            $_SESSION["user"] = $form["user_mail"];
            $_GET['action'] = "home";
            require "view/home.php";
        }else{
            $warning = "login faux";
            $_GET['action'] = "login";
            require "view/login.php";
        }

    }else {
        $_GET['action'] = "login";
        require "view/login.php";
    }
}

function register($form){

    $_GET['action'] = "register";
    require "view/register.php";

}

//endregion

//region courses management
//endregion