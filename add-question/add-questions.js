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
      var formData = $(this).serialize();

      $.ajax({
        type: "POST",
        url: "s_insert-questions.php",
        data: formData,
        success: function (response) {
          console.log("AJAX request complete");
          console.log(response);
          $("#form").reset();
        },
        error: function (error) {
          console.log("AJAX request error");
          console.log(error);
        }
      });
    }
  })
});

