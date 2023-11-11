class FormHandler {
  constructor(formSelector, messageBoxSelector) {
    this.form = document.querySelector(formSelector);
    this.messageBox = document.querySelector(messageBoxSelector);

    if (this.form !== null) {
      this.form.addEventListener("submit", (event) => {
        event.preventDefault();
        this.handleFormSubmit();
      });
    }
  }

  handleFormSubmit() {
    if (this.validateRequiredFieldsData()) {
      this.makeAjaxRequest(new FormData(this.form));
    } else {
      this.displayMessage("error", "Wymagane pola nie są wypełnione.");
    }
  }

  validateRequiredFieldsData() {
    const fields = this.form.querySelectorAll("textarea");
    for (const field of fields) {
      if (field.value === "") {
        return false;
      }
    }
    return true;
  }

  displayMessage(status, messageContent) {
    if (this.messageBox !== null) {
      const className = "message-" + status;
      this.messageBox.classList.add(className);
      this.messageBox.innerHTML = messageContent;

      setTimeout(() => {
        this.clearMessageBox(className);
      }, 1500);
    }
  }

  clearMessageBox(className) {
    this.messageBox.innerHTML = "";
    this.messageBox.classList.remove(className);
  }

  makeAjaxRequest(formData) {
    const ajaxRequest = new AjaxRequest();
    ajaxRequest.send(
      formData,
      (response) => this.handleAjaxResponse(response),
      (error) => this.handleAjaxError(error)
    );
  }

  handleAjaxError(error) {
    this.displayMessage("error", "There was an error: " + error);
  }

  handleAjaxResponse(textResponse) {
    const response = JSON.parse(textResponse);
    this.displayMessage(response.status, response.message);

    if (response.status) {
      this.resetForm();
    }
  }

  resetForm() {
    if (this.form !== null) {
      this.form.reset();
    }
  }
}

class AjaxRequest {
  send(formData, successCallback, errorCallback) {
    const xhttp = new XMLHttpRequest();
    const type = "POST";
    const url = "question-add-controller.php";

    xhttp.open(type, url, true);

    xhttp.onreadystatechange = function () {
      if (this.readyState == 4) {
        if (this.status == 200) {
          successCallback(this.response);
        } else {
          errorCallback(this.statusText);
        }
      }
    };

    xhttp.send(formData);
  }
}

const formHandler = new FormHandler("form", "#message-box");
