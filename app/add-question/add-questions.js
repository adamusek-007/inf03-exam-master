$(document).ready(function () {
  $("#form").on("submit", function (event) {
    event.preventDefault();
    if (validateRequiredFieldsData()) {
      makeAjaxRequest(new FormData(this));
    } else {
      displayMessage("error", "Wymagane pola nie są wypełnione.");
    }
  });

  function makeAjaxRequest(formData) {
    $.ajax({
      type: "POST",
      url: "s-insert-questions.php",
      data: formData,
      processData: false,
      contentType: false,
      success: handleAjaxResponse,
      error: handleAjaxError,
    });
  }

  function handleAjaxResponse(textResponse) {
    response = JSON.parse(textResponse);
    displayMessage(response.status,response.message);
    if(response.status = "success") {
      resetForm();
    }
  }

  function handleAjaxError(error) {
    displayMessage("error", "There was an error: " + error.statusText);
  }

  function resetForm() {
    $("#form")[0].reset();
  }

  function displayMessage(status, messageContent) {
    
    $("#message-box").addClass("message-"+status);
    $("#message-box").text(messageContent)
    setTimeout(clearMessageBox, 1500);
  }

  function clearMessageBox(){
    $("#message-box").text("");
    $("#message-box").removeClass();
  }

  function validateRequiredFieldsData() {
    let isValid = true;
    $(document)
      .find("textarea")
      .each(function () {
        if ($(this).val() === "") {
          isValid = false;
          return false;
        }
      });
    return isValid;
  }
});
