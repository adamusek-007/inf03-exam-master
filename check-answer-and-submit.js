var form = document.getElementById("form");
form.addEventListener("submit", function(event) {
    event.preventDefault();
});
document.getElementById("question-details").open = true;

function checkAndSubmit(userAnswerElement) {
    var userAnswer = userAnswerElement.value;
    var correctAnswer = document.getElementById("correct-answer").value;
    var correctAnswerButtonXPath = "//input[@value=\""+ correctAnswer + "\"]";
    var correctButton = document.evaluate(correctAnswerButtonXPath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
    var questionId = document.getElementById("q-id-input").value;
    var xhr = new XMLHttpRequest();
        if (userAnswer == correctAnswer) {
            userAnswerElement.style.backgroundColor = "green";
            setTimeout(() => {  form.submit(); }, 1500);
        } else {
            userAnswerElement.style.backgroundColor = "#820000";
            userAnswerElement.classList.add("opacity");
            correctButton.style.backgroundColor = "green";
            setTimeout(() => {  form.submit(); }, 2000);
        }
    xhr.open("GET", "s_update_question_data.php?answer=" + userAnswer + "&q-id=" + questionId + "&c-answer=" + correctAnswer, true);
    xhr.send();
}