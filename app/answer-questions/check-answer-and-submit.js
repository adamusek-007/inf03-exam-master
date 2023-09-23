$('#myForm').submit(function (event) {
    event.preventDefault();
});

function checkAndSubmit(userAnswer) {
    $.ajax({
        type: "POST",
        url: "s-update-question-data.php",
        data: { usr_respo: userAnswer },
        success: function (response) {
            var is_correct = response.substr(0,1);
            console.log(is_correct);
            if (is_correct == 0) {
                var correct_answer = response.substr(1);
                var incorrectElement = document.querySelector("input[value='" + userAnswer + "'");
                incorrectElement.style.backgroundColor = "red";
                var correctElement = document.querySelector("input[value='" + correct_answer + "'");
                correctElement.style.backgroundColor = "green";
                setTimeout( () => {$("form").submit()}, 1500);
            } else if (is_correct == 1) {
                var correctElement = document.querySelector("input[value='" + userAnswer + "'");
                correctElement.style.backgroundColor = "green";
                setTimeout( () => {$("form").submit()}, 1000);
            }
            // TODO response codes and marking correct answers (MySQL and JS);
        },
        error: function (error) {
            console.log(error);
        }
    });
}