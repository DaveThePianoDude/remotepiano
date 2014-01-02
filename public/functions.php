<?php
 
require_once ('class-users.php');
require_once ('functions.php');
require_once ('pages.php');
 
define ('DB_HOST', 'localhost');
define ('DB_NAME', 'project_name');
define ('DB_USERNAME', 'sql-username');
define ('DB_PASSWORD', 'sql-password');
 
class DatabaseHelpers
{
   function blowfishCrypt($password, $length)
   {
      $chars = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
      $salt = sprintf ('$2a$%02d$', $length);
      for ($i=0; $i < 22; $i++)
      {
         $salt .= $chars[rand (0,63)];
      }
 
      return crypt ($password, $salt);
   }
}
 
function isSecuredPage($page)
{
   // Return true if the given page should only be accessible to validation users
   return $page == Page::INDEX;
}
 
function checkLoggedIn($page)
{
   $loginDiv = '';
   $action = '';
   if (isset($_POST['action']))
   {
      $action = stripslashes ($_POST['action']);
   }
 
   session_start ();
 
   echo $action;
      
   
 
   return $loginDiv;
}
 
?>