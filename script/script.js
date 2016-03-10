  
  //Modifier la valeur de l'adresse IP par celle de votre RPI
  //Pour connaitre votre IP faites:
  //si liaison filaire: ifconfig eth0 | grep "inet addr"
  //si lien wifi: ifconfig wlan0 | grep "inet addr"


  var ipAdress = "192.168.2.7/html/interface%20rpi/index.php";

  //Fonction pour une meilleur compatibilite de l'Ajax
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

function reboot()
{
	var r = confirm("Etes vous sur ?");
	if (r == true)
	{
		var xhr = new getXMLHttpRequest(); //création 
		xhr.open("GET", "http://"+ ipAdress + "/?value=rbt",false);
		xhr.send(null);
	}
}
function turnoff()
{
	var r = confirm("Etes vous sur ?");
	if (r == true)
	{
		var xhr = new getXMLHttpRequest(); //création 
		xhr.open("GET", "http://"+ ipAdress + "/?value=trnoff",false);
		xhr.send(null);
	}
	
}


//Fonction charger au chargement de la page
  window.onload = function monitoring ()
  {
  	var xhr = getXMLHttpRequest();
  	xhr.open("GET", "http://" + ipAdress + "/?value=rapport" ,true);
  	//xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  	xhr.send();

  	xhr.onreadystatechange = function() //appeler a chaque changement d'etat
  	{
  		//alert(xhr.readyState); //debug

  		if(xhr.readyState == 4 ) //etat 4 données recus
  		{
  			var data = JSON.parse(xhr.responseText);
  			//temperature CPU
  			g.refresh(data.tempCpu);

  			//ram utilisée
        		g3.config.setMax = data.ramTotal;
  			g3.refresh(data.ramUsed,data.ramTotal);

  			//uptime
  			var up = document.getElementById('uptime');
                        var valUp = up.lastElementChild;
                        valUp.innerHTML = data.uptime;

  			//load
        		g4.refresh(data.loadAverage);

  			//frequence cpu
  		    	g2.refresh(data.cpufreq);

			//Hostname
			document.getElementById('hostname').innerHTML = data.hostname;
			document.title = "[" + data.hostname +"] " + data.tempCpu + "°C";
  			//recurence
  			setTimeout(function()
  			{
  				monitoring();
  			},10000);
  		}
  	};


  };
