<?php
 
require_once ('class-userdata.php');
 
class Users
{
   public function checkCredentials($username, $password)
   {
      // A UserID of 0 from the database indicates that the username/password pair
      // could not be found in the database
      $userID = 0;
      $digest = 'password';

		if ($username == 'viennapres') $userID = 1;
 
      return array ($userID, $username, $digest);
   }
}
 
?>