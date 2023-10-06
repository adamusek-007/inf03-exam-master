$(document).ready(function () {
  $("#form").on("submit", function (event) {
    event.preventDefault();
    if (validateIncomingData()) {
      makeAjaxRequest(new FormData(this));
    } else {
      alert("Wymagane pola nie są wypełnione.");
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
    if (response.status === "success") {
      displayDialog("Pomyślnie dodano do bazy.");
      resetForm();
    } else if (response.status === "error"){
      displayDialog(response.message);
    }else {
      alert(response.status);
    }
  }

  function resetForm() {
    $("#form")[0].reset();
  }

  function displayDialog(dialogContent) {
    $("#message").text(dialogContent);
    setTimeout(() => {
      $("#message").text("");
    }, 1500);
  }

  function validateIncomingData() {
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

  function handleAjaxError(error) {
    alert("There was an error: " + error.statusText);
  }
});
