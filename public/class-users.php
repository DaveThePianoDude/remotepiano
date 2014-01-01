<?php
 
//require_once ('class-databasehelpers.php');
require_once ('class-userdata.php');
 
class Users
{
   public function checkCredentials($username, $password)
   {
      // A UserID of 0 from the database indicates that the username/password pair
      // could not be found in the database
      $userID = 0;
      $digest = '';
 
      try
      {      
               $userID = 1;
           
 
         $dbh = null;
      }
      catch (PDOException $e)
      {
         $userID = 0;
         $digest = '';
      }
 
      return array ($userID, $username, $digest);
   }
}
 
?>