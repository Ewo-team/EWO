var pi     = 0;
var piBase = 0;
var race   = 0;
var grade  = 0;
var admin_mode = 0;

var coutPv      = new Array();
	var pvNv           = 0;
	var pvNvBase       = 0;
	var pvValBase      = 0;
	var pvMult         = 0;
var coutRecupPv = new Array();
	var recupPvNv      = 0;
	var recupPvNvBase  = 0;
	var recupPvValBase = 0;
	var recupPvMult    = 0;
var coutPa      = new Array();
	var paNv           = 0;
	var paNvBase       = 0;
	var paValBase      = 0;
	var paMult         = 0;
var coutMouv    = new Array();
	var mouvNv         = 0;
	var mouvNvBase     = 0;
	var mouvValBase    = 0;
	var mouvMult       = 0;
var coutdes    = new Array();
	var desNv          = 0;
	var desNvBase      = 0;
	var desValBase     = 0;
	var desMult        = 0;
var coutForce   = new Array();
	var forceNv        = 0;
	var forceNvBase    = 0;
	var forceValBase   = 0;
	var forceMult      = 0;
var coutPerc    = new Array();
	var percNv         = 0;
	var percNvBase     = 0;
	var percValBase    = 0;
	var percMult       = 0;
var coutNvMag   = new Array();
	var nvMagNv        = 0;
	var nvMagNvBase    = 0;

function cleanPa(paAMod){
	var nbr = 2;
	var s = paAMod;
	while(s/10 > 1){
		s /= 10;
		nbr++;
	}
	return paAMod.toPrecision(nbr);
}

function isArray(obj) {
   if (obj.constructor.toString().indexOf("Array") == -1)
      return false;
   else
      return true;
}

function initCoutPv(tbl,b,vb,m){
	if(isArray(tbl)){
		coutPv    = tbl;
		pvNvBase  = b;
		pvValBase = vb;
		pvMult    = m;
	}
}

function initCoutRecupPv(tbl,b,vb,m){
	if(isArray(tbl)){
		coutRecupPv    = tbl;
		recupPvNvBase  = b;
		recupPvValBase = vb;
		recupPvMult    = m;
	}
}

function initCoutPa(tbl,b,vb,m){
	if(isArray(tbl)){
		coutPa    = tbl;
		paNvBase  = b;
		paValBase = vb;
		paMult    = m;
	}
}

function initCoutMouv(tbl,b,vb,m){
	if(isArray(tbl)){
		coutMouv    = tbl;
		mouvNvBase  = b;
		mouvValBase = vb;
		mouvMult   = m;
	}
}

function initCoutdes(tbl,b,vb,m){
	if(isArray(tbl)){
		coutdes    = tbl;
		desNvBase  = b;
		desValBase = vb;
		desMult    = m;
	}
}

function initCoutForce(tbl,b,vb,m){
	if(isArray(tbl)){
		coutForce    = tbl;
		forceNvBase  = b;
		forceValBase = vb;
		forceMult     = m;
	}
}


function initCoutPerc(tbl,b,vb,m){
	if(isArray(tbl)){
		coutPerc    = tbl;
		percNvBase  = b;
		percValBase = vb;
		percMult    = m;
	}
}

function initCoutNvMag(tbl,b){
	if(isArray(tbl)){
		coutNvMag    = tbl;
		nvMagNvBase  = b;
	}
}

function setPiV(p, admin_mode_){
	pi     = p;
	piBase = p;
	admin_mode = admin_mode_;
}

function setRace(r){
	race = r;
}

function setGrade(g){
	grade = g;
}

function upCar(id){
	if(id == 0){//pv	
		cout = coutPv[pvNv];
		if(cout <= pi){
			document.forms["formUp"].pvUp.value = parseInt(document.forms["formUp"].pvUp.value)+1;
			pi -= cout;
			pvNv++;
			document.getElementById("valPvAff").innerHTML = (pvValBase+pvNvBase*pvMult)+" -> "+(pvValBase + (pvNv+pvNvBase)*pvMult);
			document.getElementById("coutPvAff").innerHTML = coutPv[pvNv];
			if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = pi;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-pi;
					}
		}
	}
	else if(id == 1){//RecupPv	
		cout = coutRecupPv[recupPvNv];
		if(cout <= pi){
			document.forms["formUp"].recupPvUp.value = parseInt(document.forms["formUp"].recupPvUp.value)+1;
			pi -= cout;
			recupPvNv++;

			document.getElementById("valRecupPvAff").innerHTML = (recupPvValBase+recupPvMult*recupPvNvBase)+" -> "+(recupPvValBase+recupPvMult*(recupPvNv+recupPvNvBase));
			document.getElementById("coutRecupPvAff").innerHTML = coutRecupPv[recupPvNv];
			if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = pi;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-pi;
					}
		
			if(recupPvNv+recupPvNvBase >= 5){
				if(recupPvNvBase < 5)
					document.getElementById("upRecupPv").innerHTML = "";
				document.getElementById("coutRecupPvAff").innerHTML = "N/A";
			}
		}
	}
	else if(id == 2){//Mouv	
		cout = coutMouv[mouvNv];
		if(cout <= pi){
			document.forms["formUp"].mouvUp.value = parseInt(document.forms["formUp"].mouvUp.value)+1;
			pi -= cout;
			mouvNv++;

			document.getElementById("valMouvAff").innerHTML = (mouvValBase+mouvMult*mouvNvBase)+" -> "+(mouvValBase+mouvMult*(mouvNv + mouvNvBase));
			document.getElementById("coutMouvAff").innerHTML = coutMouv[mouvNv];
			if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = pi;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-pi;
					}
		}
	}
	else if(id == 3){//Pa
		cout = coutPa[paNv];
		if(cout <= pi){
			document.forms["formUp"].paUp.value = parseInt(document.forms["formUp"].paUp.value)+1;
			pi -= cout;
			paNv++;

			document.getElementById("valPaAff").innerHTML =  cleanPa(paValBase+paMult*paNvBase)+" -> "+cleanPa(paValBase+paMult*(paNv+paNvBase));
			document.getElementById("coutPaAff").innerHTML = coutPa[paNv];
			if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = pi;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-pi;
					}
		}
	}
	else if(id == 4){//des
		cout = coutdes[desNv];
		if(cout <= pi){
			document.forms["formUp"].desUp.value = parseInt(document.forms["formUp"].desUp.value)+1;
			pi -= cout;
			desNv++;

			document.getElementById("valdesAff").innerHTML = (desValBase+desMult*desNvBase)+" -> "+(desValBase+desMult*(desNv + desNvBase));
			document.getElementById("coutdesAff").innerHTML = coutdes[desNv];
			if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = pi;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-pi;
					}
		}
	}
	else if(id == 5){//Force
		cout = coutForce[forceNv];
		if(cout <= pi){
			document.forms["formUp"].forceUp.value = parseInt(document.forms["formUp"].forceUp.value)+1;
			pi -= cout;
			forceNv++;

			document.getElementById("valForceAff").innerHTML = (forceValBase+forceMult*forceNvBase)+" -> "+(forceValBase+forceMult*(forceNv + forceNvBase));
			document.getElementById("coutForceAff").innerHTML = coutForce[forceNv];
			if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = pi;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-pi;
					}
		}
	}
	else if(id == 6){//Perc
		cout = coutPerc[percNv];
		if(cout <= pi){
			document.forms["formUp"].percUp.value = parseInt(document.forms["formUp"].percUp.value)+1;
			pi -= cout;
			percNv++;

			document.getElementById("valPercAff").innerHTML = (percValBase+percMult*percNvBase)+" -> "+(percValBase+percMult*(percNv + percNvBase));
			document.getElementById("coutPercAff").innerHTML = coutPerc[percNv];
			if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = pi;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-pi;
					}
		}
	}
	else if(id == 7){//NvMag
		cout = coutNvMag[nvMagNv];
		if(cout <= pi){
			document.forms["formUp"].nvMagUp.value = parseInt(document.forms["formUp"].nvMagUp.value)+1;
			pi -= cout;
			nvMagNv++;

			document.getElementById("valNvMagAff").innerHTML = (nvMagNvBase)+" -> "+(nvMagNv + nvMagNvBase);
			document.getElementById("coutNvMagAff").innerHTML = coutNvMag[nvMagNv];
			if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = pi;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-pi;
					}

			if(nvMagNv+nvMagNvBase >= 5){
				document.getElementById("upNvMag").innerHTML = "";
				document.getElementById("coutNvMagAff").innerHTML = "N/A";
			}
		}
	}
}


function clearEvo(){
	document.getElementById("valPvAff").innerHTML = pvValBase + pvMult*pvNvBase;
	document.getElementById("coutPvAff").innerHTML = coutPv[0];
	document.forms["formUp"].pvUp.value = 0;
	pvNv = 0;

	document.getElementById("valRecupPvAff").innerHTML = recupPvValBase + recupPvMult*recupPvNvBase;
	document.forms["formUp"].recupPvUp.value = 0;
	recupPvNv = 0;

	if(recupPvNv+recupPvNvBase >= 4){
		document.getElementById("upRecupPv").innerHTML = "";
		document.getElementById("coutRecupPvAff").innerHTML = "N/A";
	}
	else{
		document.getElementById("coutRecupPvAff").innerHTML = coutRecupPv[0];
		document.getElementById("upRecupPv").innerHTML = '<input type="button" value="+" onclick="upCar(1);"/>';
	}

	document.getElementById("valMouvAff").innerHTML = mouvValBase + mouvNvBase;
	document.getElementById("coutMouvAff").innerHTML = coutMouv[0];
	document.forms["formUp"].mouvUp.value = 0;
	mouvNv = 0;

	document.getElementById("valPaAff").innerHTML = paValBase + paMult*paNvBase;
	document.getElementById("coutPaAff").innerHTML = coutPa[0];
	document.forms["formUp"].paUp.value = 0;
	paNv = 0;

	document.getElementById("valdesAff").innerHTML = desValBase + desMult*desNvBase;
	document.getElementById("coutdesAff").innerHTML = coutdes[0];
	document.forms["formUp"].desUp.value = 0;
	desNv = 0;

	document.getElementById("valForceAff").innerHTML = forceValBase + forceMult*forceNvBase;
	document.getElementById("coutForceAff").innerHTML = coutForce[0];
	document.forms["formUp"].forceUp.value = 0;
	forceNv = 0;

	document.getElementById("valPercAff").innerHTML = percValBase + percNvBase;
	document.getElementById("coutPercAff").innerHTML = coutPerc[0];
	document.forms["formUp"].percUp.value = 0;
	percNv = 0;

	if(document.getElementById("valNvMagAff") && document.getElementById("coutNvMagAff")){
		document.getElementById("valNvMagAff").innerHTML = nvMagNvBase;
		document.getElementById("coutNvMagAff").innerHTML = coutNvMag[0];
		document.forms["formUp"].nvMagUp.value = 0;
		nvMagNv = 0;

		if(nvMagNv+nvMagNvBase >= 5){
			document.getElementById("nvMagPv").innerHTML = "";
			document.getElementById("coutNvMagAff").innerHTML = "N/A";
		}
		else{
			document.getElementById("coutNvMagAff").innerHTML = coutNvMag[0];
			document.getElementById("upNvMag").innerHTML = '<input type="button" value="+" onclick="upCar(7);"/>';
		}
	}
	if(!admin_mode){
				document.getElementById("nbrPi").innerHTML = piBase;
				} else {
					document.getElementById("nbrPi").innerHTML = 1000000000-piBase;
					}
	pi = piBase;
}


function submitEvo(){
	document.forms["formUp"].submit();
}

upgradeDyn();

