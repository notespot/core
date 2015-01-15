<?php
session_start();
include_once 'util.inc.php';
if (authenticated()) {
    header("Location: index.php");
} else {
    session_destroy();
    $status = sign_up();
    if ($status != true) {
        print_r($status);
    }
}

function sign_up()
{
    $errors = array();
    $first_name = filter_input(INPUT_POST, 'first_name');
    $last_name = filter_input(INPUT_POST, 'last_name');
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    if ($first_name == NULL || $first_name == false || !valid_first_name($first_name))
        $errors[] = "First name not set";
    if ($last_name == NULL || $last_name == false || !valid_last_name($last_name))
        $errors[] = "Last name not set";
    if ($email == NULL || $email == false || !valid_email($email))
        $errors[] = "Email not set";
    if ($password == NULL || $password == false)
        $errors[] = "Password not set";
    if (count($errors) == 0) {
        __insert('users', array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'external_id' => NULL,
            'status' => 0
        ));
        return true;
    }
    return $errors;
}

?>
<!doctype HTML>
<html>
<head>
    <title></title>
</head>
<body>
<form method="post" action="#">
    <input type="text" placeholder="First name" name="first_name">
    <input type="text" placeholder="Last name" name="last_name">
    <input type="email" placeholder="Email" name="email">
    <input type="password" placeholder="Password" name="password">
    <input type="submit" value="Register">
</form>
</body>
</html>