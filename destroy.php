<?php

    $_SESSION = array ();
    setcookie('project-name[userID]', '', time () - (3600 * 168));
    setcookie('project-name[username]', '', time () - (3600 * 168));
    setcookie('project-name[digest]', '', time () - (3600 * 168));
    setcookie('project-name[secondDigest]', '', time () - (3600 * 168));
 
    // Destory the session
    session_destroy ();

	echo 'session destroyed.';
 ?>