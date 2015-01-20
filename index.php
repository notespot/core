<<<<<<< HEAD
<?php
include_once './util.inc';
//db_config(DB_NAME);
function query(){
  $query=  filter_input(INPUT_GET, "q");
  header("Content-Type:application/json");
$conn=db_config(DB_NAME);
$sqlob=$conn->query("SELECT * from notes,courses where courses.name like '%$query%' and notes.course_id=courses.id  ");

$sqlob->execute();
$res=$sqlob->fetchAll();
  echo json_encode($res,JSON_PRETTY_PRINT);
  }
if(isset($_GET["q"]))
{
    query();
}
    

?>
=======
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
>>>>>>> origin/master
