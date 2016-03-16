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
			print("Error, no value specified by user");
		}
}

function fct_get($val)
{
	//Preparations des valeurs CPU_Idle
	$cpuIdle = intval(exec('top -bn1| grep "Cpu(s)" | sed "s/\ \ */\ /g" | cut -d " " -f8'));
	

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

	//Preparation valeurs wifi (ESSID)
	$buf_wifiessid = exec("/sbin/iwconfig wlan0 | grep 'ESSID'");
	$exp_wifiessid = explode(" ",$buf_wifiessid);
	$toDelete = array( "ESSID", ":", "\"");
	$wifiessid =str_replace($toDelete,"",$exp_wifiessid[8]);

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

	//Cpu Frequence
	$cpufreq=exec("cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq");
	$cpufreq = $cpufreq / 1000;

	//temperature cpu
	$tmp =  exec('cat /sys/class/thermal/thermal_zone0/temp');
	$temp = round( $tmp/1000 );
	
	//Hostname
	$hostName = exec ('/bin/hostname');

	//usage disque 
	$diskTotal = exec ('/bin/df -h / | /bin/grep "/" | /bin/sed "s/\ \ */\ /g" | /usr/bin/cut -d " " -f2 | /bin/sed "s/G/\ /g"');
	
	$diskUsed = exec ('/bin/df -h / | /bin/grep "/" | /bin/sed "s/\ \ */\ /g" | /usr/bin/cut -d " " -f3 | /bin/sed "s/G/\ /g"');
	
	$diskFree = exec ('/bin/df -h / | /bin/grep "/" | /bin/sed "s/\ \ */\ /g" | /usr/bin/cut -d " " -f4 | /bin/sed "s/G/\ /g"');
 
	//Switch pour renvoyer les valeurs.
	switch($val)
	{
		case ("temp") ://Demande de temperature cpu
		print( json_encode($tmp2));
		break;

		case ("CPU_freq") : //Frequence cpu
		print(json_encode($cpufreq));
		break;



		case("CPU_idle") : //Pourcentage UC utiliser par des processus system
		print(json_encode($cpuidle));
		break;


		case("CPU_load_average") : //Pourcentage UC utiliser sur les 15 dernieres minutes
		print(json_encode($average));
		break;

		case("Mem_total") : //Ram installé
		print(json_encode($memTotal));
		break;

		case("Mem_used") : //ram utiliser
		print(json_encode($memUsed));
		break;

		case("Mem_free") : //ram disponible
    		print(json_encode($memFree));
    		break;

		case("Wifi_ESSID") : //Point de connexion wifi
		print(json_encode($wifiessid));
		break;

		case ("uptime") : //Uptime de la machine
		print ($uptime);
		break;

		case ("rapport") :
		$data = array(
				"tempCpu" => $temp,
				"loadAverage" => $average,
				"ramUsed" => $memUsed,
				"ramTotal" => $memTotal,
				"uptime" => $uptime,
				"cpuIdle" => $cpuIdle,
				"wifiEssid" => $wifiessid,
				"cpufreq" => $cpufreq,
				"hostname"=> $hostName,
				"diskTotal"=> $diskTotal,
				"diskUsed"=> $diskUsed,
				"diskFree"=> $diskFree 
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
		print("Wrong value\n");
		break;
	}
}
?>
