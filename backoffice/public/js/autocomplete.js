function remove_element(ele) {
    if (ele != null) {
        ele.parentNode.removeChild(ele)
        ele = null;
    }
    return null;
}

function autocomplete_activate(input_field) {
    divCreate = document.createElement("DIV");
    divCreate.setAttribute("id", input_field.id + "-autocomplete-list");
    divCreate.setAttribute("class", "autocomplete-items");
    input_field.parentNode.appendChild(divCreate);

    return divCreate;
}

function autocomplete_fill(autocomplete_div, suggestions, input_len, callback_autocompleted) {
    for (i = 0; i < suggestions.length; i++) {
        b = document.createElement("DIV");
        b.innerHTML = "<strong>" + suggestions[i].substr(0, input_len) + "</strong>";
        b.innerHTML += suggestions[i].substr(input_len);
        b.innerHTML += "<input type='hidden' value=\"" + suggestions[i] + "\">";
        b.addEventListener("click", function (e) {
            callback_autocompleted(this.getElementsByTagName("input")[0].value);
        });
        divCreate.appendChild(b);
    }
}

function autocomplete_clear(autocomplete_div) {
    var child = autocomplete_div.lastElementChild;
    while (child) {
        autocomplete_div.removeChild(child);
        child = autocomplete_div.lastElementChild;
    }
}

document.addEventListener("DOMContentLoaded", function (event) {
    // Create new div so that input accueil_Espece is alone in the div
    divCreate = document.createElement("DIV");
    divCreate.setAttribute("class", "autocomplete");

    document.getElementById("accueil_Espece").parentNode.appendChild(divCreate);

    divCreate.appendChild(document.getElementById("accueil_Espece"));

    //do work
    var elem = document.getElementById("accueil_Espece");

    var old_value = elem.value;

    var autocomplete_div = null;

    elem.addEventListener("keyup", function (event) {
        // If value has changed...
        if (old_value != elem.value) {
            // Updated stored value
            old_value = elem.value;

            // Check if text is 3 letters
            if (elem.value.length >= 3) {
                // Autocomplete
                const domain = window.location.origin;

                var request = new XMLHttpRequest();
                request.open('GET', '/api/espece/' + elem.value, true);

                request.onload = function () {
                    if (this.status >= 200 && this.status < 400) {
                        // Success!
                        var data = JSON.parse(this.response);

                        if (autocomplete_div == null) {
                            autocomplete_div = autocomplete_activate(elem);
                        } else {
                            autocomplete_clear(autocomplete_div);
                        }

                        autocomplete_fill(autocomplete_div, data, elem.value.length, function (suggestion) {
                            elem.value = suggestion;
                            autocomplete_div = remove_element(autocomplete_div);
                        });
                    } else {
                        // We reached our target server, but it returned an error

                    }
                };

                request.onerror = function () {
                    // There was a connection error of some sort
                };

                request.send();
            } else {
                autocomplete_div = remove_element(autocomplete_div);
            }
        }
    });
});//https://www.w3schools.com/howto/howto_js_autocomplete.asp