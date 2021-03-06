var nonLinearStepSlider;
var ageRange;
var ageMin;
var ageMax;
var gender = ""; // initialization
var floors = [];
var filter_residents;
var selected_residents = [];
var filter = [];
var groups = [];
var cookie_name = "filter " + caregiverID;
var filterObject;
var filterInfo = [[]];
var cookieArray = [];

$( document ).ready(function() {
    google.charts.load('current', {'packages': ['corechart']});
    //showFilters();
    clickAddGroup();
    clickFilter(caregiverID);
});

$(window).resize(function(){
    chart0function();
    chart2function();
    chart3function();
});

// no longer used
function setCookie(arg) {   // arg:Object    
    // value
    var cookieJSON = getCookie();                   
    var cookieArray = [];
    if (cookieJSON != null)
        cookieArray = JSON.parse(cookieJSON);            
    cookieArray.push(arg);
    var cookie_value = JSON.stringify(cookieArray);
    
    // time
    var d = new Date();
    var exdays = 15;
    d.setTime(d.getTime() + (exdays * 1000 * 24 * 60 * 60));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cookie_name + "=" + cookie_value + ";" + expires + "; path=/";
}

// no longer used
function getCookie() {
    var cookie = document.cookie;
    if (cookie !== null) {
        var parts = cookie.split(cookie_name + "=");
        if (parts.length === 2)
            return parts.pop();
    }
}

function clickAddGroup()
{
    document.getElementById("btnSave").disabled = true;
    /*
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    //$('.help-block').empty(); // clear error string, TODO
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Group'); // Set Title to Bootstrap modal title
    */
    if (nonLinearStepSlider == null) {
        nonLinearStepSlider = document.getElementById('slider-non-linear-step');
        noUiSlider.create(nonLinearStepSlider, {
            start: [50, 90],
            step: 1,
            range: {
                'min': [20],
                'max': [120]
            }
        });

        ageRange = [
            document.getElementById('age-min'),
            document.getElementById('age-max')
        ];

        nonLinearStepSlider.noUiSlider.on('update', function (values, handle) {
            ageRange[handle].innerHTML = parseInt(values[handle]);
        });
        
        nonLinearStepSlider.noUiSlider.on('change', function () {
            clickFilter(caregiverID);
        });
    }
}

function clickFilter(caregiverID)
{
    var url = base_url + 'index.php/caregiver/filterGroup';
    var caregiverID = caregiverID;
    // clear global array
    floors = [];
    filter = [];
    // GENDER
    if ( $("#optionMale").is(':checked') && !$("#optionFemale").is(':checked') ) {
        gender = "male";
    } else if ( !$("#optionMale").is(':checked') && $("#optionFemale").is(':checked') ) {
        gender = "female";
    } else if ( $("#optionMale").is(':checked') && $("#optionFemale").is(':checked') ) {
        gender = "gender";
    } else if ( !$("#optionMale").is(':checked') && !$("#optionFemale").is(':checked') ) {
        gender = "";
    }

    // FLOOR
    var f = document.getElementById("floor");
    for (var i = 0; i < f.options.length; i++) {
        if (f.options[i].selected) {
            if (!(f.options[i].value in floors)) { // prevent duplicates
                floors.push(f.options[i].value);
            }
        }
    }

    // AGE
    nonLinearStepSlider.noUiSlider.on('update', function (values, handle) {
        if (handle === 0) {
            ageMin = parseInt(values[handle]);
        } else if (handle === 1) {
            ageMax = parseInt(values[handle]);
        }
    });
    filter.push(ageMin);
    filter.push(ageMax);
    filter.push(gender);
    filter.push(floors);
    filterObject = {ageMin: ageMin, ageMax: ageMax, gender: gender, floors: floors};
    
    $.ajax({
        type: "POST",
        url: base_url + 'index.php/caregiver/filterGroup',
        data: {
            "ageMin": ageMin,
            "ageMax": ageMax,
            "gender": gender,
            "floors": floors[0]
        },
        dataType: "text",
        cache: false,

        success: function (data) {
            if (data.length === 0) {
                console.log("NO DATA!")
            } else {
                filter_residents = JSON.parse(data);
                showFResidents(filter_residents);
            }
            
        },
        error: function () {
            alert("Error: filter")
        },
    });
}

function showFResidents(arg) {
    var filter_residents = arg;
    $('#result-list').html("<select class=\"form-control\" id=\"filter_resident\" " 
            + "multiple=\"multiple\" " // onchange=\"clickFilter(caregiverID)\"
            + "></select>");
    var options = "";
    if (filter_residents.length > 0) {
        document.getElementById("btnSave").disabled = false;
        document.getElementById('result-info').innerHTML = "";
        document.getElementById('update_div').style.display = "block";
        document.getElementById('chart1_div').style.display = "block"; 
        for (filter_resident of filter_residents) {
            {
                options = "<option value = " + filter_resident.id + ">" + filter_resident.first_name +
                        " " + filter_resident.last_name + "</option>";
                $('#result-list select').append(options);
            }
            $("#result-list option").attr("selected", "selected");
        }
        clickStats();
    } else {
        document.getElementById("btnSave").disabled = true;
        document.getElementById('result-info').innerHTML = "<br>" + no_result;
        document.getElementById('update_div').style.display = "none"; // TODO: snackbar
        document.getElementById('chart1_div').style.display = "none"; 
    }
}

// update charts for filtered result 
function clickStats() {
    selected_residents = [];
    var f = document.getElementById("filter_resident");
    for (var i = 0; i < f.options.length; i++) {
        if (f.options[i].selected) {
            if (!(f.options[i].value in selected_residents)) { // prevent duplicates
                selected_residents.push(f.options[i].value);
            }
        }
    }
    
    /*
    $.ajax({
        type: "POST",
        url: base_url + 'index.php/caregiver/addGroup',
        data: {
            "selected_residents": selected_residents,
            "caregiverID": caregiverID,
            "filter": filter.toString()
        },
        dataType: "text",
        cache: false,

        success: function (data) {
            console.log(filter.toString());*/
            //setCookie(filterObject);
            //$('#modal_form').modal('hide');
            //showFilters();
            filter_residents = 0;
            chart0function();
            chart2function();
            chart3function();
        /*},
        error: function () {
        },
    });*/
}

// no longer used
function reload(){
    $.ajax({
        type: "POST",
        url: base_url + 'index.php/caregiver/getGroups',
        data: {
            "selected_residents": selected_residents,
            "caregiverID": caregiverID,
            "filter": filter.toString()
        },
        dataType: "text",
        cache: false,

        success: function (data) {
            groups = JSON.parse(data);
        },
        error: function () {
            alert("Error: reload");
        },
    });
}

// no longer used
function showFilters() {
    filterInfo = [[]];
    var cookieJSON = getCookie();
    if (cookieJSON != null) {
        cookieArray = JSON.parse(cookieJSON);
        var i = 0;
        var code = "";
        $('#accordion_filters').empty();

        for (cookie of cookieArray) {
            i++;
            code = "<div class=\"panel panel-default\">" +
                    "<div class=\"panel-heading \">" +
                    "<h4 class=\"panel-title\">" +
                    "<a data-toggle=\"collapse\" data-parent=\"#accordion_filters\" href=\"#collapse" + i + "\">" + i +
                    "</a>" + "</h4>" + "</div>" +
                    "<div id=\"collapse" + i + "\" class=\"panel-collapse collapse\">";
                    for(key in cookie){
                        code += "<div class=\"panel-body\">" + key + ":\t" + cookie[key] + "</div>" ;
                    }
                    code += "<div class=\"panel-body\">";
                    code += "<button type=\"button\" class=\"btn-link\" id=\"btn" + i + "\" onclick=\"chart0function("+ "\)\">Graph</button>" ;
                    code += "</div></div></div>";
            $('#accordion_filters').append(code);
        }
    }
}

// c_statistics_all_category_selected_individual
function chart0function() {
    $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });
    
    /*var i = 0;
    for (cookie of cookieArray) {
        i++;
        $('#btn' + i).click(function () {
            console.log('you clicked ' + i);
        });
    }*/
    //google.charts.load('current', {'packages': ['corechart']});

    $.ajax({
        type: "POST",
        url: base_url + 'index.php/caregiver/load_avarage_score_per_group_per_category_chart',
        data: {selected_residents: selected_residents},
        dataType: "text",
        cache: false,

        success: function (data) {
            //document.getElementById("btnSave").disabled = true;
            var Yaxis = [];
            var Xaxis = [];
            var response = JSON.parse(data);
            Xaxis = response[0];
            Yaxis = response[1];
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                columnChart(Yaxis, Xaxis, data, "chart1_div"); // ,chart_title0
            }
        }
    });
    return false;
}

// no longer used
function chart1function() {

    var resident = parseInt($("#residents_select").val());

    $.ajax({
        type: "POST",
        url: base_url + "index.php/caregiver/load_resident_chart",
        data: {resident: resident},
        dataType: "text",
        cache: false,

        success: function (data) {
            var Yaxis = [];
            var Xaxis = [];
            var response = JSON.parse(data);
            Xaxis = response[0];
            Yaxis = response[1];
            //google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                columnChart(Yaxis, Xaxis, data, "chart1_div", chart_title1);
            }
        }
    });
    return false;
}

// c_statistics_category_all_individual
function chart2function() {
    var category = parseInt($("#categories_select").val());
    
    $.ajax({
        type: "POST",
        url: base_url + "index.php/caregiver/load_category_chart",
        data: {
            category: category,
            selected_residents: selected_residents
        },
        dataType: "text",
        cache: false,

        success: function (data) {
            var Yaxis = [];
            var Xaxis = [];
            var response = JSON.parse(data);
            Xaxis = response[0];
            Yaxis = response[1];
            google.charts.setOnLoadCallback(drawChart2);

            function drawChart2() {
                var data = new google.visualization.DataTable();
                columnChart(Yaxis, Xaxis, data, "chart2_div"); // ,chart_title2
            }
        }
    });
    return false;
}

// c_statistics_average_scores_of_selected_residents
function chart3function() {
    $.ajax({
        type: "POST",
        url: base_url + "index.php/caregiver/load_avarage_score_per_resident_chart",
        data: {selected_residents: selected_residents},
        dataType: "text",
        cache: false,

        success: function (data) {
            var Yaxis = [];
            var Xaxis = [];
            var response = JSON.parse(data);
            Xaxis = response[0];
            Yaxis = response[1];
            google.charts.setOnLoadCallback(drawChart3);

            function drawChart3() {
                var data = new google.visualization.DataTable();
                columnChart(Yaxis, Xaxis, data, "chart3_div"); // ,chart_title3
            }
        }
    });
    return false;
}