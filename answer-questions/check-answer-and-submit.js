$('#myForm').submit(function (event) {
    event.preventDefault();
});

function checkAndSubmit(userAnswer) {
    $.ajax({
        type: "POST",
        url: "s_update_question_data.php",
        data: { usr_respo: userAnswer },
        success: function (response) {
            console.log(response);
            if (response === '0') {
                $("#form").submit();
            } 
            // TODO response codes and marking correct answers (MySQL and JS);
        },
        error: function (error) {
            console.log(error);
        }
    });
}