<?php
/**
 * Created by PhpStorm.
 * User: Thomas Huguet
 * Date: 06.05.2017
 * Time: 09:10
 */

session_start();
require "controler/controler.php";

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
          snowLeasingRequest($_GET['code']);
          break;
      case 'updateCartRequest':
           updateCartRequest($_GET['code'], $_POST);
          break;
      case 'deleteCartRequest':
          deleteCartRequest($_GET['line']);
          break;
      case 'displayCart':
          displayCart();
          break;
      default :
          home();
  }
}
else
{
    home();
}