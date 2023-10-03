$(document).ready(function () {
  $("#form").on("submit", function (event) {
    event.preventDefault();
    if (validateIncomingData()) {
      makeAjaxRequest(new FormData(this));
    } else {
      alert("Please fill out all required fields before submitting.");
    }
  })
});

function makeAjaxRequest(formData) {
  $.ajax({
    type: "POST",
    url: "s-insert-questions.php",
    data: formData,
    processData: false,
    contentType: false,
    success: (response)=> { handleAjaxResponse(response)},
    error: (error)=>{handleAjaxError(error)}
  });
}

function handleAjaxResponse(response) {
  if (response == 0) {
    $("#form")[0].reset();
  } else {
    alert(response);
    console.log(response);
  }
}
function handleAjaxError(error) {
  console.log("AJAX request error:");
  console.log(error);
}


function validateIncomingData() {
  var allFieldsFilled = true;

  $(document).find("textarea").each(function () {
    if ($(this).val() == "") {
      allFieldsFilled = false;
      return false;
    }
  });

  return allFieldsFilled;
}