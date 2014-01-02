<?php
 
require_once ('functions.php');
 
checkLoggedIn (Page::INDEX);
 
?>
 
<html>
   <body>
      <form name="logout" method="post" action="login.php">
		<h3>This is the index page.</h3>
         <input type="hidden" name="action" value="logout" />
         <input type="submit" value="Logout" />
      </form>
   </body>
</html>