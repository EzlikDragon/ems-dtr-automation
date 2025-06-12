<?php
session_start();
session_unset();
session_destroy();
header("Location: dtr_login.php");
exit();
