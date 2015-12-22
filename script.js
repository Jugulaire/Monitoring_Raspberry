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
			var data = JSON.parse(xhr.responseText); // Json vers tab
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
                        valRamUsed.innerHTML = data.ramUsed + ' MB';
			//uptime
			var up = document.getElementById('uptime');
                        var valUp = up.lastElementChild;
                        valUp.innerHTML = data.uptime;
			//recurence
			setTimeout(function()
			{
				monitoring();
			},1000);
		} 
	};


};
