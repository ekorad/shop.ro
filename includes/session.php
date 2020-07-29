<?php

$secure = false; // change this for SSL connections
$sc_params = session_get_cookie_params();
session_set_cookie_params($sc_params['lifetime'], $sc_params['path'], $sc_params['domain'], $secure, true);
session_name("SessionID");
session_start();
session_regenerate_id();
setcookie(session_name(), session_id(), time() + 100 * 24 * 60 * 60, "/");
?>