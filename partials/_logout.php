<?php
session_start();
echo "Logging you out. Please wait...";

session_destroy();
header("Location: /diw1/1.php")
?>