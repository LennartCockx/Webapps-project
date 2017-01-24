// Global variables
var questions;
var questionSet;
var index = 0;
var max;
var width;
var timeout;
var base_url;
var firstButtonText = null;
var ButtonText;
var categoryCompletedText;
var totNumberOfQuestions;

// This function is used for setting the baseurl and having the correct language in the textareas that can change
function initialize(baseurl, buttontext, firstbuttontext, categorycompletedtext,numberQuestions) {
    base_url = baseurl;
    firstButtonText = firstbuttontext;
    ButtonText = buttontext;
    categoryCompletedText = categorycompletedtext;
    totNumberOfQuestions = numberQuestions;
    console.log(numberQuestions);
    
}


// User has to be logged in as resident to be able to do this!
// This function stores the answer of a specific resident, the storing is done with an ajax request
function storeAnswer(chosenOption, base_url, categoryName) {
    $("#GoBackButton").html(ButtonText);
    // check if not all questions were completed
    /*if (index < max) {*/
    var url = base_url + 'index.php/resident/question_store_answer';
    var data = {question_set: questionSet,
        chosen_option: chosenOption + 1};/* In the database the chosenoptions start from 1 not from 0 */
    $.ajax({
        url: url,
        type: 'POST',
        data: JSON.stringify(data),
        dataType: "text",
        cache: false,
        processData: false
    });

    // modify the question
    index++;
    // change the progress bar
    width = index / max * 100;
    console.log("width: " + width);
    $('#progressBar').css('width', width + "%");
    $('#progressbarText').text("Vraag " + index + " van de " + max);
    //console.log(index);



    if (index < max) {
        // animations on the question text
        $("#question_text").finish();
        $("#question_text").text("");
        $("#question_text").text(questions[index].question);
        questionSet = questions[index].question_set;
        $("#question_text").css('color', '#FF5722');
        $("#question_text").animate({color: 'black'}, 1500);
//            $('.answerbutton').attr("disabled", "disabled");
//            setTimeout(function(){
//                $('.answerbutton').removeAttr("disabled");
//            },1000);

    } else {
        $("#question_text").text(categoryCompletedText);
        $("#question_text").finish();
        $("#question_text").css('color', '#FF5722');
        $("#question_text").animate({color: 'black'}, 1500);
        //$("#progress").effect( "bounce", {times:5,distance: 50}, 3 );
        timeout = setTimeout(function () {
            window.location.href = base_url + "index.php/resident/completed?category=" + categoryName;

        }, 750);
    }
    /*} else {
     window.location.href = base_url + "index.php/resident/completed?category=" + categoryName;
     }*/
}

function loadQuestion(i) {
    // put all the questions in a variable and put them in the view
    questions = i;
    questionSet = questions[index].question_set;
    max = questions.length;
    window.addEventListener("load", function () {
        $("#question_text").text(questions[index].question);
        width = index / max * 100;
        $('#progressBar').css('width', width + "%");
        $('#progressbarText').text("Vraag " + index + " van de " + max);
    }, false);
}
// This function is called when the go back button is pressed
function pressGoBack() {
    if (index === 0) {
        // answers need to be deleted again when you go back otherwise they are assumed correct
        var url = base_url + 'index.php/resident/delete_answers';
        var data = {question_set: questionSet};
        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify(data),
            dataType: "text",
            cache: false,
            processData: false
        });
        window.location.href = base_url + 'index.php/resident/categories?';
    }
    if (index === 1) {
        // the button has a different text value to go back to the category choice
        $('#GoBackButton').html(firstButtonText);
    }
    if (index > 0) {
        index--;
        window.clearTimeout(timeout)
        $("#question_text").text("");
        $("#question_text").text(questions[index].question);
        questionSet = questions[index].question_set;
        //animations
        $("#question_text").finish();
        $("#question_text").css('color', '#FF5722');
        $("#question_text").animate({color: 'black'}, 1500);
        width = index / max * 100;
        $('#progressBar').css('width', width + "%");
        $('#progressbarText').text("Vraag " + index + " van de " + max);
    }
}


