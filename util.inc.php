<?php
define('DB_NAME', "notespot");

function set_session_values($first_name, $last_name, $id)
{
    $_SESSION['user-id'] = $id;
    $_SESSION['first-name'] = $first_name;
    $_SESSION['last-name'] = $last_name;
    $_SESSION['auth-key'] = md5('' . time());
}

function authenticated()
{
    return is_valid_session();
}

function redirect_if_not_authenticated()
{
    if (!authenticated()) {
        session_destroy();
        header("Location: index.php");
    }
}

function redirect_if_authenticated()
{
    if (authenticated())
        header("Location: index.php");
}


function is_valid_session()
{
    return
        isset($_SESSION['auth-key']) &&
        isset($_SESSION['user-id']) &&
        isset($_SESSION['first-name']) &&
        isset($_SESSION['last-name']);
}

function db_config($db_name, $server = 'localhost', $username = "root", $password = '')
{
    try {
        $conn = new PDO("mysql:host=$server;dbname=$db_name", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return false;
}

/** Form validation Code */
/*-----------------------Contains the form fields verification code------------------------------------*/
function valid_name($val)
{
    return preg_match('/[a-zA-Z]+/', $val) != false;

}

/**
 * @return true if the $val is valid else false
 */
function valid_first_name($val)
{
    return valid_name($val);
}

function valid_last_name($val)
{
    return valid_name($val);
}

function valid_email($val)
{
    return preg_match("/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $val) != false;
}

/*------------------------------------Validation code ends-------------------------------------------*/


function __insert($tbl, $values)
{
    $conn = db_config(DB_NAME);
    $sql = "INSERT INTO `$tbl` ";
    $cols = "";
    $vals = "";
    foreach ($values as $k => $v) {
        $cols .= "`$k`,";
        $vals .= "'$v',";
    }
    $cols = "(" . rtrim($cols, ",") . ')';
    $vals = "(" . rtrim($vals, ",") . ")";
    $sql .= $cols . " VALUES " . $vals;
    try {
        $conn->exec($sql);
    } catch (PDOException $e) {
        echo "$sql: " . $e->getMessage();
    }
}