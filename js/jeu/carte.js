"use strict";

function layer_carte(id_layer) {
    var layer = document.getElementById(id_layer).style.display;
    if (layer === 'none') {
        document.getElementById(id_layer).style.display = "block";
    } else {
        document.getElementById(id_layer).style.display = "none";
    }
}

function layer_carte_all(id_layer) {
    document.getElementById(id_layer).style.display = "block";
}

function layer_all(carte) {
    layer_carte_all('carte_' + carte + '_grille');
    layer_carte_all('carte_' + carte + '_viseur');
    layer_carte_all('carte_' + carte + '_portes');
    layer_carte_all('carte_' + carte + '_boucliers');
    layer_carte_all('carte_' + carte + '_R1G0');
    layer_carte_all('carte_' + carte + '_R1G4');
    layer_carte_all('carte_' + carte + '_R1G5');
    layer_carte_all('carte_' + carte + '_R4G0');
    layer_carte_all('carte_' + carte + '_R4G4');
    layer_carte_all('carte_' + carte + '_R4G5');
    layer_carte_all('carte_' + carte + '_R3G0');
    layer_carte_all('carte_' + carte + '_R3G4');
    layer_carte_all('carte_' + carte + '_R3G5');
    layer_carte_all('carte_' + carte + '_R2G0');
    layer_carte_all('carte_' + carte + '_R2G4');
    layer_carte_all('carte_' + carte + '_R2G5');
} /* Ajout Coordonnées des cartes */

function isChrome() {
    var data = [{
        string: navigator.userAgent,
        subString: "Chrome"
    }];
    for (var i = 0; i < data.length; i++) {
        var dataString = data[i].string;
        var dataProp = data[i].prop;
        if (dataString) {
            if (dataString.indexOf(data[i].subString) !== -1) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function viewportOff(el) {
    var valueT = 0,
        valueL = 0,
        docBody = document.body;
    var element = el;
    do {
        valueT += element.offsetTop || 0;
        valueL += element.offsetLeft || 0;
        if (element.offsetParent === docBody && element.style.position === 'absolute') {
            break;
        }
    } while (element = element.offsetParent);
    if (!isChrome()) {
        element = el;
        do {
            if (element !== docBody) {
                valueT -= element.scrollTop || 0;
                valueL -= element.scrollLeft || 0;
            }
        } while (element = element.parentNode);
    } else {
        valueL = valueL - document.body.scrollLeft;
        valueT = valueT - document.body.scrollTop;
    }
    return {
        'left': Math.round(valueL),
        'top': Math.round(valueT)
    };
}

function findPos(el) {
    if (el.offsetParent) {
        var coord = viewportOff(el);
        return {
            'x': coord.left,
            'y': coord.top
        };
    }
    return false;
}

function razStatut() {
    if (document.getElementById(this.id + '_coord')) {
        document.getElementById(this.id + '_coord').style.visibility = 'hidden';
    }
}

function setRes() {
    if (document.getElementById(arguments[0] + '_coord') && document.getElementById(arguments[0] + '_coordX') && document.getElementById(arguments[0] + '_coordY')) {
        document.getElementById(arguments[0] + '_coord').style.visibility = 'visible';
        document.getElementById(arguments[0] + '_coordX').innerHTML = arguments[1];
        document.getElementById(arguments[0] + '_coordY').innerHTML = arguments[2];
    }
}

function getParamPlan(el) {
    switch (el) {
    case 'fond_carte_terre':
        return {
            'rx': Math.floor(arguments[1] / 2.33 - 150),
            'ry': Math.floor(arguments[2] / 1.33 - 150)
        };
    case 'fond_carte_paradis':
        return {
            'rx': Math.floor(arguments[1] / 4 - 50),
            'ry': Math.floor(arguments[2] / 4 - 100)
        };
    case 'fond_carte_enfer':
        return {
            'rx': Math.floor(arguments[1] / 4 - 50),
            'ry': Math.floor(arguments[2] / 4)
        };
    }
}

function setCoordPlan(e) {
    var ev = e || window.event;
    var pos = findPos(this);
    var diffx = ev.clientX - pos.x;
    var diffy = ev.clientY - pos.y;
    var calc = getParamPlan(this.id, diffx, diffy);
    calc.ry < 0 ? calc.ry = Math.abs(calc.ry) : calc.ry = '-' + calc.ry;
    setRes(this.id, calc.rx, calc.ry);
}
if (document.getElementById('fond_carte_terre')) {
    var dct = document.getElementById('fond_carte_terre');
    dct.onmousemove = setCoordPlan;
    dct.onmouseout = razStatut;
}
if (document.getElementById('fond_carte_paradis')) {
    var dcp = document.getElementById('fond_carte_paradis');
    dcp.onmousemove = setCoordPlan;
    dcp.onmouseout = razStatut;
}
if (document.getElementById('fond_carte_enfer')) {
    var dce = document.getElementById('fond_carte_enfer');
    dce.onmousemove = setCoordPlan;
    dce.onmouseout = razStatut;
}