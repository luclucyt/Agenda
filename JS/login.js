let toggle = document.getElementsByClassName("login-toggle")[0];

let LoginIsOpen = true; // true = login is open, false = register is open

toggle.addEventListener("click", function() {
    if(LoginIsOpen) {

        document.getElementsByClassName("login-wrapper")[0].style.height = "0px";
        document.getElementsByClassName("signup-wrapper")[0].style.height = "100%";

        toggle.innerHTML = "Heb je al een account, log hier in.";

        LoginIsOpen = false;
    }
    else {
        document.getElementsByClassName("login-wrapper")[0].style.height = "100%";
        document.getElementsByClassName("signup-wrapper")[0].style.height = "0px";

        toggle.innerHTML = "Nog geen account, maak er een aan.";

        LoginIsOpen = true;
    }
});

document.getElementsByClassName("login-wrapper")[0].style.height = "100%";
document.getElementsByClassName("signup-wrapper")[0].style.height = "0px";

toggle.innerHTML = "Nog geen account, maak er een aan.";