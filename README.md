# Monitoring_Raspberry

#Description: 

Interface de monitoring web pour raspberry Pi.

#Ajout récents:

* [Backend] Ajout des fonctions load average,rapport et uptime.
* [Backend] Performances ameliorées.
* [Frontend] Creation d'une interface temps réel de base.
* [Script.js] Ajout d'une variable globale pour le parametrage de l'ip surveillée.  
* [index.php] Correction du bug de memoire libre fausse. 
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

*  Téléchargement de l'archive de base:   
```
wget https://https://github.com/Jugulaire/Monitoring_Raspberry/archive/master.zip
```
*  Décompression de l'archive:
```
unzip master.zip
```
*  Déplacement des fichier vers /var/www
```
cd Monitoring_Raspberry-master
mv index.php monitor.html script.js style.css /var/www
```
*  Changer le proprietaire de index.php (Obligatoire pour les exec)
```
cd /var/www
chown www-data index.php
```
*  Modifier script.js pour parametrer l'ip
```
sudo vim script.js
[retirez 192.168.0.10 et placez y votre ip locale ] 
```

#Utilisation: 

Ouvrez une page de votre navigateur favoris et tapez 
```
http://votreIP/monitor.html 

```

