function onSearchSubmit() {
    
}

function onFieldFocus() {
    var searchElemContainer = document.getElementById("searchField").parentNode;
    searchElemContainer.classList.toggle("focused");    
}

function expandAction() {
    var button = document.getElementById("adv-search-button").childNodes[1];
    var content = document.getElementById("adv-search-content");
    if (button.classList.contains("toggled")) {
        content.style.maxHeight = "10px";
    } else {
        content.style.maxHeight = content.scrollHeight + "px";
    }
    button.classList.toggle("toggled");
}