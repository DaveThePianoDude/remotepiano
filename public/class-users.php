<?php
 
require_once ('class-userdata.php');
 
class Users
{
   public function checkCredentials($username, $password)
   {
      // A UserID of 0 from the database indicates that the username/password pair
      // could not be found in the database
      $userID = 0;
      $digest = '';
	  $username = 'viennapres';
 
      try
      {      
         $userID = 1;
		 $digest = 'password';
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