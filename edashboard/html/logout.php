<?php
session_start();
session_destroy(); // Destroy all sessions
header("Location: ../../website/index.php"); // Redirect to homepage
exit();
?>