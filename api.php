<?php

include_once 'util.inc.php';

header("Content-Type: application/json");

if (isset($_GET['cities'])) {
    $state_id = filter_input(INPUT_GET, 'state');
    $cities = get_all_cities($state_id);
    if ($cities != false) {
        echo json_encode($cities, JSON_PRETTY_PRINT);
    } else
        echo json_encode(array(
            'error' => 1,
        ), JSON_PRETTY_PRINT);

} elseif (isset($_GET['states'])) {
    $country_id = filter_input(INPUT_GET, 'country');
    $states = get_all_states($country_id);
    if ($states != false) {
        echo json_encode($states, JSON_PRETTY_PRINT);
    } else
        echo json_encode(array(
            'error' => 1,
        ), JSON_PRETTY_PRINT);

} elseif (isset($_GET['departments'])) {
    $deps = get_all_departments();
    if ($deps != false) {
        echo json_encode($deps, JSON_PRETTY_PRINT);
    } else  echo json_encode(array(
        'error' => 1,
    ), JSON_PRETTY_PRINT);
} elseif (isset($_GET['institutes']) && isset($_GET['city'])) {
    $city_id = filter_input(INPUT_GET, "city");
    if ($city_id != false && $city_id != null) {
        $institutes = get_all_institutes($city_id);
        if ($institutes != false) {
            echo json_encode($institutes, JSON_PRETTY_PRINT);
        } else echo json_encode(array(
            'error' => 1,
        ), JSON_PRETTY_PRINT);

    } else echo json_encode(array(
        'error' => 1,
    ), JSON_PRETTY_PRINT);
} elseif (isset($_GET['courses']) && isset($_GET['institute'])) {
    $inst_id = filter_input(INPUT_GET, "institute", FILTER_VALIDATE_INT);
    if ($inst_id != null && $inst_id != false) {
        $courses = get_all_courses($inst_id);
        if ($courses != false) {
            echo json_encode($courses, JSON_PRETTY_PRINT);
        } else echo json_encode(array(
            'error' => 1,
        ), JSON_PRETTY_PRINT);
    } else echo json_encode(array(
        'error' => 1,
    ), JSON_PRETTY_PRINT);
}


function get_all_courses($inst_id)
{
    try {
        $conn = db_config(DB_NAME);
        $query = $conn->query("SELECT * FROM courses WHERE institute_id=$inst_id");
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    } catch (PDOException $e) {
//        echo $e->getMessage();
    }
    return false;
}

function get_all_institutes($city_id)
{
    try {
        $conn = db_config(DB_NAME);
        $query = $conn->query("SELECT * FROM institutes WHERE city_id=$city_id");
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    } catch (PDOException $e) {
//        echo $e->getMessage();
    }
    return false;

}

function get_all_departments()
{
    try {
        $conn = db_config(DB_NAME);
        $query = $conn->query("SELECT * FROM departments");
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    } catch (PDOException $e) {
//        echo $e->getMessage();
    }
    return false;
}

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

function get_all_cities($state_id)
{
    try {
        $conn = db_config(DB_NAME);
        $query = $conn->query("SELECT * FROM cities WHERE state_id=$state_id");
        $query->execute();
        $result = $query->fetchAll();
        return $result;
    } catch (PDOException $e) {
//        echo $e->getMessage();
    }
    return false;
}
