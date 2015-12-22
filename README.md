# Monitoring_Raspberry

#Description: 

Interface de monitoring web pour raspberry Pi.

#Ajout récents:

* [Backend] Ajout des fonctions load average,rapport et uptime.
* [Backend] Performances ameliorées.
* [Frontend] Creation d'une interface temps réel de base.
* [Script.js] Ajout d'une variable globale pour le parametrage de l'ip surveillée.  

#To-do: 
 
* [Frontend] Ajout d'autres affichages comme la charge CPU.
* [Frontend] Ajout de graphiques pour mieux illustrer les valeurs afficher. 
* [Backend/Frontend] Création d'une documentation.
* [Frontend] Ajout d'une page de parametrage.

#ScreenShot: 

![Alt text] (https://github.com/Jugulaire/Monitoring_Raspberry/blob/master/screenShot.png?raw=true)

#Pré-requis: 

* Un serveur web fonctionnel avec PHP5.
* Un Raspberry Pi connecté en local avec une IP fixe.
* Un acces FTP. 

#Installation: 

1. Téléchargement de l'archive de base:
   
```
wget https://https://github.com/Jugulaire/Monitoring_Raspberry/archive/master.zip
```

2. Décompression de l'archive:

```
unzip master.zip

```

3. Déplacement des fichier vers /var/www
```
cd Monitoring_Raspberry-master
mv index.php monitor.html script.js style.css /var/www

```

4. Changer le proprietaire de index.php (Obligatoire pour les exec)
```
cd /var/www
chown www-data index.php

```

5. Modifier script.js pour parametrer l'ip

```
sudo vim script.js
[retirez 192.168.0.10 et placez y votre ip locale ] 
```


#Utilisation: 

Ouvrez une page de votre navigateur favoris et tapez 
```
http://votreIP/monitor.html 

```

#Problemes connus: 

1. La valeur RAM occupée est fausse.
Cést du a un soucis que je n'ai pas encore resolu dans index.php, lors de la recuperation de la valeur RAM libre ou total le parcour du tableau est faux sur certaines version de raspbian. 
