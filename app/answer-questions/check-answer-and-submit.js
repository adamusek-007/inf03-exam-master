$(".submit").on("click", function () {
    checkAndSubmit(this.value);
})

function checkAndSubmit(userReplyAnswer) {
    $.ajax({
        type: "POST",
        url: "s-update-question-data.php",
        data: { user_reply_answer: userReplyAnswer },
        success: handleAjaxResponse,
        error: (error) => console.log(error)
    });
}

function handleAjaxResponse(textResponse) {
    const response = JSON.parse(textResponse);
    const replyCorrectness = response.reply_correctness;

    setElementBackground(response.user_reply_answer, replyCorrectness ? 'green' : 'red');
    if (!replyCorrectness) {
        setElementBackground(response.correct_answer, 'green');
    }

    const delay = replyCorrectness ? 1000 : 1500;
    setTimeout(() => { $("#form").submit() }, delay);
}

function setElementBackground(value, color) {
    var element = document.querySelector(`input[value='${value}'`);
    element.style.backgroundColor = color;
}