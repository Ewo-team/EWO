function autologin() {
    "use strict";
    // Si le localStorage est pris en compte
    if (localStorage) {
        //Vérifier si l'auto-login est activé
        if (localStorage.autologin === "active") {
            var ticket = localStorage.loginticket,
			login = localStorage.login,
			urlConnection;
                urlConnection = url + "/compte/connexion/connexion.php?login=" + login + "&ticket=" + ticket;

            // charger la page de login en envoyant le ticket
            document.location = urlConnection;
        } else {
            if (localStorage.length > 0) {
                // l'auto-login n'est pas activé, on vide donc le localStorage
                removeLogin();
            }
        }
    }
}

function addLogin(ticket, login) {
    "use strict";
    // Ajout du ticket, et indication d'utiliser l'auto-login
    localStorage.autologin = "active";
    localStorage.loginticket = ticket;
    localStorage.login = login;
}

function removeLogin() {
    "use strict";
    // L'utilisateur quitte la page, on supprime le localStorage
    localStorage.clear();
}