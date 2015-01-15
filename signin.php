<?php
session_start();

include_once 'util.inc.php';

redirect_if_authenticated();

function sign_in()
{
    $errors = array();
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    if ($email == NULL || $email == false || !valid_email($email)) {
        $errors[] = "Email or password is either not set or is incorrect";
    }
    if (count($errors) == 0) {
        $conn = db_config(DB_NAME);
        $stmt = $conn->prepare("SELECT id, first_name, last_name, password FROM users WHERE email=:email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetchAll();
        if ($stmt->rowCount() == 1) {
            $row = $result[0];
            if (password_verify($password, $row['password'])) {
                set_session_values($row['first_name'], $row['last_name'], $row['id']);
                return true;
            } else {
                $errors[] = "Email or password is incorrect";
            }
        } else {
            $errors[] = "Email or password is incorrect";
        }
    }
    return $errors;
}

$status = sign_in();

if ($status !== true) {
    print_r($status);
} else {
    redirect_if_authenticated();
}

?>
<!doctype HTML>
<html>
<head>
    <title>Sign In to Notespot</title>
</head>
<body>
<form method="post" action="#">
    <input type="email" placeholder="Email" name="email">
    <input type="password" placeholder="password" name="password">
    <input type="submit" value="Sign In">
</form>
</body>
</html>