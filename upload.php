<?php
session_start();

include_once 'util.inc.php';

redirect_if_not_authenticated();

function save_note()
{
    if (isset($_GET['upload'])) {
        $errors = array();
        $state = filter_input(INPUT_POST, "state", FILTER_VALIDATE_INT);
        $city = filter_input(INPUT_POST, "city", FILTER_VALIDATE_INT);
        $institute = filter_input(INPUT_POST, "institute", FILTER_VALIDATE_INT);
        $course = filter_input(INPUT_POST, "course", FILTER_VALIDATE_INT);
        $dep = filter_input(INPUT_POST, "dep", FILTER_VALIDATE_INT);
        $desc = filter_input(INPUT_POST, "desc");

        if ($state == null || $state == false)
            $errors[] = "State not selected";
        if ($city == null || $city == false)
            $errors[] = "City not selected";
        if ($institute == null || $institute == false)
            $errors[] = "Institute not selected";
        if ($course == null || $course == false)
            $errors[] = "Course not selected";
        if ($desc == null || $desc == false)
            $errors[] = "Description not given";
        if ($dep == null || $dep == false)
            $errors[] = "Department not selected";

        if (count($errors) == 0) {
            $conn = db_config(DB_NAME);
            $new_name = sha1("" . time());
            if (upload_file($new_name) == true) {
                $sql = "INSERT INTO notes (department_id, course_id, institute_id, user_id, link, description)
                          VALUES (:dep, :course, :inst, :usr, :link, :description)";
                try {
                    //print_r($sql);
                    $query = $conn->prepare($sql);
                    $query->bindParam(":dep", $dep);
                    $query->bindParam(":course", $course);
                    $query->bindParam(":inst", $institute);
                    $query->bindParam(":usr", $_SESSION['user-id']);
                    $query->bindParam(":link", $new_name);
                    $query->bindParam(":description", $desc);
                    $query->execute();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }

        } else {
            print_r($errors);
        }
    }
}

function upload_file($new_name)
{
    $errors = array();
    $target_dir = "uploads/";
    $temp = explode(".", $_FILES["fileToUpload"]["name"]);
    $new_name = $new_name . "." . end($temp);
    $target_file = $target_dir . $new_name;//basename($_FILES["fileToUpload"]["name"]);
    $upload_ok = 1;
    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

    if (file_exists($target_file)) {
        $errors[] = "Sorry, file already exists";
        $upload_ok = 0;
    }

    if ($_FILES["fileToUpload"]["size"] > 1000000) {
        $errors[] = "Sorry, your file is too large";
        $upload_ok = 0;
    }

    if ($file_type != "pdf" && $file_type != "txt") {
        $errors[] = "Sorry, only PDF and TXT files are allowed";
        $upload_ok = 0;
    }

    if ($upload_ok != 0) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            return true;
        }
    }
    return $errors;
}

save_note();

function get_all_states($country_id = 1)
{
    try {
        $conn = db_config(DB_NAME);
        $query = $conn->query("SELECT * FROM states WHERE country_id=$country_id");
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    } catch (PDOException $e) {
//        echo $e->getMessage();
    }
    return false;
}

?>

<!doctype HTML>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="assets/css/pure-min.css">
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/notespot-core.js"></script>
</head>
<body>
<?php echo_logout(); ?>

<form action="?upload" method="post" enctype="multipart/form-data" class="pure-form pure-form-aligned">
    <fieldset>
        <legend>Notes upload form</legend>

        <div class="pure-control-group">
            <label for="state">State</label>
            <select name="state" id="state_list" onchange="stateSelected();">
                <option value="0">-Select-</option>
                <?php
                $states = get_all_states();
                if ($states != false) {
                    foreach ($states as $state) {
                        echo '<option value="' . $state['id'] . '">' . $state['name'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="pure-control-group">
            <label for="city">City</label>
            <select name="city" id="city_list" onchange="citySelected()">
            </select>
        </div>

        <div class="pure-control-group">
            <label for="institute">Institute</label>
            <select name="institute" id="institute_list" onchange="instituteSelected()">
            </select>
        </div>

        <div class="pure-control-group">
            <label for="dep">Department</label>
            <select name="dep" id="department_list">
            </select>
        </div>

        <div class="pure-control-group">
            <label for="course">Course</label>
            <select name="course" id="course_list">
            </select>
        </div>

        <div class="pure-control-group">
            <label for="desc">Description</label>
            <textarea name="desc"></textarea>
        </div>

        <div class="pure-control-group">
            <label for="fileToUpload"> Select file to upload</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
        </div>

        <div class="pure-control-group">
            <input type="submit" class="pure-button pure-button-primary" value="Upload notes" name="submit">
        </div>
    </fieldset>


</form>

</body>
</html>