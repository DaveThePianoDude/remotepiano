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
 
   // Check if we're already logged in, and check session information against cookies
   // credentials to protect against session hijacking
   if (isset ($_COOKIE['project-name']['userID']) &&
        
       (!isset ($_COOKIE['project-name']['username']) ||
        (isset ($_COOKIE['project-name']['username']) &&
         Users::checkCredentials($_COOKIE['project-name']['username'],
                                 $_COOKIE['project-name']['digest']))))
   {
      // Regenerate the ID to prevent session fixation
      session_regenerate_id ();
 
      // Restore the session variables, if they don't exist
      if (!isset ($_SESSION['project-name']['userID']))
      {
         $_SESSION['project-name']['userID'] = $_COOKIE['project-name']['userID'];
      }
 
      // Only redirect us if we're not already on a secured page and are not
      // receiving a logout request
      if (!isSecuredPage ($page) && $action != 'logout')
      {
         header ('Location: ./');
 
         exit;
      }
   }
   else
   {
      // If we're not already the login page, redirect us to the login page
      if ($page != Page::LOGIN)
      {
         header ('Location: login.php');
 
         exit;
      }
   }
   
   // If we're not already logged in, check if we're trying to login or logout
   if ($page == Page::LOGIN && $action != '')
   {
      switch ($action)
      {
         case 'login':
         {
            $userData = Users::checkCredentials (stripslashes ($_POST['login-username']),
                                                 stripslashes ($_POST['password']));
            if ($userData[0] != 0)
            {
               $_SESSION['project-name']['userID'] = $userData[0];
               $_SESSION['project-name']['ip'] = $_SERVER['REMOTE_ADDR'];
               $_SESSION['project-name']['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
               if (isset ($_POST['remember']))
               {
                  // We set a cookie if the user wants to remain logged in after the
                  // browser is closed
                  // This will leave the user logged in for 168 hours, or one week
                  setcookie('project-name[userID]', $userData[0], time () + (3600 * 168));
                  setcookie('project-name[username]',
                  $userData[1], time () + (3600 * 168));
                  setcookie('project-name[digest]', $userData[2], time () + (3600 * 168));
                  setcookie('project-name[secondDigest]',
                  DatabaseHelpers::blowfishCrypt($_SERVER['REMOTE_ADDR'] .
                                                 $_SERVER['HTTP_USER_AGENT'], 10), time () + (3600 * 168));
               }
               else
               {
                  setcookie('project-name[userID]', $userData[0], false);
                  setcookie('project-name[username]', '', false);
                  setcookie('project-name[digest]', '', false);
                  setcookie('project-name[secondDigest]',
                  DatabaseHelpers::blowfishCrypt($_SERVER['REMOTE_ADDR'] .
                                                 $_SERVER['HTTP_USER_AGENT'], 10), time () + (3600 * 168));
               }
 
 
				echo ($userData[0]); 
               //header ('Location: ./');
 
               exit;
            }
            else
            {
               $loginDiv = '<div id="login-box" class="error">The username or password ' .
                           'you entered is incorrect.</div>';
            }
            break;
         }
		 
         // Destroy the session if we received a logout or don't know the action received
         case 'logout':
         default:
         {
            // Destroy all session and cookie variables
            $_SESSION = array ();
            setcookie('project-name[userID]', '', time () - (3600 * 168));
            setcookie('project-name[username]', '', time () - (3600 * 168));
            setcookie('project-name[digest]', '', time () - (3600 * 168));
            setcookie('project-name[secondDigest]', '', time () - (3600 * 168));
 
            // Destory the session
            session_destroy ();
 
            $loginDiv = '<div id="login-box" class="info">Thank you. Come again!</div>';
 
            break;
         }
      }
   }
 
   return $loginDiv;
}
 
?>