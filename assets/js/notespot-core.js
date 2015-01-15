/**
 * Created by Anurag Gautam on 16-01-2015.
 */

function stateSelected() {
    var e = document.getElementById("state_list");
    var state_id = e.options[e.selectedIndex].value;
    $('#city_list').empty();
    $('#city_list').append('<option value="0" selected>-Select city-</option>');
    $.ajax({
        type: "GET",
        url: "api.php?cities&state=" + state_id,
        success: function (data) {
            $.each(data, function (i, e) {
                $('#city_list').append('<option value="' + e['id'] + '">' + e['name'] + '</option>');
            });
        }
    });
}

function citySelected() {
    var e = document.getElementById("city_list");
    var city_id = e.options[e.selectedIndex].value;
    $('#institute_list').empty();
    $('#institute_list').append('<option value="0" selected>-Select institute-</option>');

    $.ajax({
        type: "GET",
        url: "api.php?institutes&city=" + city_id,
        success: function (data) {
            $.each(data, function (i, o) {
                $('#institute_list').append('<option value="' + o['id'] + '">' + o['name'] + '</option>');
            })
        }
    });
}

function instituteSelected() {
    $('#department_list').empty();
    $.ajax({
        type: "GET",
        url: "api.php?departments",
        success: function (data) {
            $.each(data, function (i, o) {
                $('#department_list').append('<option value="' + o['id'] + '">' + o['name'] + '</option>')
            });
        }
    });
    departmentSelected();
}

function departmentSelected() {
    var e = document.getElementById("institute_list");
    var inst_id = e.options[e.selectedIndex].value;
    $('#course_list').empty();
    $.ajax({
        type: "GET",
        url: "api.php?courses&institute=" + inst_id,
        success: function (data) {
            $.each(data, function (i, o) {
                $('#course_list').append('<option value="' + o['id'] + '">' + o['name'] + '</option>');
            });
        }
    });
}