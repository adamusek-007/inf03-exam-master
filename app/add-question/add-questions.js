const form = document.querySelector("form");

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
  const fields = form.querySelectorAll("textarea");
  for (const field of fields) {
    if (field.value === "") {
      return false;
    }
  }
  return true;
}

function displayMessage(status, messageContent) {
  const messageBox = document.querySelector("#message-box");

  if (messageBox !== null) {
    const className = "message-" + status;
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
  const xhttp = new XMLHttpRequest();
  const type = "POST";
  const url = "question-add-controller.php";
  xhttp.open(type, url, true);

  xhttp.onreadystatechange = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        handleAjaxResponse(this.response);
      } else {
        handleAjaxError(this.statusText);
      }
    }
  };

  xhttp.send(formData);
}

function handleAjaxError(error) {
  displayMessage("error", "There was an error: " + error);
}

function handleAjaxResponse(textResponse) {
  const response = JSON.parse(textResponse);
  displayMessage(response.status, response.message);

  if (response.status) {
    resetForm();
  }
}

function resetForm() {
  const form = document.querySelector("form");

  if (form !== null) {
    form.reset();
  }
}
