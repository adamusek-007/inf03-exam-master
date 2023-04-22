var form = document.getElementById("myForm");

form.addEventListener("submit", function (event) {
  event.preventDefault();
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "s_insert-questions.php", true);
  xhr.send(new FormData(form));
  form.reset();
});

