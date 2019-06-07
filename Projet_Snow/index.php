<?php
/**
 * This file is used to redirect the user according to his actions
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

session_start();
require "controler/users.php";
require "controler/snows.php";
require "controler/cart.php";
require "controler/rent.php";

if (isset($_GET['action']))
{
  $action = $_GET['action'];
  switch ($action)
  {
      case 'home' :
          home();
          break;
      case 'login' :
          login($_POST);
          break;
      case 'logout' :
          logout();
          break;
      case 'register' :
          register($_POST);
          break;
      case 'displaySnows' :
          displaySnows();
          break;
      case 'displayASnow' :
          displayASnow($_GET['code']);
          break;
      case 'snowLeasingRequest':
          snowLeasingRequest($_GET['code'],"");
          break;
      case 'displayCart':
          displayCart();
          break;
      case 'updateCartRequest':
           updateCartRequest($_GET['code'], $_POST);
          break;
      case 'deleteCartRequest':
          deleteCartRequest($_GET['line']);
          break;
      case 'updateCartItem':
          updateCartItem($_GET['line'], $_POST);
          break;
      case 'emptyCart':
          emptyCart();
          break;
      case 'confirmLeasing':
          confirmLeasing();
          break;
      case 'displayLeasing':
          displayLeasing();
          break;
      case 'displayManageLeasing':
          displayManageLeasing($_GET['idLeasing']);
          break;
      default :
          home();
  }
}
else
{
    home();
}
