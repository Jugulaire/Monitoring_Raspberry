//Modifier la valeur de l'adresse IP par celle de votre RPI
//Pour connaitre votre IP faites: 
//si liaison filaire: ifconfig eth0 | grep "inet addr"
//si lien wifi: ifconfig wlan0 | grep "inet addr"

var ipAdress = "192.168.0.10";

//Requis pour AJAX 
function getXMLHttpRequest() {
	var xhr =null;

	if (window.XMLHttpRequest || window.ActiveXObject)
	{
		if(window.ActiveXObject)
		{
			try
			{
				xhr = new ActiveXObject("Msxm12.XMLHTTP");
			}catch(e)
			{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else 
		{
			xhr= new XMLHttpRequest();
		}
	} 
	else 
	{
		alert("Navigateur incompatible");
		return null;
	}
	
	return xhr;
};
window.onload = function monitoring ()
{
	var xhr = getXMLHttpRequest();
	xhr.open("GET", "http://" + ipAdress + "/?value=rapport" ,true);
	xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xhr.send();

	xhr.onreadystatechange = function() //appeler a chaque changement d'etat
	{ 
		//alert(xhr.readyState); //debug 
		
		if(xhr.readyState == 4 ) //etat 4 données recus
		{
			var data = JSON.parse(xhr.responseText);
			//temperature CPU
			var temp = document.getElementById('temp');
			var valTemp = temp.lastElementChild;
			valTemp.innerHTML = data.tempCpu + ' °C';
			if(parseFloat(data.tempCpu)> 60.00)
			{
				valTemp.style.color="red";
			}
			else 
			{
				valTemp.style.color="green";
			}
			//LoadAverage
			var load = document.getElementById('loadaverage');
			var valAverage = load.lastElementChild;
			valAverage.innerHTML = data.loadAverage;
			//ram utilisée
			var ramUsed = document.getElementById('ramUsed');
                        var valRamUsed = ramUsed.lastElementChild;
                        valRamUsed.innerHTML = data.ramUsed + ' MB' + " sur " + data.ramTotal + " MB disponible";
			if(parseFloat(data.ramUsed)> 300)
                        {
                                valRamUsed.style.color="red";
                        }
                        else if (parseFloat(data.ramUsed)> 220)
                        {
                                valRamUsed.style.color="orange";
                        }
			else 
			{
				valRamUsed.style.color="green";
			}

			//uptime
			var up = document.getElementById('uptime');
                        var valUp = up.lastElementChild;
                        valUp.innerHTML = data.uptime;
			//cpu user
			var user = document.getElementById('user');
			var valuser = user.lastElementChild;
			valuser.innerHTML = data.user;
			//cpu sys 
			var sys = document.getElementById('sys');
                        var valsys = sys.lastElementChild;
                        valsys.innerHTML = data.sys;
			//cpu idle
			var idle = document.getElementById('idle');
                        var validle = idle.lastElementChild;
                        validle.innerHTML = data.idle;
			//ESSID 
			var essid = document.getElementById('ESSID');
                        var valessid = essid.lastElementChild;
                        valessid.innerHTML = data.wifiEssid;
			//frequence cpu
			var freq = document.getElementById('cpufreq');
                        var valfreq = freq.lastElementChild;
                        valfreq.innerHTML = data.cpufreq;
			//recurence
			setTimeout(function()
			{
				monitoring();
			},1000);
		} 
	};


};
