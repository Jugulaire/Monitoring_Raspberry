
  var ip = window.location.toString();
  var arrayIp=ip.split("/");
  var ipAdress = arrayIp[2];

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
  	xhr.send();

  	xhr.onreadystatechange = function() //appeler a chaque changement d'etat
  	{
  		//alert(xhr.readyState); //debug

  		if(xhr.readyState == 4 ) //etat 4 données recus
  		{
  			var data = JSON.parse(xhr.responseText);
  			//Usage HDD
  			g.refresh(data.diskUsed,data.diskTotal);

  			//ram utilisée
        		g3.config.setMax = data.ramTotal;
  			g3.refresh(data.ramUsed,data.ramTotal);

  			//uptime
  			var up = document.getElementById('uptime');
                        var valUp = up.lastElementChild;
                        valUp.innerHTML = data.uptime;

  			//load
        		g4.refresh(data.loadAverage);

  			//cpu usage
			if(!isNaN(data.cpuIdle)) 
  		    		g2.refresh(data.cpuIdle);

			//Hostname
			document.getElementById('hostname').innerHTML = data.hostname;
			document.title = "[" + data.hostname +"] ";
  			//recurence
  			setTimeout(function()
  			{
  				monitoring();
  			},10000);
  		}
  	};


  };
