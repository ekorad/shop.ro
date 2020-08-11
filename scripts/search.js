function onSearchSubmit() {
    
}

function onFieldFocus() {
    var searchElemContainer = document.getElementById("searchField").parentNode;
    searchElemContainer.classList.toggle("focused");    
}

function navExpandAction(btn) {
    if (window.innerWidth > 1300) {
        return;
    }
    var expandableDiv = btn.nextSibling.nextSibling;
    if (btn.classList.contains("toggled")) {
        expandableDiv.style.maxHeight = "0px";
    } else {
        expandableDiv.style.maxHeight = expandableDiv.scrollHeight + "px";
    }
    btn.classList.toggle("toggled");
}

function mobileMenuButtonAction() {
    var button = document.getElementById("mobile-menu-button");
    var menu = document.getElementById("nav");
    var expandBtns = document.getElementsByClassName("btn-expand");
    var overlay = document.getElementById("overlay");
    menu.classList.toggle("expanded");
    overlay.classList.toggle("expanded");
    if (!menu.classList.contains("expanded")) {
        var i;
        for (i = 0; i < expandBtns.length; i++) {
            if (expandBtns[i].classList.contains("toggled")) {
                navExpandAction(expandBtns[i]);
            }
        }
    }    
}