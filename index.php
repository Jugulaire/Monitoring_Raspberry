<?php
//API monitoring Raspberry pi //
//Crée par Jugulaire //

switch ($_SERVER['REQUEST_METHOD'])//On detérmine le type de requete HTTP (GET, POST,etc...)
{
	case('GET') ://Seule le GET est utilisé dans notre cas
		if(isset($_GET['value']))//Si une valeur est demandé
		{
			fct_get($_GET['value']);//Appele de la fonction correspondante
			break;
		}
		else//Si aucune valeur n'est demander
		{
			header('Location: monitor.html ');
		}
}

function fct_get($val)
{
	//Placez ici le nom de l'interface reseau a surveiller 	
	$interface = 'wlan0';


	//Preparations des valeurs CPU_Idle
	$cpuIdle = exec('top -bn 2| grep "Cpu(s)" | sed "s/\ \ */\ /g" | cut -d " " -f8');

	//Rx
	$rxCmd="cat /sys/class/net/{$interface}/statistics/rx_bytes";
	$rxBytes = exec($rxCmd);
	$rx = $rxBytes/1000000;
	//Tx	
	$txCmd="cat /sys/class/net/{$interface}/statistics/tx_bytes";
	$txBytes = exec($txCmd);
	$tx = $txBytes/1000000;

	//Preaparation des infos memoire (ram)
	$buf_memfree =  exec("cat /proc/meminfo | grep -i '^memfree' | tr 'A-z :' ' ' | sed -e 's/ *//g'");
	$memFree=round($buf_memfree/1024,0);

	//Memoire installer
	$buf_memtot = exec("cat /proc/meminfo | grep -i '^memtotal' | tr 'A-z :' ' ' | sed -e 's/ *//g'");
	$memTotal=round($buf_memtot/1024,0);

	//Memoire cached 
	$buf_memCached = exec("cat /proc/meminfo | grep -i '^cached' | tr 'A-z :' ' ' | sed -e 's/ *//g'");
	$memCached=round($buf_memCached/1024,0);

	//Memoire buffered
	$buf_memBuffered = exec("cat /proc/meminfo | grep -i '^buffers' | tr 'A-z :' ' ' | sed -e 's/ *//g'");
	$memBuffered=round($buf_memBuffered/1024,0);

	//préparation uptime
	$upSecondes = exec("/usr/bin/cut -d. -f1 /proc/uptime");
	$sec = ($upSecondes%60);
	$min = ($upSecondes/60%60);
	$heures = ($upSecondes/3600%24);
	$days = ($upSecondes/86400);
	$uptime = sprintf("%d jour(s) %02d heure(s) %02d minute(s) %02d seconde(s)" , $days,$heures,$min,$sec);

	//Ram utiliser
	$memUsed = $memTotal-($memBuffered+$memCached+$memFree);

	//load average
	$bufAverage = exec ("cat /proc/loadavg");
	$expAverage = explode(" ",$bufAverage);
	$average = $expAverage[2];

	//Hostname
	$hostName = exec ('/bin/hostname');

	//usage disque 
	$diskTotal = exec ('/bin/df -h / | /bin/grep "/" | /bin/sed "s/\ \ */\ /g" | /usr/bin/cut -d " " -f2 | /bin/sed "s/G/\ /g"');
	
	$diskUsed = exec ('/bin/df -h / | /bin/grep "/" | /bin/sed "s/\ \ */\ /g" | /usr/bin/cut -d " " -f3 | /bin/sed "s/G/\ /g"');
	
	$diskFree = exec ('/bin/df -h / | /bin/grep "/" | /bin/sed "s/\ \ */\ /g" | /usr/bin/cut -d " " -f4 | /bin/sed "s/G/\ /g"');
 
	//Switch pour renvoyer les valeurs.
	switch($val)
	{
		case ("rapport") :
		$data = array(
				"loadAverage" => $average,
				"ramUsed" => $memUsed,
				"ramTotal" => $memTotal,
				"uptime" => $uptime,
				"cpuIdle" => $cpuIdle,
				"hostname"=> $hostName,
				"diskTotal"=> $diskTotal,
				"diskUsed"=> $diskUsed,
				"diskFree"=> $diskFree,
				"rx"=>$rx,
				"tx"=>$tx
		);
		print ( json_encode($data));
		break;

		case ("rbt") : //reboot 
		exec ('sudo /sbin/reboot');
		break;
		
		case ("trnoff"):
		exec ('sudo /sbin/shutdown -h now');		
		break;

		default:
		header('Location: monitor.html ');
		break;
	}
}
?>
