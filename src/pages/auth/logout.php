<?php
// Destroy the session to log the user out
session_destroy();

// Redirect to the login page after logout
header('Location: /login');

exit;