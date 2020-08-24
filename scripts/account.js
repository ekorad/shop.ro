var oldNameFieldValue = "";

function onAccountInputFocus(elem) {
    var statusSpans = document.getElementById("main-content")
            .getElementsByClassName("status-span");
    var crtForm = elem.form;
    var crtStatusSpan = crtForm.getElementsByClassName("statusSpan")[0];
    var i;
    for (i = 0; i < statusSpans.length; i++) {
        if (crtStatusSpan !== statusSpans[i]) {
            statusSpans[i].innerHTML = "";
        }
    }
}

function nameEditButtonAction() {
    var nameField = document.getElementById("nameField");
    nameField.disabled = false;
    nameField.focus();

    // for storing the old value and placing cursor at 
    // the end of the input after focus:
    oldNameFieldValue = nameField.value;
    nameField.value = "";
    nameField.value = oldNameFieldValue;
}

function onNameFieldBlur() {
    var form = document.getElementById("nameForm");
    var tempBtn = document.createElement("input");
    tempBtn.style.display = "none";
    tempBtn.type = "submit";
    form.appendChild(tempBtn).click();
    form.removeChild(tempBtn);
    document.getElementById("nameField").disabled = true;
}

function nameFormSubmitAction() {
    var nameField = document.getElementById("nameField");
    var nameText = nameField.value;
    var statusSpan = document.getElementById("nameForm")
            .getElementsByClassName("status-span")[0];

    if (nameText.length === 0) {
        statusSpan.innerHTML = "Câmpul nu poate fi gol!";
        nameField.value = oldNameFieldValue;
        return false;
    }

    if (!validateName(nameText)) {
        statusSpan.innerHTML = "Numele nu poate conține decât litere!";
        return false;
    }

    if (nameText === oldNameFieldValue) {
        return false;
    }

    nameText = nameText.trim();
    nameText = nameText.replace(/\s{2,}/g, ' ');

    nameField.value = nameText;

    return true;
}

function emailEditButtonAction() {
    var expandableDiv = document.getElementById("emailForm")
            .getElementsByClassName("expandable")[0];
    if (expandableDiv.style.maxHeight !== expandableDiv.scrollHeight + "px") {
        expandableDiv.style.maxHeight = expandableDiv.scrollHeight + "px";
    } else {
        expandableDiv.style.maxHeight = "0px";
    }
}

function emailFormSubmitAction() {
    var emailField = document.getElementById("newEmailField");
    var emailText = emailField.value;
    var statusSpan = document.getElementById("emailForm")
            .getElementsByClassName("status-span")[0];

            

    if (emailText.length === 0) {
        statusSpan.innerHTML = "Câmpul nu poate fi gol!";
        return false;
    }

    if (!validateEmail(emailText)) {
        statusSpan.innerHTML = "Email-ul introdus este invalid!";
        return false;
    }
    
    return true;
}