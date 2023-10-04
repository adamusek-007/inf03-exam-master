function checkAndSubmit(userReplyAnswer) {
    $.ajax({
        type: "POST",
        url: "s-update-question-data.php",
        data: { user_reply_answer: userReplyAnswer },
        success: (response) => { handleAjaxResponse(response, userReplyAnswer) },
        error: (error) => { alert(error) }
    });
}
function handleAjaxResponse(response, userReplyAnswer) {
    var is_correct = getReplyCorrectness(response);
    if (is_correct == 1) {
        replySuccess(userReplyAnswer);
    } else {
        replyFailure(response, userReplyAnswer)
    }
}
function replySuccess(userReplyAnswer) {
    setElementBackground(userReplyAnswer, "green");
    submitForm(1000);
}

function replyFailure(response, userReplyAnswer) {
    setElementBackground(userReplyAnswer, "red");
    var correct_answer = response.substr(1);
    setElementBackground(correct_answer, "green");
    submitForm(1500);
}

function submitForm(timeout) {
    setTimeout(() => { $("#form").submit() }, timeout);
}

function setElementBackground(value, color) {
    var element = document.querySelector("input[value='" + value + "'");
    element.style.backgroundColor = color;
}

function getReplyCorrectness(response) {
    return response.substr(0, 1);
}