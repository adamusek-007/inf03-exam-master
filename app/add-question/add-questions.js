let form = document.querySelector("form");

if (form !== null) {
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    handleFormSubmit(form);
  });
}

function handleFormSubmit(form) {
  if (validateRequiredFieldsData(form)) {
    makeAjaxRequest(new FormData(form));
  } else {
    displayMessage("error", "Wymagane pola nie są wypełnione.");
  }
}

function validateRequiredFieldsData(form) {
  let isValid = true;
  let fields = form.querySelectorAll("textarea");
  fields.forEach((field) => {
    if (field.value === "") {
      isValid = false;
      return false;
    }
  });
  return isValid;
}

function displayMessage(status, messageContent) {
  let messageBox = document.querySelector("#message-box");
  if (messageBox != null) {
    let className = "message-" + status;
    messageBox.classList.add(className);
    messageBox.innerHTML = messageContent;
    setTimeout(() => {
      clearMessageBox(messageBox, className);
    }, 1500);
  }
}

function clearMessageBox(messageBox, className) {
  messageBox.innerHTML = "";
  messageBox.classList.remove(className);
}

function makeAjaxRequest(formData) {
  let xhttp = new XMLHttpRequest();
  let type = "POST";
  let url = "question-add-controller.php";
  xhttp.open(type, url, true);

  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      handleAjaxResponse(this.response);
    } else {
      handleAjaxError(this.statusText);
    }
  };
  xhttp.send(formData);
}

function handleAjaxError(error) {
  displayMessage("error", "There was an error: " + error);
}

function handleAjaxResponse(textResponse) {
  let response = JSON.parse(textResponse);
  displayMessage(response.status, response.message);
  if (response.status) {
    resetForm();
  }
}

function resetForm() {
  let form = document.querySelector("form");
  if (form != null) form.reset();
}
