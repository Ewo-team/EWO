"use strict";

function switchCommande(id) {
    var obj = document.getElementById(id);
    var obj2 = document.getElementById(id + "_act");
    if (obj2) {
        if (obj.style.display === "none") {
            obj2.innerHTML = " - ";
        } else {
            obj2.innerHTML = " + ";
        }
    }
}

function switchTr(id) {
    var obj = document.getElementById(id);
    if (obj) {
        if (obj.style.display === "none") {
            obj.style.display = "table-row";
        } else {
            obj.style.display = "none";
        }
    }
}

function switchVisu(id) {
    switchCommande(id);
    switchTr(id);
}

function switchVisu2(id1, id2) {
    switchCommande(id1);
    switchTr(id1);
    switchTr(id2);
}
var switchComVar = 0;

function switchAllCommande(id) {
    var obj = document.getElementById(id);
    if (obj) {
        if (switchComVar % 2 === 0) {
            obj.innerHTML = " Tout cacher ";
        } else {
            obj.innerHTML = " Tout afficher ";
        }
        switchComVar++;
    }
}

function affCommande(id) {
    var obj2 = document.getElementById(id + "_act");
    if (obj2) {
        obj2.innerHTML = " - ";
    }
}

function affTr(id) {
    var obj = document.getElementById(id);
    if (obj) {
        obj.style.display = "table-row";
    }
}

function cacheCommande(id) {
    var obj2 = document.getElementById(id + "_act");
    if (obj2) {
        obj2.innerHTML = " + ";
    }
}

function cacheTr(id) {
    var obj = document.getElementById(id);
    if (obj) {
        obj.style.display = "none";
    }
}

function switchAll(tblId, idCommande) {
    var tbl_r = [];
    var elem = tblId.shift();
    while (elem) {
        tbl_r.push(elem);
        if (switchComVar % 2 === 0) { //On affiche
            affTr(elem);
            affCommande(elem);
        } else {
            cacheTr(elem);
            cacheCommande(elem);
        }
        elem = tblId.shift();
    }
    switchAllCommande(idCommande);
}

function switchAll2(tblId1, tblId2, idCommande) {
    var elem1 = tblId1.shift();
    var elem2 = tblId2.shift();
    while (elem1 && elem2) {
        if (switchComVar % 2 === 0) { //On affiche
            affTr(elem1);
            affTr(elem2);
            affCommande(elem1);
        } else {
            cacheTr(elem1);
            cacheTr(elem2);
            cacheCommande(elem1);
        }
        elem1 = tblId1.shift();
        elem2 = tblId2.shift();
    }
    switchAllCommande(idCommande);
}