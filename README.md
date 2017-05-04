# Monitoring_Raspberry

# Description: 

Interface de monitoring web pour raspberry Pi.

# Ajout récents:

* [Frontend] Nouveau style.
* [Backend] Performances ameliorées.
* [Frontend] Création d'une interface temps réel avec des jauges.
* [Script.js] Ajout d'une variable globale pour le parametrage de l'ip surveillée.
* [script.js] Correction du bug de colorisation de la memoire utilisée.
* [style.css] Ajout d'une fonction reboot et extinction distante.
* [Backend] Correction du bug de RAM libre.

# To-do: 
 
* [Frontend] Ajout d'une fonction pour interagir avec le gpio. 
* [Backend/Frontend] Création d'une documentation.
* [Frontend] Création de messages dynamiques en haut de page.

# ScreenShot: 
 

![Alt text] (https://github.com/Jugulaire/Monitoring_Raspberry/blob/master/screenShot.png?raw=true)

# Pré-requis: 

* Un serveur web fonctionnel avec PHP5.
* Un Raspberry Pi connecté en local avec une IP fixe.
* Un acces FTP. 

# Installation: 

*  Téléchargement de l'archive de base:   
```
wget https://github.com/Jugulaire/Monitoring_Raspberry/archive/master.zip
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
[retirez 192.168.0.10 et placez-y votre ip locale ] 
```
Note : Si le monitoring se situe dans un sous-dossier comme par exemple /var/www/html/votreDossier moddifier 192.168.0.10 par votreIp/votreDossier.

# Utilisation: 

Ouvrez une page de votre navigateur favori et tapez 
```
http://votreIP/monitor.html 

```
Note: Si les fichiers sont placés dans un sous-dossier (/var/www/html/monDossier) tapez :
```
http://votreIP/monDossier/monitor.html
```

