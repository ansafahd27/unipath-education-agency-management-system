function CheckForm(form) {
  // Clear previous errors
  var errors = form.querySelectorAll(".alert-danger");
  errors.forEach(function (el) {
    el.remove();
  });

  // Reset styles
  var inputs = form.querySelectorAll("input, textarea, select");
  inputs.forEach(function (el) {
    el.style.borderColor = "";
  });

  var isValid = true;

  for (var i = 0; i < inputs.length; i++) {
    var input = inputs[i];
    if (input.type === "submit" || input.type === "button") continue;

    var value = input.value.trim();
    var errorMsg = "";

    if (value === "") {
      errorMsg = "This field is required.";
    } else if (value !== "" && input.type === "email") {
      var emailPattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      if (!emailPattern.test(value)) {
        errorMsg = "Invalid email format.";
      }
    }
    // 3. Check Number Format
    else if (value !== "" && input.type === "number") {
      if (isNaN(value)) {
        errorMsg = "Please enter a valid number.";
      }
    }

    if (errorMsg !== "") {
      isValid = false;

      var errorDiv = document.createElement("div");
      errorDiv.className = "alert-danger";
      errorDiv.style.color = "#dc3545";
      errorDiv.style.padding = "5px 0";
      errorDiv.style.marginTop = "4px";
      errorDiv.style.fontSize = "0.875rem";
      errorDiv.innerText = errorMsg;

      if (input.nextSibling) {
        input.parentNode.insertBefore(errorDiv, input.nextSibling);
      } else {
        input.parentNode.appendChild(errorDiv);
      }

      input.style.borderColor = "#dc3545";
    }
  }

  return isValid;
}
