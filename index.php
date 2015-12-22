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
	//Preparations des valeurs CPU_Load
	$fd = fopen("/proc/stat","r");
        if ($fd) {
            $statinfo = explode("\n",fgets($fd, 1024));
            fclose($fd);
            foreach($statinfo as $line) {
                $info = explode(" ",$line);
                if($info[0]=="cpu") {
                    array_shift($info);
                    if(!$info[0]) array_shift($info);
                    $total = $info[0] + $info[1] + $info[2] + $info[3];
                    $total = $total/100;
                    $user = round($info[0] / $total, 2);
                    $nice = round($info[1] / $total, 2);
                    $system = round($info[2] / $total, 2);
                    $idle = round($info[3] / $total, 2);
                }
            }
	}
	//Preaparation des infos memoire (ram)
	$buf_memfree =  exec("cat /proc/meminfo | grep 'MemFree:'");
	$exp_memfree = explode(" ",$buf_memfree);
	if (!empty($exp_memfree[9]))
	{
		$memFree = round($exp_memfree[9]/1000 , 2);
	}
	else 
	{
		$memFree = round($exp_memfree[10]/1000 , 2);
	}
	//Memoire installer
	$buf_memtot = exec("cat /proc/meminfo | grep 'MemTotal'");
  	$exp_memtot = explode(" ",$buf_memtot);
	if (!empty($exp_memtot[8]))
        {
                $memTotal = round($exp_memtot[8]/1000 , 2);
        }
        else
        {
                $memTotal = round($exp_memtot[9]/1000 , 2);
        }		

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
	$memUsed = $memTotal-$memFree;

	//load average
	$bufAverage = exec ("cat /proc/loadavg");
	$expAverage = explode(" ",$bufAverage);
	$average = $expAverage[2];

	//Cpu Frequence
	$cpufreq=exec("cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq");
	$cpufreq = $cpufreq / 1000;

	//temperature cpu
	$tmp =  exec('cat /sys/class/thermal/thermal_zone0/temp');
	$temp =round( $tmp/1000 );

	//Switch pour renvoyer les valeurs.
	switch($val)
	{
		case ("temp") ://Demande de temperature cpu
		print( json_encode($tmp2));
		break;

		case ("CPU_freq") : //Frequence cpu
		print(json_encode($cpufreq));
		break;

		case("CPU_load_user") ://Pourcentage UC utiliser par des processus user
		print(json_encode($user));
		break;

		case("CPU_load_nice") : //Pourcentage UC utiliser par des processus nicé
		print(json_encode($nice));
		break;

		case("CPU_load_system") : //Pourcentage UC utiliser par des processus system
		print(json_encode($system));
		break;

		case("CPU_load_idle") : //Pourcentage de l'uc libre
		print(json_encode($idle));
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
				"user" => $user,
				"sys" => $system,
				"idle" => $idle,
				"wifiEssid" => $wifiessid,
				"cpufreq" => $cpufreq 
		);
		print ( json_encode($data));
		break;

		default:
		print("Wrong value");
		break;
	}
}
?>
