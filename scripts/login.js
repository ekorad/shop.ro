function onLoginSubmit() {
    var tooltips = document.getElementsByClassName("tooltip");
    var i;
    for (i = 0; i < tooltips.length; i++) {
        if (tooltips[i].classList.contains("toggled")) {
            tooltips[i].classList.remove("toggled");
        }
    }

    var email = document.getElementById("emailField").value;
    var password = document.getElementById("passwordField").value;
    var statusSpan = document.getElementById("status");

    statusSpan.innerHTML = "";
    if (email.length === 0 || password.length === 0) {
        statusSpan.innerHTML = "Toate câmpurile trebuie completate!";
        return false;
    }

    if (!validateEmail(email)) {
        var tooltip = document.getElementById("emailField").nextSibling
                .nextSibling;
        if (!tooltip.classList.contains("toggled")) {
            tooltip.classList.toggle("toggled");
        }
        return false;
    }

    return true;
}

function validateEmail(email) {
    const pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!pattern.test(String(email).toLowerCase())) {
        return false;
    }
    return true;
}

function onInputClick(elem) {
    var tooltip = elem.nextSibling.nextSibling;
    if (tooltip.classList.contains("toggled")) {
        tooltip.classList.remove("toggled");
    }
}