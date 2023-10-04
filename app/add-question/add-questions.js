$(document).ready(function () {
  submitHandler();
});

function submitHandler() {
  $("#form").on("submit", (event) => {
    event.preventDefault();
    if (validateIncomingData()) {
      makeAjaxRequest(new FormData(this));
    } else {
      alert("Wymagane pola nie są wypełnione.");
    }
  })
}

function makeAjaxRequest(formData) {
  $.ajax({
    type: "POST",
    url: "s-insert-questions.php",
    data: formData,
    processData: false,
    contentType: false,
    success: (response) => { handleAjaxResponse(response) },
    error: (error) => { alert(error); }
  });
}

function handleAjaxResponse(response) {
  if (response == 0) {
    displaySuccessDialog();
    resetForm();
  } else {
    alert(response);
  }
}

function resetForm(){
  $("#form")[0].reset();
}

function displaySuccessDialog() {
  $("#message").text("Pomyślnie dodano do bazy.");
  setTimeout(() => { $("#message").text("") }, 1500)
}

function validateIncomingData() {
  $(document).find("textarea").each(function () {
    if ($(this).val() == "") {
      return false;
    }
  });
}