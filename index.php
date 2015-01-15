<?php
session_start();
include_once 'util.inc.php';

$auth = false;
if (authenticated()) {
    $auth = true;
}

if (isset($_GET['logout'])) {
    session_destroy();
    $auth = false;
}
?>
<!doctype HTML>
<html>
<head>
    <title>Welcome <?php if ($auth == true) {
            echo $_SESSION['first-name'];
        } ?></title>
</head>
<body>
<?php if ($auth) {
    echo '<h1><a href="?logout">Logout</a></h1>';
}?>
</body>
</html>