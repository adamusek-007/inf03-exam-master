$(document).ready(function () {
  $("#form").on("submit", function (event) {
    event.preventDefault();

    var allFieldsFilled = true;

    $(this).find(":input[required]").each(function () {
      if ($(this).val() === "") {
        allFieldsFilled = false;
        return false;
      }
    });

    if (allFieldsFilled) {
      var formData = new FormData(this);

      $.ajax({
        type: "POST",
        url: "s_insert-questions.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          console.log("AJAX request complete");
          console.log(response);
          $("#form")[0].reset();
        },
        error: function (error) {
          console.log("AJAX request error");
          console.log(error);
        }
      });
    } else {
      alert ("Please fill out all required fields before submitting.");
    }
  })
});

