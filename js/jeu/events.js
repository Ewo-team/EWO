"use strict";

function showTarget(event, info, nb, mat) {
    if (document.getElementById('subev' + event) === null) {
        var xhr = getXhr();
        // On défini ce qu'on va faire quand on aura la réponse
        xhr.onreadystatechange = function() {
            // On ne fait quelque chose que si on a tout reçu et que le serveur est ok
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText !== 'null' && xhr.responseText !== 'aucun') {
                    var tbody = document.getElementById('tbody_event');
                    var row = document.getElementById('event' + event);
                    for (var j = 0; j < row.cells.length; j++) {
                        row.cells[j].className = "bottom";
                    }
                    if (document.getElementById('subev' + event) === null) {
                        var jsonobjet = xhr.responseText;
                        var donnees = JSON.parse(jsonobjet);
                        for (var i = 1; i <= nb; i++) {
                            var sub = tbody.insertRow(row.rowIndex + i);
                            sub.id = "subev" + event;
                            sub.style.backgroundColor = donnees.bg[i - 1];
                            sub.innerHTML = donnees.ct[i - 1];
                            if (i === nb) {
                                for (j = 0; j < sub.cells.length; j++) {
                                    sub.cells[j].className = "bottom";
                                }
                            }
                        }
                        tbody.rows[row.rowIndex].cells[4].firstChild.innerHTML = "Masquer";
                        for (j = (1 + row.rowIndex); j <= (nb + row.rowIndex); j++) {
                            tbody.rows[j].cells[tbody.rows[j].cells.length - 1].className += "left";
                        }
                    }
                }
            }
        };
        xhr.open("GET", "../event/subEvent.php?key=" + encodeURIComponent(info) + "&mat=" + mat, true);
        xhr.send(null);
    } else {
        var tbody = document.getElementById('tbody_event');
        var j, row = document.getElementById('event' + event);
        for (var i = 1; i <= nb; i++) {
            if (tbody.rows[row.rowIndex + i].style.display === 'none') {
                tbody.rows[row.rowIndex].cells[4].firstChild.innerHTML = "Masquer";
                try {
                    tbody.rows[row.rowIndex + i].style.display = 'table-row';
                } catch (e) {
                    tbody.rows[row.rowIndex + i].style.display = 'block';
                }
                for (j = 0; j < row.cells.length; j++) {
                    row.cells[j].className = "bottom";
                }
            } else {
                tbody.rows[row.rowIndex].cells[4].firstChild.innerHTML = "Afficher";
                tbody.rows[row.rowIndex + i].style.display = 'none';
                for (j = 0; j < row.cells.length; j++) {
                    row.cells[j].className = "";
                }
            }
        }
        row.focus();
    }
}

function loadContentFrom(divID, pageURL) {
    if (document.getElementById(divID).innerHTML.length === 0) {
        var xhr = getXhr();
        xhr.onreadystatechange = function() {
            // On ne fait quelque chose que si on a tout reçu et que le serveur est ok
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText !== 'null' && xhr.responseText !== 'aucun') {
                    if (document.getElementById(divID) !== null) {
                        var html = xhr.responseText;
                        document.getElementById(divID).innerHTML = html;
                    }
                }
            }
        };
        xhr.open("GET", pageURL, true);
        xhr.send(null);
    }
}
jQuery(document).ready(function() {
    var url = document.URL;
    var position = url.lastIndexOf("#");
    jQuery(".conteneur_titre").bind("click", function(event) {
        var id = jQuery(this).attr("id");
        jQuery(".conteneur_corps").css("display", "none");
        jQuery("#page" + id).css("display", "block");
        if (id !== -1) {
            changeTitre(id, nomPerso);
        }
    });
    if (position >= 1) {
        var longueur = url.length;
        if (longueur > 1) {
            if (url.substring(position + 1, longueur) === "CV") {
                jQuery("#CV").click();
            } else if (url.substring(position + 1, longueur) === "Profil") {
                jQuery("#Profil").click();
            }
        }
    }
    changeTitre("Event", nomPerso);

    function changeTitre(id, perso) {
        if (id !== -1) {
            document.title = ("EWO: " + id + " de " + perso);
        }
    }
});