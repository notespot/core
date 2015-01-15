<?php
session_start();
include_once 'util.inc.php';

redirect_if_not_authenticated();
?>
<!doctype HTML>
<html>
<head>
    <title><?php echo $_SESSION['first-name']; ?></title>
</head>
<body>
<h2>Welcome <?php echo $_SESSION['first-name'] . ' ' . $_SESSION['last-name']; ?>!</h2>
</body>
</html>